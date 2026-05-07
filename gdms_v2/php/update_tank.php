<?php
session_start();require_once '../includes/config.php';header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD']!=='POST'){echo json_encode(['success'=>false]);exit;}
try{
    $db=Database::getInstance();
    $tankId=sanitize($_POST['tank_id']??'');
    $volume=floatval($_POST['current_volume']??0);
    $temp=$_POST['temperature']!==''?floatval($_POST['temperature']):null;
    $status=sanitize($_POST['status']??'active');
    $notes=sanitize($_POST['notes']??'');
    if(empty($tankId)){echo json_encode(['success'=>false,'message'=>'Tank ID required']);exit;}
    $db->update("UPDATE storage_tanks SET current_volume=?,temperature=?,status=?,notes=?,updated_at=NOW() WHERE tank_id=?",[$volume,$temp,$status,$notes,$tankId]);
    logActivity('staff',$_SESSION['staff_id']??'STAFF','Update Tank',"Tank $tankId: {$volume}L @ {$temp}°C");
    echo json_encode(['success'=>true]);
}catch(Exception $e){echo json_encode(['success'=>false,'message'=>$e->getMessage()]);}
