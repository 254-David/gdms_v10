<?php
session_start();require_once '../includes/config.php';header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD']!=='POST'){echo json_encode(['success'=>false]);exit;}
try{
    $db=Database::getInstance();
    $name=sanitize($_POST['full_name']??'');$email=sanitize($_POST['email']??'');
    $phone=sanitize($_POST['phone']??'');$username=sanitize($_POST['username']??'');
    $role=sanitize($_POST['role']??'data_entry');$code=sanitize($_POST['access_code']??ACCESS_CODE);
    if(empty($name)||empty($username)||empty($email)){echo json_encode(['success'=>false,'message'=>'Name, email and username required']);exit;}
    $c=$db->fetchOne("SELECT COUNT(*) as c FROM staff")['c'];
    $staffId='STF'.str_pad($c+1,3,'0',STR_PAD_LEFT);
    // Generate strong 12-char password: upper + lower + digits + symbols
    $chars='ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789@#$!';
    $rawPass='';
    for($i=0;$i<12;$i++) $rawPass.=$chars[random_int(0,strlen($chars)-1)];
    $pass=hashPassword($rawPass);
    $db->insert("INSERT INTO staff(staff_id,full_name,email,phone,role,username,password,access_code) VALUES(?,?,?,?,?,?,?,?)",[$staffId,$name,$email,$phone,$role,$username,$pass,$code]);
    logActivity('staff',$_SESSION['staff_id']??'STAFF','Add Staff',"Staff $staffId: $name");
    echo json_encode(['success'=>true,'staff_id'=>$staffId,'temp_password'=>$rawPass]);
}catch(Exception $e){echo json_encode(['success'=>false,'message'=>$e->getMessage()]);}
