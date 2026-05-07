<?php
session_start();
require_once '../includes/config.php';
header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo json_encode(['success'=>false]); exit;
}

function mpesaRequest($url, $headers, $body=null){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if($body !== null){
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }
    $response = curl_exec($ch);
    $err      = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($response === false) throw new Exception("Network error: $err");
    return ['body'=>$response, 'code'=>$httpCode];
}

try {
    $phone       = sanitize($_POST['phone']       ?? '');
    $amount      = intval($_POST['amount']         ?? 0);
    $payment_id  = sanitize($_POST['payment_id']  ?? '');
    $farmer_id   = sanitize($_POST['farmer_id']   ?? '');
    $farmer_name = sanitize($_POST['farmer_name'] ?? '');

    if(empty($phone) || $amount <= 0){
        echo json_encode(['success'=>false,'message'=>'Phone number and amount are required.']); exit;
    }

    // Normalize phone → 2547XXXXXXXX
    $phone = preg_replace('/[^0-9]/', '', $phone);
    if(substr($phone,0,1) === '0') $phone = '254'.substr($phone,1);
    if(strlen($phone) === 9)        $phone = '254'.$phone;
    if(strlen($phone) !== 12 || substr($phone,0,3) !== '254'){
        throw new Exception("Invalid phone number. Use format 07XXXXXXXX.");
    }

    // ── Step 1: Get OAuth token ──────────────────────────────────
    $credentials = base64_encode(MPESA_CONSUMER_KEY.':'.MPESA_CONSUMER_SECRET);
    $tokenResult = mpesaRequest(
        MPESA_TOKEN_URL,
        ["Authorization: Basic $credentials"]
    );

    if($tokenResult['code'] !== 200){
        throw new Exception("M-Pesa token failed (HTTP {$tokenResult['code']}). ".
            "Make sure your app has B2C enabled on developer.safaricom.co.ke");
    }

    $tokenData = json_decode($tokenResult['body'], true);
    $token     = $tokenData['access_token'] ?? '';
    if(empty($token)){
        throw new Exception('No access token returned. Go to developer.safaricom.co.ke → My Apps → enable Daraja B2C on your app.');
    }

    // ── Step 2: B2C — send money from cooperative TO farmer ─────
    // No certificate needed — using Safaricom portal-generated credential
    $payload = [
        'OriginatorConversationID' => 'GDMS-'.$payment_id.'-'.time(),
        'InitiatorName'            => MPESA_INITIATOR_NAME,
        'SecurityCredential'       => MPESA_SECURITY_CREDENTIAL,
        'CommandID'                => 'BusinessPayment',
        'Amount'                   => $amount,
        'PartyA'                   => MPESA_B2C_SHORTCODE,
        'PartyB'                   => $phone,
        'Remarks'                  => "Milk payment - $farmer_name",
        'QueueTimeOutURL'          => MPESA_B2C_TIMEOUT_URL,
        'ResultURL'                => MPESA_B2C_RESULT_URL,
        'Occasion'                 => $payment_id ?: 'MilkPayment',
    ];

    $b2cResult = mpesaRequest(
        MPESA_B2C_URL,
        ["Authorization: Bearer $token", "Content-Type: application/json"],
        json_encode($payload)
    );

    $b2cData = json_decode($b2cResult['body'], true);

    if(isset($b2cData['ResponseCode']) && $b2cData['ResponseCode'] === '0'){
        $conversationId = $b2cData['ConversationID'] ?? '';

        $db = Database::getInstance();
        if(!empty($payment_id)){
            try {
                $db->update(
                    "UPDATE payments SET mpesa_checkout_id=?, payment_status='processing' WHERE payment_id=?",
                    [$conversationId, $payment_id]
                );
            } catch(Exception $dbErr){}
        }

        logActivity('staff', $_SESSION['staff_id']??'STAFF', 'M-Pesa B2C Sent',
            "Phone:$phone Amount:KES$amount ConversationID:$conversationId");

        echo json_encode([
            'success'         => true,
            'message'         => "KES $amount sent to $phone ($farmer_name). Farmer will receive M-Pesa SMS shortly.",
            'conversation_id' => $conversationId,
        ]);

    } else {
        $errMsg = $b2cData['errorMessage']
               ?? $b2cData['ResponseDescription']
               ?? $b2cData['ResultDesc']
               ?? 'Unknown error: '.substr($b2cResult['body'],0,300);
        echo json_encode(['success'=>false,'message'=>"Safaricom: $errMsg"]);
    }

} catch(Exception $e){
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}