<?php
session_start();
require_once '../includes/config.php';
header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD']!=='POST'){echo json_encode(['success'=>false,'message'=>'Invalid request']);exit;}
try {
    $db = Database::getInstance();
    $farmerId    = sanitize($_POST['farmer_id']??'');
    $date        = sanitize($_POST['delivery_date']??date('Y-m-d'));
    $time        = sanitize($_POST['delivery_time']??date('H:i'));
    $session     = sanitize($_POST['session']??'morning');
    $quantity    = floatval($_POST['quantity_litres']??0);
    $storageTank = sanitize($_POST['storage_tank']??'');
    $notes       = sanitize($_POST['notes']??'');
    $recordedBy  = $_SESSION['staff_id']??'STAFF';

    // Quality inputs
    $temperature = $_POST['temperature']!==''     ? floatval($_POST['temperature'])     : null;
    $fat         = $_POST['fat_content']!==''     ? floatval($_POST['fat_content'])     : null;
    $protein     = $_POST['protein_content']!=='' ? floatval($_POST['protein_content']) : null;
    $acidity     = $_POST['acidity']!==''         ? floatval($_POST['acidity'])         : null;
    $snf         = $_POST['snf']!==''             ? floatval($_POST['snf'])             : null;
    $water       = $_POST['water_content']!==''   ? floatval($_POST['water_content'])   : 0;
    $antibiotic  = sanitize($_POST['antibiotic_test']??'pending');
    $alcohol     = sanitize($_POST['alcohol_test']??'pending');

    // Spoilage inputs
    $smell       = sanitize($_POST['smell_check']??'normal');
    $visual      = sanitize($_POST['visual_check']??'normal');

    if(empty($farmerId)||$quantity<=0){
        echo json_encode(['success'=>false,'message'=>'Farmer and quantity are required']);exit;
    }

    // === QUALITY GRADE (no temperature) ===
    $grade=''; $status=''; $qIssues=''; $price=45.00;
    if($fat!==null && $protein!==null && $acidity!==null){
        $q = getQualityGrade([
            'fat'=>$fat,'protein'=>$protein,'ph'=>$acidity,
            'snf'=>$snf,'water'=>$water,
            'antibiotic'=>$antibiotic,'alcohol'=>$alcohol
        ]);
        $grade   = $q['grade'];
        $status  = $q['status'];
        $qIssues = implode('; ',$q['issues']);
        $price   = getPricePerLitre($grade);
    }
    $total = $quantity * $price;

    // === SPOILAGE RISK (temperature only) ===
    $spoilageRisk = 'low';
    if($temperature!==null){
        if($temperature > 10)     $spoilageRisk = 'critical';
        elseif($temperature > 8)  $spoilageRisk = 'high';
        elseif($temperature > 6)  $spoilageRisk = 'medium';
        else                      $spoilageRisk = 'low';
    }
    if($smell==='sour'||$smell==='bad')         $spoilageRisk = ($spoilageRisk==='low'||$spoilageRisk==='medium')?'high':$spoilageRisk;
    if($visual==='clotted')                     $spoilageRisk = 'critical';

    $deliveryId = generateDeliveryId();

    $db->insert("INSERT INTO milk_deliveries(
        delivery_id,farmer_id,recorded_by,delivery_date,delivery_time,session,
        quantity_litres,temperature,fat_content,protein_content,acidity,snf,
        water_content,antibiotic_test,alcohol_test,smell_check,visual_check,
        quality_grade,quality_status,quality_issues,spoilage_risk,
        storage_tank,price_per_litre,total_amount,notes
    ) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
    [$deliveryId,$farmerId,$recordedBy,$date,$time,$session,
     $quantity,$temperature,$fat,$protein,$acidity,$snf,
     $water,$antibiotic,$alcohol,$smell,$visual,
     $grade,$status,$qIssues,$spoilageRisk,
     $storageTank,$price,$total,$notes]);

    $db->update("UPDATE farmers SET total_deliveries=total_deliveries+1, total_litres=total_litres+?, total_earnings=total_earnings+? WHERE farmer_id=?",
        [$quantity,$total,$farmerId]);

    logActivity('staff',$recordedBy,'Record Delivery',"$deliveryId for $farmerId: {$quantity}L Grade:$grade Spoilage:$spoilageRisk");

    echo json_encode([
        'success'       => true,
        'delivery_id'   => $deliveryId,
        'grade'         => $grade,
        'spoilage_risk' => $spoilageRisk,
        'amount'        => $total,
        'issues'        => $q['issues']??[]
    ]);

} catch(Exception $e){
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}