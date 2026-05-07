<?php
// M-Pesa B2C Result Callback — receives payment confirmation from Safaricom
// Safaricom calls this URL after the payment is processed (success or failure)
require_once '../includes/config.php';

$callbackData = json_decode(file_get_contents('php://input'), true);

try {
    // B2C result is inside Result object (different from STK which uses Body.stkCallback)
    $result         = $callbackData['Result']               ?? [];
    $resultCode     = $result['ResultCode']                 ?? -1;
    $resultDesc     = $result['ResultDesc']                 ?? '';
    $conversationId = $result['ConversationID']             ?? '';
    $originatorId   = $result['OriginatorConversationID']   ?? '';

    $db = Database::getInstance();

    if($resultCode == 0){
        // Payment was successful — extract the M-Pesa receipt and amount
        $mpesaCode   = '';
        $amount      = 0;
        $phone       = '';
        $receiverName = '';

        $params = $result['ResultParameters']['ResultParameter'] ?? [];
        foreach($params as $param){
            switch($param['Key']){
                case 'TransactionReceipt':      $mpesaCode    = $param['Value']; break;
                case 'TransactionAmount':       $amount       = $param['Value']; break;
                case 'ReceiverPartyPublicName': $receiverName = $param['Value']; break;
                case 'B2CRecipientIsRegisteredCustomer':
                    // confirms farmer is a registered M-Pesa user
                    break;
            }
        }

        // Update payment record — match by conversation ID stored during B2C request
        $db->update(
            "UPDATE payments 
             SET payment_status='completed', 
                 mpesa_receipt=?, 
                 notes=CONCAT(IFNULL(notes,''), ' | M-Pesa B2C: ', ?)
             WHERE mpesa_checkout_id=?",
            [$mpesaCode, $mpesaCode, $conversationId]
        );

        logActivity(
            'system', 'MPESA', 'B2C Payment Success',
            "Receipt:$mpesaCode Amount:$amount Receiver:$receiverName ConversationID:$conversationId"
        );

    } else {
        // Payment failed — mark as failed and store the reason
        $db->update(
            "UPDATE payments 
             SET payment_status='failed',
                 notes=CONCAT(IFNULL(notes,''), ' | Failed: ', ?)
             WHERE mpesa_checkout_id=?",
            [$resultDesc, $conversationId]
        );

        logActivity(
            'system', 'MPESA', 'B2C Payment Failed',
            "Code:$resultCode Desc:$resultDesc ConversationID:$conversationId"
        );
    }

} catch(Exception $e){
    // Log silently — always return 200 to Safaricom
}

// Always respond 200 to Safaricom — otherwise they keep retrying
http_response_code(200);
echo json_encode(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);