<?php
require_once '../includes/config.php';header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD']!=='POST'){echo json_encode(['success'=>false]);exit;}
try{
    $db=Database::getInstance();
    $name=sanitize($_POST['name']??'');$email=sanitize($_POST['email']??'');
    $phone=sanitize($_POST['phone']??'');$subject=sanitize($_POST['subject']??'');$message=sanitize($_POST['message']??'');
    if(empty($name)||empty($email)||empty($subject)||empty($message)){echo json_encode(['success'=>false,'message'=>'All fields required']);exit;}
    $db->insert("INSERT INTO contact_messages(name,email,phone,subject,message) VALUES(?,?,?,?,?)",[$name,$email,$phone,$subject,$message]);
    echo json_encode(['success'=>true]);
}catch(Exception $e){echo json_encode(['success'=>false,'message'=>$e->getMessage()]);}
