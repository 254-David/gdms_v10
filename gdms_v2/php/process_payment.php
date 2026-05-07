<?php
session_start();
require_once '../includes/config.php';
header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD']!=='POST'){echo json_encode(['success'=>false]);exit;}
try {
    $db          = Database::getInstance();
    $farmerId    = sanitize($_POST['farmer_id']??'');
    $periodStart = sanitize($_POST['period_start']??'');
    $periodEnd   = sanitize($_POST['period_end']??'');
    $method      = sanitize($_POST['payment_method']??'mpesa');
    $deductions  = floatval($_POST['deductions']??0);
    $deductReason= sanitize($_POST['deduction_reason']??'');
    $notes       = sanitize($_POST['notes']??'');

    if(empty($farmerId)||empty($periodStart)||empty($periodEnd)){
        echo json_encode(['success'=>false,'message'=>'Required fields missing']);exit;
    }

    $deliveries = $db->fetchAll(
        "SELECT * FROM milk_deliveries 
         WHERE farmer_id=? AND delivery_date BETWEEN ? AND ? 
         AND payment_status='pending'
         AND quality_grade != 'rejected'
         AND quality_grade IS NOT NULL",
        [$farmerId,$periodStart,$periodEnd]
    );

    if(empty($deliveries)){
        echo json_encode(['success'=>false,'message'=>'No payable deliveries found. Rejected or ungraded milk cannot be paid.']);exit;
    }

    $totalLitres=0; $gradeA=0; $gradeB=0; $gradeC=0; $baseAmount=0; $bonus=0;
    foreach($deliveries as $d){
        $totalLitres += $d['quantity_litres'];
        $baseAmount  += $d['total_amount'];
        if($d['quality_grade']==='A'){
            $gradeA += $d['quantity_litres'];
            $bonus  += $d['quantity_litres'] * getQualityBonus('A');
        } elseif($d['quality_grade']==='B'){
            $gradeB += $d['quantity_litres'];
        } else {
            $gradeC += $d['quantity_litres'];
        }
    }

    $net = max(0, $baseAmount + $bonus - $deductions);

    if($net <= 0){
        echo json_encode(['success'=>false,'message'=>'Net payment amount is KES 0. Cannot process a zero payment.']);exit;
    }

    $payId = generatePaymentId();

    $db->insert(
        "INSERT INTO payments(
            payment_id,farmer_id,processed_by,
            payment_period_start,payment_period_end,
            total_litres,grade_a_litres,grade_b_litres,grade_c_litres,
            base_amount,quality_bonus,deductions,deduction_reason,
            net_amount,payment_method,payment_status,payment_date,notes
        ) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'completed',NOW(),?)",
        [
            $payId,$farmerId,$_SESSION['staff_id']??'STAFF',
            $periodStart,$periodEnd,
            $totalLitres,$gradeA,$gradeB,$gradeC,
            $baseAmount,$bonus,$deductions,$deductReason,
            $net,$method,$notes
        ]
    );

    // Only mark non-rejected deliveries as paid
    $db->update(
        "UPDATE milk_deliveries SET payment_status='paid' 
         WHERE farmer_id=? AND delivery_date BETWEEN ? AND ?
         AND quality_grade != 'rejected'",
        [$farmerId,$periodStart,$periodEnd]
    );

    $db->update(
        "UPDATE farmers SET total_earnings=total_earnings+? WHERE farmer_id=?",
        [$net,$farmerId]
    );

    logActivity('staff',$_SESSION['staff_id']??'STAFF','Process Payment',"$payId for $farmerId: KES $net");
    echo json_encode(['success'=>true,'payment_id'=>$payId,'amount'=>$net,'litres'=>$totalLitres]);

} catch(Exception $e){
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}