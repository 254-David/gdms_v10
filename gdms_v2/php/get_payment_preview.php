<?php
session_start();
require_once '../includes/config.php';
header('Content-Type: application/json');
$farmerId=sanitize($_GET['farmer_id']??'');
$start=sanitize($_GET['start']??date('Y-m-01'));
$end=sanitize($_GET['end']??date('Y-m-d'));
if(empty($farmerId)){echo json_encode(['success'=>false]);exit;}
try {
    $db=Database::getInstance();
    $rows=$db->fetchAll("SELECT quality_grade,SUM(quantity_litres) as litres,SUM(total_amount) as amount FROM milk_deliveries WHERE farmer_id=? AND delivery_date BETWEEN ? AND ? AND payment_status='pending' GROUP BY quality_grade",[$farmerId,$start,$end]);
    $totalLitres=0;$base=0;$gradeA=0;$bonus=0;$breakdown=[];
    foreach($rows as $r){$totalLitres+=$r['litres'];$base+=$r['amount'];if($r['quality_grade']==='A'){$gradeA+=$r['litres'];$bonus+=$r['litres']*getQualityBonus('A');}$breakdown[]=$r;}
    $net=$base+$bonus;
    $farmer=$db->fetchOne("SELECT full_name,mpesa_number,bank_name,bank_account FROM farmers WHERE farmer_id=?",[$farmerId]);
    echo json_encode(['success'=>true,'total_litres'=>$totalLitres,'base'=>$base,'bonus'=>$bonus,'net'=>$net,'breakdown'=>$breakdown,'farmer'=>$farmer]);
} catch(Exception $e){echo json_encode(['success'=>false,'message'=>$e->getMessage()]);}
