<?php
session_start();require_once '../includes/config.php';header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD']!=='POST'){echo json_encode(['success'=>false]);exit;}
try{
    $db=Database::getInstance();
    if($_SESSION['staff_role']??''!=='admin'){echo json_encode(['success'=>false,'message'=>'Admin only']);exit;}
    foreach(['A','B','C'] as $g){
        $price=floatval($_POST['price_'.$g]??0);
        $bonus=floatval($_POST['bonus_'.$g]??0);
        if($price>0){
            $db->update("UPDATE price_config SET status='inactive' WHERE grade=?",[$g]);
            $db->insert("INSERT INTO price_config(grade,price_per_litre,quality_bonus,effective_date,updated_by) VALUES(?,?,?,CURDATE(),?)",[$g,$price,$bonus,$_SESSION['staff_id']??'STAFF']);
        }
    }
    logActivity('staff',$_SESSION['staff_id']??'STAFF','Update Prices','Milk prices updated');
    echo json_encode(['success'=>true]);
}catch(Exception $e){echo json_encode(['success'=>false,'message'=>$e->getMessage()]);}
