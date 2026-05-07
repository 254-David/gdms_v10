<?php
require_once '../includes/config.php';
$callbackData = json_decode(file_get_contents('php://input'), true);
$result = $callbackData['Result'] ?? [];
$code   = $result['ResultCode']  ?? -1;
$convId = $result['ConversationID'] ?? '';

if($code == 0 && $convId){
    $db = Database::getInstance();
    $db->update(
        "UPDATE payments SET payment_status='completed', payment_date=NOW() WHERE mpesa_checkout_id=?",
        [$convId]
    );
}
http_response_code(200);
echo json_encode(['ResultCode'=>0,'ResultDesc'=>'Success']);