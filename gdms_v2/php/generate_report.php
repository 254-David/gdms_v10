<?php
session_start();
require_once '../includes/config.php';
requireStaffLogin();
header('Content-Type: application/json');

$type     = sanitize($_GET['type']??'monthly');
$from     = sanitize($_GET['from']??date('Y-m-01'));
$to       = sanitize($_GET['to']??date('Y-m-d'));
$farmerId = sanitize($_GET['farmer']??'');

try {
    $db = Database::getInstance();

    // Build farmer filter
    $fWhere = $farmerId ? " AND d.farmer_id='$farmerId'" : '';

    // Summary stats
    $totalLitres = $db->fetchOne(
        "SELECT COALESCE(SUM(quantity_litres),0) as t FROM milk_deliveries d
         WHERE delivery_date BETWEEN ? AND ?$fWhere", [$from,$to])['t'];

    $gradeACount = $db->fetchOne(
        "SELECT COUNT(*) as c FROM milk_deliveries d
         WHERE quality_grade='A' AND delivery_date BETWEEN ? AND ?$fWhere", [$from,$to])['c'];

    $paidAmount = $db->fetchOne(
        "SELECT COALESCE(SUM(p.net_amount),0) as t FROM payments p
         WHERE payment_status='completed' AND payment_date BETWEEN ? AND ?".($farmerId?" AND p.farmer_id='$farmerId'":''),
        [$from.' 00:00:00',$to.' 23:59:59'])['t'];

    $activeFarmers = $db->fetchOne(
        "SELECT COUNT(DISTINCT farmer_id) as c FROM milk_deliveries d
         WHERE delivery_date BETWEEN ? AND ?$fWhere", [$from,$to])['c'];

    $rejectedCount = $db->fetchOne(
        "SELECT COUNT(*) as c FROM milk_deliveries d
         WHERE quality_grade='rejected' AND delivery_date BETWEEN ? AND ?$fWhere", [$from,$to])['c'];

    $gradeBCount = $db->fetchOne(
        "SELECT COUNT(*) as c FROM milk_deliveries d
         WHERE quality_grade='B' AND delivery_date BETWEEN ? AND ?$fWhere", [$from,$to])['c'];

    $gradeCCount = $db->fetchOne(
        "SELECT COUNT(*) as c FROM milk_deliveries d
         WHERE quality_grade='C' AND delivery_date BETWEEN ? AND ?$fWhere", [$from,$to])['c'];

    // Deliveries table
    $rows = $db->fetchAll(
        "SELECT d.delivery_id, f.full_name, d.delivery_date, d.session,
                d.quantity_litres, d.fat_content, d.acidity, d.temperature,
                d.quality_grade, d.total_amount, d.payment_status, d.spoilage_risk
         FROM milk_deliveries d
         JOIN farmers f ON d.farmer_id = f.farmer_id
         WHERE d.delivery_date BETWEEN ? AND ?$fWhere
         ORDER BY d.delivery_date DESC, d.delivery_time DESC",
        [$from,$to]
    );

    // Payment rows (for payment report type)
    $payments = $db->fetchAll(
        "SELECT p.payment_id, f.full_name, p.payment_period_start, p.payment_period_end,
                p.total_litres, p.base_amount, p.quality_bonus, p.deductions,
                p.net_amount, p.payment_method, p.payment_status, p.payment_date
         FROM payments p
         JOIN farmers f ON p.farmer_id = f.farmer_id
         WHERE p.payment_date BETWEEN ? AND ?".($farmerId?" AND p.farmer_id='$farmerId'":'')."
         ORDER BY p.payment_date DESC",
        [$from.' 00:00:00',$to.' 23:59:59']
    );

    echo json_encode([
        'success'       => true,
        'type'          => $type,
        'from'          => $from,
        'to'            => $to,
        'stats'         => [
            'total_litres'    => $totalLitres,
            'grade_a'         => $gradeACount,
            'grade_b'         => $gradeBCount,
            'grade_c'         => $gradeCCount,
            'rejected'        => $rejectedCount,
            'paid_amount'     => $paidAmount,
            'active_farmers'  => $activeFarmers,
        ],
        'rows'    => $rows,
        'payments'=> $payments,
    ]);

} catch(Exception $e) {
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}
