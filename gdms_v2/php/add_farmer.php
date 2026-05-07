<?php
session_start();require_once '../includes/config.php';header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD']!=='POST'){echo json_encode(['success'=>false]);exit;}
try{
    $db=Database::getInstance();
    $name=sanitize($_POST['full_name']??'');$email=sanitize($_POST['email']??'');
    $phone=sanitize($_POST['phone']??'');$idNum=sanitize($_POST['id_number']??'');
    $location=sanitize($_POST['location']??'');$ward=sanitize($_POST['ward']??'');
    $cows=intval($_POST['number_of_cows']??0);$breeds=sanitize($_POST['cow_breeds']??'');
    $bank=sanitize($_POST['bank_name']??'');$acc=sanitize($_POST['bank_account']??'');
    $mpesa=sanitize($_POST['mpesa_number']??'');
    if(empty($name)||empty($phone)||empty($idNum)){echo json_encode(['success'=>false,'message'=>'Name, phone and ID required']);exit;}
    $existing=$db->fetchOne("SELECT id FROM farmers WHERE id_number=? OR phone=? OR email=?",[$idNum,$phone,$email]);
    if($existing){echo json_encode(['success'=>false,'message'=>'Farmer with this ID/phone/email already exists']);exit;}
    $farmerId=generateFarmerId();
    // Generate strong 12-char password: upper + lower + digits + symbols
    $chars='ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789@#$!';
    $rawPass='';
    for($i=0;$i<12;$i++) $rawPass.=$chars[random_int(0,strlen($chars)-1)];
    $pass=hashPassword($rawPass);
    $db->insert("INSERT INTO farmers(farmer_id,full_name,email,phone,id_number,password,location,ward,number_of_cows,cow_breeds,bank_name,bank_account,mpesa_number) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)",
        [$farmerId,$name,$email,$phone,$idNum,$pass,$location,$ward,$cows,$breeds,$bank,$acc,$mpesa]);
    logActivity('staff',$_SESSION['staff_id']??'STAFF','Add Farmer',"Farmer $farmerId: $name");
    echo json_encode(['success'=>true,'farmer_id'=>$farmerId,'temp_password'=>$rawPass]);
}catch(Exception $e){echo json_encode(['success'=>false,'message'=>$e->getMessage()]);}
