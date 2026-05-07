<?php
session_start();
require_once '../includes/config.php';
requireStaffLogin();
header('Content-Type: application/json');
$id = sanitize($_GET['id'] ?? '');
if(empty($id)){echo json_encode(['success'=>false]);exit;}
try {
    $db = Database::getInstance();
    $d = $db->fetchOne(
        "SELECT d.*, f.full_name as farmer_name FROM milk_deliveries d 
         JOIN farmers f ON d.farmer_id=f.farmer_id 
         WHERE d.delivery_id=?", [$id]
    );
    if(!$d){echo json_encode(['success'=>false,'message'=>'Delivery not found']);exit;}
    echo json_encode(['success'=>true,'delivery'=>$d]);
} catch(Exception $e){
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}
