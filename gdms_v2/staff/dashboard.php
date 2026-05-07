<?php
session_start();
require_once '../includes/config.php';
requireStaffLogin();

// Prevent browser from caching the dashboard
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
$db = Database::getInstance();
$staffName = $_SESSION['staff_name'];
$staffRole = $_SESSION['staff_role'];

$totalFarmers  = $db->fetchOne("SELECT COUNT(*) as c FROM farmers WHERE status='active'")['c'];
$todayLitres   = $db->fetchOne("SELECT COALESCE(SUM(quantity_litres),0) as t FROM milk_deliveries WHERE delivery_date=CURDATE()")['t'];
$todayCount    = $db->fetchOne("SELECT COUNT(*) as c FROM milk_deliveries WHERE delivery_date=CURDATE()")['c'];
$pendingAmt    = $db->fetchOne("SELECT COALESCE(SUM(total_amount),0) as t FROM milk_deliveries WHERE payment_status='pending' AND (quality_grade IS NULL OR quality_grade!='rejected')")['t'];
$pendingCount  = $db->fetchOne("SELECT COUNT(DISTINCT farmer_id) as c FROM milk_deliveries WHERE payment_status='pending' AND (quality_grade IS NULL OR quality_grade!='rejected')")['c'];
$monthLitres   = $db->fetchOne("SELECT COALESCE(SUM(quantity_litres),0) as t FROM milk_deliveries WHERE MONTH(delivery_date)=MONTH(NOW()) AND YEAR(delivery_date)=YEAR(NOW())")['t'];
$spoilageAlerts= $db->fetchOne("SELECT COUNT(*) as c FROM milk_deliveries WHERE spoilage_risk IN('high','critical') AND delivery_date>=DATE_SUB(CURDATE(),INTERVAL 3 DAY)")['c'];
$completedPay  = $db->fetchOne("SELECT COALESCE(SUM(net_amount),0) as t FROM payments WHERE payment_status='completed' AND MONTH(payment_date)=MONTH(NOW())")['t'];
$unreadMsgs    = $db->fetchOne("SELECT COUNT(*) as c FROM contact_messages WHERE status='unread'")['c'];
$gradeACnt     = $db->fetchOne("SELECT COUNT(*) as c FROM milk_deliveries WHERE quality_grade='A' AND delivery_date>=DATE_SUB(CURDATE(),INTERVAL 30 DAY)")['c'];
$gradeBCnt     = $db->fetchOne("SELECT COUNT(*) as c FROM milk_deliveries WHERE quality_grade='B' AND delivery_date>=DATE_SUB(CURDATE(),INTERVAL 30 DAY)")['c'];
$gradeCCnt     = $db->fetchOne("SELECT COUNT(*) as c FROM milk_deliveries WHERE quality_grade='C' AND delivery_date>=DATE_SUB(CURDATE(),INTERVAL 30 DAY)")['c'];
$rejCnt        = $db->fetchOne("SELECT COUNT(*) as c FROM milk_deliveries WHERE quality_grade='rejected' AND delivery_date>=DATE_SUB(CURDATE(),INTERVAL 30 DAY)")['c'];
$recentDlv     = $db->fetchAll("SELECT d.*,f.full_name as fn FROM milk_deliveries d JOIN farmers f ON d.farmer_id=f.farmer_id ORDER BY d.created_at DESC LIMIT 8");
$farmers       = $db->fetchAll("SELECT * FROM farmers ORDER BY full_name");
$tanks         = $db->fetchAll("SELECT * FROM storage_tanks ORDER BY tank_id");
$allDlv        = $db->fetchAll("SELECT d.*,f.full_name as fn FROM milk_deliveries d JOIN farmers f ON d.farmer_id=f.farmer_id ORDER BY d.delivery_date DESC,d.delivery_time DESC LIMIT 200");
$allPay        = $db->fetchAll("SELECT p.*,f.full_name as fn FROM payments p JOIN farmers f ON p.farmer_id=f.farmer_id ORDER BY p.created_at DESC");
$staffList     = $db->fetchAll("SELECT * FROM staff ORDER BY role,full_name");
$announcements = $db->fetchAll("SELECT a.*,s.full_name as posted_by_name FROM announcements a LEFT JOIN staff s ON a.posted_by=s.staff_id ORDER BY a.created_at DESC");
$spoilItems    = $db->fetchAll("SELECT d.*,f.full_name as fn FROM milk_deliveries d JOIN farmers f ON d.farmer_id=f.farmer_id WHERE d.spoilage_risk IN('high','critical') ORDER BY d.created_at DESC LIMIT 20");
$chartData     = $db->fetchAll("SELECT DATE_FORMAT(delivery_date,'%d %b') as dl,SUM(quantity_litres) as lt FROM milk_deliveries WHERE delivery_date>=DATE_SUB(CURDATE(),INTERVAL 14 DAY) GROUP BY delivery_date ORDER BY delivery_date");
$prices        = $db->fetchAll("SELECT * FROM price_config WHERE status='active' ORDER BY grade");
$contactMsgs   = $db->fetchAll("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 50");
$actLogs       = $db->fetchAll("SELECT * FROM system_logs ORDER BY created_at DESC LIMIT 100");
$lowSpCnt      = $db->fetchOne("SELECT COUNT(*) as c FROM milk_deliveries WHERE spoilage_risk='low' AND delivery_date>=DATE_SUB(CURDATE(),INTERVAL 7 DAY)")['c'];
$medSpCnt      = $db->fetchOne("SELECT COUNT(*) as c FROM milk_deliveries WHERE spoilage_risk='medium' AND delivery_date>=DATE_SUB(CURDATE(),INTERVAL 7 DAY)")['c'];
$hiSpCnt       = $db->fetchOne("SELECT COUNT(*) as c FROM milk_deliveries WHERE spoilage_risk='high' AND delivery_date>=DATE_SUB(CURDATE(),INTERVAL 7 DAY)")['c'];
$critSpCnt     = $db->fetchOne("SELECT COUNT(*) as c FROM milk_deliveries WHERE spoilage_risk='critical' AND delivery_date>=DATE_SUB(CURDATE(),INTERVAL 7 DAY)")['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Staff Dashboard — GDMS</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<aside class="sidebar">
  <div class="sb-logo"><div class="ico">🥛</div><div><h2>GDMS</h2><p>Staff Portal</p></div></div>
  <div class="sb-user">
    <div class="sb-av"><?= strtoupper(substr($staffName,0,2)) ?></div>
    <h4><?= htmlspecialchars($staffName) ?></h4>
    <span class="role-tag"><?= ucfirst(str_replace('_',' ',$staffRole)) ?></span>
  </div>
  <div class="sb-scroll">
    <div class="nav-sec">Main</div>
    <button class="nav-i active" onclick="showPage('dashboard',this)"><i class="fas fa-th-large"></i> Dashboard</button>
    <button class="nav-i" onclick="showPage('deliveries',this)"><i class="fas fa-truck"></i> Milk Deliveries <span class="nav-b"><?= $todayCount ?></span></button>
    <button class="nav-i" onclick="showPage('quality',this)"><i class="fas fa-flask"></i> Quality Monitor</button>
    <button class="nav-i" onclick="showPage('spoilage',this)"><i class="fas fa-thermometer-half"></i> Spoilage Detection <?php if($spoilageAlerts>0):?><span class="nav-b red"><?= $spoilageAlerts ?></span><?php endif;?></button>
    <div class="nav-sec">Management</div>
    <button class="nav-i" onclick="showPage('farmers',this)"><i class="fas fa-users"></i> Farmers <span class="nav-b"><?= $totalFarmers ?></span></button>
    <button class="nav-i" onclick="showPage('payments',this)"><i class="fas fa-money-check-alt"></i> Payments <?php if($pendingCount>0):?><span class="nav-b"><?= $pendingCount ?></span><?php endif;?></button>
    <button class="nav-i" onclick="showPage('storage',this)"><i class="fas fa-database"></i> Storage Tanks</button>
    <div class="nav-sec">Analytics</div>
    <button class="nav-i" onclick="showPage('reports',this)"><i class="fas fa-chart-bar"></i> Reports</button>
    <button class="nav-i" onclick="showPage('ai-advisor',this)"><i class="fas fa-robot"></i> AI Advisor <span class="nav-b ai">AI</span></button>
    <div class="nav-sec">Admin</div>
    <button class="nav-i" onclick="showPage('announcements',this)"><i class="fas fa-bullhorn"></i> Announcements</button>
    <button class="nav-i" onclick="showPage('staff-mgmt',this)"><i class="fas fa-user-cog"></i> Staff Management</button>
    <button class="nav-i" onclick="showPage('messages',this)"><i class="fas fa-envelope"></i> Messages <?php if($unreadMsgs>0):?><span class="nav-b red"><?= $unreadMsgs ?></span><?php endif;?></button>
    <button class="nav-i" onclick="showPage('settings',this)"><i class="fas fa-cog"></i> Settings</button>
    <?php if($staffRole==='admin'):?>
    <button class="nav-i" onclick="showPage('logs',this)"><i class="fas fa-list-alt"></i> Activity Logs</button>
    <?php endif;?>
  </div>
  <div class="sb-ft"><a href="logout.php" class="nav-i" style="color:#f87171;"><i class="fas fa-sign-out-alt"></i> Logout</a></div>
</aside>

<div class="main">
<div class="topbar">
  <div class="tb-l"><div><div class="tb-title" id="tb-title">Dashboard Overview</div><div class="tb-sub"><?= date('l, d F Y') ?></div></div></div>
  <div class="tb-r">
    <button class="tb-btn" onclick="openModal('dlvModal')" title="Record Delivery"><i class="fas fa-plus"></i></button>
    <button class="tb-btn" onclick="showPage('messages',null)" title="Messages"><?php if($unreadMsgs>0):?><span class="nd"></span><?php endif;?><i class="fas fa-envelope"></i></button>
    <div class="tb-user">
      <div class="tu-av"><?= strtoupper(substr($staffName,0,2)) ?></div>
      <div><div class="tu-nm"><?= htmlspecialchars(explode(' ',$staffName)[0]) ?></div><div class="tu-rl"><?= ucfirst(str_replace('_',' ',$staffRole)) ?></div></div>
    </div>
  </div>
</div>
<div class="content">

<!-- DASHBOARD -->
<div class="pg active" id="pg-dashboard">
  <div class="sg">
    <div class="sc g"><div class="si g"><i class="fas fa-users"></i></div><div class="sv"><?= number_format($totalFarmers) ?></div><div class="sl">Active Farmers</div><div class="sch up"><i class="fas fa-check-circle"></i> Registered members</div></div>
    <div class="sc b"><div class="si b"><i class="fas fa-tint"></i></div><div class="sv"><?= number_format($todayLitres,1) ?>L</div><div class="sl">Today's Collection</div><div class="sch up"><i class="fas fa-truck"></i> <?= $todayCount ?> deliveries</div></div>
    <div class="sc o"><div class="si o"><i class="fas fa-coins"></i></div><div class="sv">KES <?= number_format($pendingAmt/1000,1) ?>K</div><div class="sl">Pending Payments</div><div class="sch down"><i class="fas fa-clock"></i> <?= $pendingCount ?> farmers waiting</div></div>
    <div class="sc p"><div class="si p"><i class="fas fa-chart-line"></i></div><div class="sv"><?= number_format($monthLitres,0) ?>L</div><div class="sl">This Month</div><div class="sch <?= $spoilageAlerts>0?'down':'up' ?>"><?= $spoilageAlerts>0?"<i class='fas fa-exclamation-triangle'></i> $spoilageAlerts spoilage alerts":"<i class='fas fa-check-circle'></i> No spoilage alerts" ?></div></div>
  </div>
  <div class="cg">
    <div class="cc"><div class="ch"><div><div class="ct">Milk Collection Trend</div><div class="cs">Daily litres — last 14 days</div></div></div><canvas id="colChart" height="110"></canvas></div>
    <div class="cc"><div class="ch"><div class="ct">Quality Distribution</div><div class="cs">Last 30 days</div></div><canvas id="qualChart" height="140"></canvas></div>
  </div>
  <div class="tc">
    <div class="th"><h3>Recent Deliveries</h3><div class="ta">
      <button class="btn btn-p" onclick="openModal('dlvModal')"><i class="fas fa-plus"></i> Record</button>
      <button class="btn btn-o" onclick="showPage('deliveries',null)">View All</button>
    </div></div>
    <div style="overflow-x:auto;"><table><thead><tr><th>Farmer</th><th>Date / Session</th><th>Qty</th><th>Fat%</th><th>pH</th><th>Grade</th><th>Spoilage</th><th>Amount</th><th>Payment</th></tr></thead><tbody>
    <?php foreach($recentDlv as $d):?>
    <tr>
      <td><div class="fc"><div class="fav"><?= strtoupper(substr($d['fn'],0,2)) ?></div><div><div class="fn"><?= htmlspecialchars($d['fn']) ?></div><div class="fi"><?= $d['farmer_id'] ?></div></div></div></td>
      <td><div><?= date('d M Y',strtotime($d['delivery_date'])) ?></div><div class="fi"><?= ucfirst($d['session']) ?> · <?= date('H:i',strtotime($d['delivery_time'])) ?></div></td>
      <td><strong><?= number_format($d['quantity_litres'],1) ?>L</strong></td>
      <td><?= $d['fat_content']!==null?$d['fat_content'].'%':'—' ?></td>
      <td><?= $d['acidity']??'—' ?></td>
      <td><?php if($d['quality_grade']):?><span class="bx bx-<?= strtolower($d['quality_grade']) ?>">Grade <?= $d['quality_grade'] ?></span><?php else:?>—<?php endif;?></td>
      <td><span class="bx bx-<?= $d['spoilage_risk']==='low'?'low':($d['spoilage_risk']==='medium'?'med':($d['spoilage_risk']==='critical'?'crit':'hi')) ?>"><?= ucfirst($d['spoilage_risk']) ?></span></td>
      <td>KES <?= number_format($d['total_amount'],2) ?></td>
      <td><span class="bx bx-<?= $d['payment_status']==='paid'?'paid':($d['payment_status']==='processing'?'proc':'pend') ?>"><?= ucfirst($d['payment_status']) ?></span></td>
    </tr>
    <?php endforeach;?>
    </tbody></table></div>
  </div>
</div>

<!-- DELIVERIES -->
<div class="pg" id="pg-deliveries">
  <div class="tc">
    <div class="th"><h3>All Milk Deliveries</h3><div class="ta">
      <input type="text" class="si-f" placeholder="Search..." oninput="filterTbl(this,'dlvTbl')">
      <button class="btn btn-p" onclick="openModal('dlvModal')"><i class="fas fa-plus"></i> Record Delivery</button>
      <button class="btn btn-o" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
    </div></div>
    <div style="overflow-x:auto;"><table id="dlvTbl"><thead><tr><th>Farmer</th><th>Date</th><th>Session</th><th>Qty</th><th>Temp</th><th>Fat%</th><th>Protein%</th><th>pH</th><th>SNF%</th><th>Water%</th><th>Antibiotic</th><th>Grade</th><th>Spoilage</th><th>Amount</th><th>Payment</th><th>AI</th></tr></thead><tbody>
    <?php foreach($allDlv as $d):?>
    <tr>
      <td><div class="fc"><div class="fav" style="width:28px;height:28px;font-size:11px;"><?= strtoupper(substr($d['fn'],0,2)) ?></div><div><div class="fn"><?= htmlspecialchars($d['fn']) ?></div><div class="fi"><?= $d['farmer_id'] ?></div></div></div></td>
      <td style="font-size:12px;"><?= date('d M Y',strtotime($d['delivery_date'])) ?></td>
      <td><span class="bx bx-<?= $d['session']==='morning'?'morn':'eve' ?>"><?= ucfirst($d['session']) ?></span></td>
      <td><strong><?= number_format($d['quantity_litres'],1) ?>L</strong></td>
      <td><?= $d['temperature']!==null?$d['temperature'].'°C':'—' ?></td>
      <td><?= $d['fat_content']!==null?$d['fat_content'].'%':'—' ?></td>
      <td><?= $d['protein_content']!==null?$d['protein_content'].'%':'—' ?></td>
      <td><?= $d['acidity']??'—' ?></td>
      <td><?= $d['snf']!==null?$d['snf'].'%':'—' ?></td>
      <td><?= $d['water_content']!==null?$d['water_content'].'%':'—' ?></td>
      <td><span class="bx" style="background:<?= $d['antibiotic_test']==='negative'?'rgba(16,217,126,0.12)':'rgba(239,68,68,0.12)' ?>;color:<?= $d['antibiotic_test']==='negative'?'#059669':'#dc2626' ?>"><?= ucfirst($d['antibiotic_test']) ?></span></td>
      <td><?php if($d['quality_grade']):?><span class="bx bx-<?= strtolower($d['quality_grade']) ?>">Grade <?= $d['quality_grade'] ?></span><?php else:?>—<?php endif;?></td>
      <td><span class="bx bx-<?= $d['spoilage_risk']==='low'?'low':($d['spoilage_risk']==='medium'?'med':($d['spoilage_risk']==='critical'?'crit':'hi')) ?>"><?= ucfirst($d['spoilage_risk']) ?></span></td>
      <td>KES <?= number_format($d['total_amount'],2) ?></td>
      <td><span class="bx bx-<?= $d['payment_status']==='paid'?'paid':($d['payment_status']==='processing'?'proc':'pend') ?>"><?= ucfirst($d['payment_status']) ?></span></td>
      <td><button class="btn btn-b" onclick="aiAnalyzeDelivery('<?= $d['delivery_id'] ?>','<?= htmlspecialchars(addslashes($d['fn'])) ?>')" title="AI"><i class="fas fa-robot"></i></button></td>
    </tr>
    <?php endforeach;?>
    </tbody></table></div>
  </div>
</div>

<!-- QUALITY MONITOR -->
<div class="pg" id="pg-quality">
 <div class="info-banner green"><i class="fas fa-info-circle" style="color:#00875a;font-size:16px;"></i>
    <span>Quality grade is based on <strong>fat content, protein, pH, SNF, water content & antibiotic test</strong></span>
  </div>
  <div class="sg">
    <div class="sc g"><div class="si g"><i class="fas fa-star"></i></div><div class="sv"><?= $gradeACnt ?></div><div class="sl">Grade A (30 days)</div><div class="sch up">Premium quality</div></div>
    <div class="sc b"><div class="si b"><i class="fas fa-thumbs-up"></i></div><div class="sv"><?= $gradeBCnt ?></div><div class="sl">Grade B (30 days)</div><div class="sch up">Good quality</div></div>
    <div class="sc o"><div class="si o"><i class="fas fa-exclamation"></i></div><div class="sv"><?= $gradeCCnt ?></div><div class="sl">Grade C (30 days)</div><div class="sch warn">Acceptable</div></div>
    <div class="sc r"><div class="si r"><i class="fas fa-times-circle"></i></div><div class="sv"><?= $rejCnt ?></div><div class="sl">Rejected (30 days)</div><div class="sch down">Below standard</div></div>
  </div>
  <div class="tc">
    <div class="th"><h3>Quality Records</h3><div class="ta"><input type="text" class="si-f" placeholder="Search..." oninput="filterTbl(this,'qualTbl')"></div></div>
    <div style="overflow-x:auto;"><table id="qualTbl"><thead><tr><th>Farmer</th><th>Date</th><th>Litres</th><th>Fat%</th><th>Protein%</th><th>pH</th><th>SNF%</th><th>Water%</th><th>Antibiotic</th><th>Grade</th><th>Issues</th></tr></thead><tbody>
    <?php foreach($allDlv as $d):?>
    <tr>
      <td><?= htmlspecialchars($d['fn']) ?></td>
      <td style="font-size:12px;"><?= date('d M Y',strtotime($d['delivery_date'])) ?></td>
      <td><?= number_format($d['quantity_litres'],1) ?>L</td>
      <td style="color:<?= $d['fat_content']>=3.5?'#059669':($d['fat_content']>=3.0?'#d97706':'#dc2626') ?>"><?= $d['fat_content']!==null?$d['fat_content'].'%':'—' ?></td>
      <td style="color:<?= $d['protein_content']>=3.0?'#059669':($d['protein_content']>=2.8?'#d97706':'#dc2626') ?>"><?= $d['protein_content']!==null?$d['protein_content'].'%':'—' ?></td>
      <td style="color:<?= ($d['acidity']>=6.6&&$d['acidity']<=6.8)?'#059669':(($d['acidity']>=6.4&&$d['acidity']<=7.0)?'#d97706':'#dc2626') ?>"><?= $d['acidity']??'—' ?></td>
      <td style="color:<?= $d['snf']>=8.5?'#059669':($d['snf']>=8.0?'#d97706':'#dc2626') ?>"><?= $d['snf']!==null?$d['snf'].'%':'—' ?></td>
      <td style="color:<?= $d['water_content']<=2?'#059669':($d['water_content']<=5?'#d97706':'#dc2626') ?>"><?= $d['water_content']!==null?$d['water_content'].'%':'—' ?></td>
      <td><span class="bx" style="background:<?= $d['antibiotic_test']==='negative'?'rgba(16,217,126,0.12)':'rgba(239,68,68,0.12)' ?>;color:<?= $d['antibiotic_test']==='negative'?'#059669':'#dc2626' ?>"><?= ucfirst($d['antibiotic_test']) ?></span></td>
      <td><?php if($d['quality_grade']):?><span class="bx bx-<?= strtolower($d['quality_grade']) ?>">Grade <?= $d['quality_grade'] ?></span><?php else:?>—<?php endif;?></td>
      <td style="font-size:12px;color:#1e293b;font-weight:500;max-width:200px;"><?= htmlspecialchars($d['quality_issues']??'') ?: '—' ?></td>
    </tr>
    <?php endforeach;?>
    </tbody></table></div>
  </div>
</div>

<!-- SPOILAGE DETECTION -->
<div class="pg" id="pg-spoilage">
  <div class="info-banner red"><i class="fas fa-thermometer-half" style="color:#f87171;font-size:16px;"></i>
    <span><strong>Spoilage detection uses temperature, smell & visual checks</strong> ≤6°C=Low · 6–8°C=Medium · 8–10°C=High · &gt;10°C=Critical</span>
  </div>
  <div class="sg">
    <div class="sc g"><div class="si g"><i class="fas fa-check-circle"></i></div><div class="sv"><?= $lowSpCnt ?></div><div class="sl">Low Risk (7 days)</div><div class="sch up">Temp ≤6°C — Safe</div></div>
    <div class="sc b"><div class="si b"><i class="fas fa-exclamation-circle"></i></div><div class="sv"><?= $medSpCnt ?></div><div class="sl">Medium Risk (7 days)</div><div class="sch warn">Temp 6–8°C — Monitor</div></div>
    <div class="sc o"><div class="si o"><i class="fas fa-exclamation-triangle"></i></div><div class="sv"><?= $hiSpCnt ?></div><div class="sl">High Risk (7 days)</div><div class="sch down">Temp 8–10°C — Act Now</div></div>
    <div class="sc r"><div class="si r"><i class="fas fa-fire"></i></div><div class="sv"><?= $critSpCnt ?></div><div class="sl">Critical (7 days)</div><div class="sch down">Temp &gt;10°C — Urgent!</div></div>
  </div>
  <?php if(!empty($spoilItems)):?>
  <div class="tc" style="border:2px solid rgba(239,68,68,0.35);margin-bottom:18px;">
    <div class="th" style="background:rgba(239,68,68,0.07);border-bottom:1px solid rgba(239,68,68,0.2);">
      <h3 style="color:#e70606;"><i class="fas fa-exclamation-triangle"></i> Active Spoilage Alerts — <?= count($spoilItems) ?> deliveries need attention</h3>
    </div>
    <div style="overflow-x:auto;"><table><thead><tr><th>Farmer</th><th>Date</th><th>Session</th><th>Qty</th><th>Temperature</th><th>Smell</th><th>Visual</th><th>Risk</th><th>Quality Grade</th><th>AI</th></tr></thead><tbody>
    <?php foreach($spoilItems as $s):?>
    <tr>
      <td><div class="fc"><div class="fav" style="width:28px;height:28px;font-size:11px;background:linear-gradient(135deg,#dc2626,#991b1b);"><?= strtoupper(substr($s['fn'],0,2)) ?></div><span class="fn"><?= htmlspecialchars($s['fn']) ?></span></div></td>
      <td style="font-size:12px;"><?= date('d M Y',strtotime($s['delivery_date'])) ?></td>
      <td><span class="bx bx-<?= $s['session']==='morning'?'morn':'eve' ?>"><?= ucfirst($s['session']) ?></span></td>
      <td><strong><?= $s['quantity_litres'] ?>L</strong></td>
      <td><strong style="font-size:15px;color:<?= $s['temperature']>10?'#dc2626':($s['temperature']>8?'#f97316':'#fbbf24') ?>;"><?= $s['temperature']!==null?$s['temperature'].'°C':'—' ?></strong></td>
      <td><?= ucfirst(str_replace('_',' ',$s['smell_check']??'normal')) ?></td>
      <td><?= ucfirst(str_replace('_',' ',$s['visual_check']??'normal')) ?></td>
      <td><span class="bx bx-<?= $s['spoilage_risk']==='critical'?'crit':'hi' ?>"><?= ucfirst($s['spoilage_risk']) ?></span></td>
      <td><?php if($s['quality_grade']):?><span class="bx bx-<?= strtolower($s['quality_grade']) ?>">Grade <?= $s['quality_grade'] ?></span><?php else:?><span style="color:#94a3b8;">—</span><?php endif;?></td>
      <td><button class="btn btn-b" onclick="aiAnalyzeDelivery('<?= $s['delivery_id'] ?>','<?= htmlspecialchars(addslashes($s['fn'])) ?>')"><i class="fas fa-robot"></i></button></td>
    </tr>
    <?php endforeach;?>
    </tbody></table></div>
  </div>
  <?php endif;?>
  <div class="tc">
    <div class="th"><h3>All Deliveries — Spoilage View</h3><div class="ta"><input type="text" class="si-f" placeholder="Search..." oninput="filterTbl(this,'spoilTbl')"></div></div>
    <div style="overflow-x:auto;"><table id="spoilTbl"><thead><tr><th>Farmer</th><th>Date</th><th>Session</th><th>Qty</th><th>Temperature</th><th>Smell</th><th>Visual</th><th>Spoilage Risk</th><th>Quality Grade</th></tr></thead><tbody>
    <?php foreach($allDlv as $d): $rc=['low'=>'#059669','medium'=>'#fbbf24','high'=>'#f97316','critical'=>'#dc2626'][$d['spoilage_risk']]??'#6b7280'; ?>
    <tr>
      <td><div class="fc"><div class="fav" style="width:28px;height:28px;font-size:11px;"><?= strtoupper(substr($d['fn'],0,2)) ?></div><span class="fn"><?= htmlspecialchars($d['fn']) ?></span></div></td>
      <td style="font-size:12px;"><?= date('d M Y',strtotime($d['delivery_date'])) ?></td>
      <td><span class="bx bx-<?= $d['session']==='morning'?'morn':'eve' ?>"><?= ucfirst($d['session']) ?></span></td>
      <td><?= number_format($d['quantity_litres'],1) ?>L</td>
      <td><?php if($d['temperature']!==null):?><strong style="color:<?= $rc ?>;"><?= $d['temperature'] ?>°C</strong><?php else:?>—<?php endif;?></td>
      <td><?= ucfirst(str_replace('_',' ',$d['smell_check']??'normal')) ?></td>
      <td><?= ucfirst(str_replace('_',' ',$d['visual_check']??'normal')) ?></td>
      <td><span class="bx bx-<?= $d['spoilage_risk']==='low'?'low':($d['spoilage_risk']==='medium'?'med':($d['spoilage_risk']==='critical'?'crit':'hi')) ?>"><?= ucfirst($d['spoilage_risk']) ?></span></td>
      <td><?php if($d['quality_grade']):?><span class="bx bx-<?= strtolower($d['quality_grade']) ?>">Grade <?= $d['quality_grade'] ?></span><?php else:?>—<?php endif;?></td>
    </tr>
    <?php endforeach;?>
    </tbody></table></div>
  </div>
</div>

<!-- FARMERS -->
<div class="pg" id="pg-farmers">
  <div class="tc">
    <div class="th"><h3>Registered Farmers</h3><div class="ta">
      <input type="text" class="si-f" placeholder="Search..." oninput="filterTbl(this,'frmTbl')">
      <button class="btn btn-p" onclick="openModal('farmerModal')"><i class="fas fa-user-plus"></i> Add Farmer</button>
    </div></div>
    <div style="overflow-x:auto;"><table id="frmTbl"><thead><tr><th>Farmer</th><th>Contact</th><th>Location</th><th>Deliveries</th><th>Total Litres</th><th>Earnings</th><th>Status</th><th>Actions</th></tr></thead><tbody>
    <?php foreach($farmers as $f):?>
    <tr>
      <td><div class="fc"><div class="fav"><?= strtoupper(substr($f['full_name'],0,2)) ?></div><div><div class="fn"><?= htmlspecialchars($f['full_name']) ?></div><div class="fi"><?= $f['farmer_id'] ?> · ID: <?= $f['id_number'] ?></div></div></div></td>
      <td><div><?= htmlspecialchars($f['phone']) ?></div><div class="fi"><?= htmlspecialchars($f['email']) ?></div></td>
      <td><?= htmlspecialchars($f['location']??'—') ?></td>
      <!--<td><?= $f['number_of_cows']??'—' ?></td>-->
      <!--<td style="font-size:12px;color:#64748b;"><?= htmlspecialchars($f['cow_breeds']??'—') ?></td>-->
      <td><?= number_format($f['total_deliveries']) ?></td>
      <td><?= number_format($f['total_litres'],1) ?>L</td>
      <td>KES <?= number_format($f['total_earnings'],2) ?></td>
      <td><span class="bx bx-<?= $f['status']==='active'?'act':'inact' ?>"><?= ucfirst($f['status']) ?></span></td>
      <td>
        <button class="btn btn-b" onclick="openDeliveryForFarmer('<?= $f['farmer_id'] ?>','<?= htmlspecialchars(addslashes($f['full_name'])) ?>')" title="Record Delivery"><i class="fas fa-plus"></i></button>
        <button class="btn btn-or" onclick="openPaymentForFarmer('<?= $f['farmer_id'] ?>')" title="Process Payment"><i class="fas fa-money-bill"></i></button>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody></table></div>
  </div>
</div>

<!-- PAYMENTS -->
<div class="pg" id="pg-payments">
  <div class="sg" style="grid-template-columns:repeat(3,1fr);">
    <div class="sc g"><div class="si g"><i class="fas fa-check-circle"></i></div><div class="sv">KES <?= number_format($completedPay/1000,1) ?>K</div><div class="sl">Paid This Month</div></div>
    <div class="sc o"><div class="si o"><i class="fas fa-clock"></i></div><div class="sv">KES <?= number_format($pendingAmt/1000,1) ?>K</div><div class="sl">Pending Payments</div></div>
    <div class="sc b"><div class="si b"><i class="fas fa-users"></i></div><div class="sv"><?= $pendingCount ?></div><div class="sl">Farmers Awaiting</div></div>
  </div>
  <div class="tc">
    <div class="th"><h3>Payment Records</h3><div class="ta">
      <input type="text" class="si-f" placeholder="Search..." oninput="filterTbl(this,'payTbl')">
      <button class="btn btn-p" onclick="openModal('payModal')"><i class="fas fa-plus"></i> Process Payment</button>
    </div></div>
    <div style="overflow-x:auto;"><table id="payTbl"><thead><tr><th>Payment ID</th><th>Farmer</th><th>Period</th><th>Litres</th><th>Base</th><th>Bonus</th><th>Deductions</th><th>Net Paid</th><th>Method</th><th>Status</th><th>Date</th><th>M-Pesa</th></tr></thead><tbody>
    <?php foreach($allPay as $p):?>
    <tr>
      <td style="font-size:11px;color:#94a3b8;"><?= $p['payment_id'] ?></td>
      <td><div class="fc"><div class="fav" style="width:28px;height:28px;font-size:11px;"><?= strtoupper(substr($p['fn'],0,2)) ?></div><div><span class="fn"><?= htmlspecialchars($p['fn']) ?></span><div class="fi"><?= $p['farmer_id'] ?></div></div></div></td>
      <td style="font-size:11px;"><?= date('d M',strtotime($p['payment_period_start'])) ?> — <?= date('d M Y',strtotime($p['payment_period_end'])) ?></td>
      <td><?= number_format($p['total_litres'],1) ?>L</td>
      <td>KES <?= number_format($p['base_amount'],2) ?></td>
      <td style="color:#f0a500;">+KES <?= number_format($p['quality_bonus'],2) ?></td>
      <td style="color:#f87171;">-KES <?= number_format($p['deductions'],2) ?></td>
      <td><strong style="color:#00875a;">KES <?= number_format($p['net_amount'],2) ?></strong></td>
      <td><span class="bx" style="background:#f1f5f9;color:#475569;"><?= ucfirst(str_replace('_',' ',$p['payment_method'])) ?></span></td>
      <td><span class="bx bx-<?= $p['payment_status']==='completed'?'paid':($p['payment_status']==='processing'?'proc':'pend') ?>"><?= ucfirst($p['payment_status']) ?></span></td>
      <td style="font-size:11px;"><?= $p['payment_date']?date('d M Y',strtotime($p['payment_date'])):'—' ?></td>
      <td><?php
        $f = $db->fetchOne("SELECT mpesa_number,full_name FROM farmers WHERE farmer_id=?",[$p['farmer_id']]);
        if($p['payment_method']==='mpesa' && $p['payment_status']==='completed'):?>
        <button class="btn btn-p" style="font-size:11px;padding:5px 10px;background:linear-gradient(135deg,#00875a,#006644);"
          onclick="sendMpesa('<?= $p['payment_id'] ?>','<?= $p['farmer_id'] ?>',<?= $p['net_amount'] ?>,'<?= htmlspecialchars($f['mpesa_number']??'') ?>','<?= htmlspecialchars(addslashes($f['full_name']??'')) ?>')">
          <i class="fas fa-mobile-alt"></i> Send
        </button>
        <?php else:?><span style="font-size:11px;color:#94a3b8;">—</span><?php endif;?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody></table></div>
  </div>
</div>

<!-- STORAGE TANKS -->
<div class="pg" id="pg-storage">
  <div class="tgrid">
    <?php foreach($tanks as $t):
      $pct=$t['capacity_litres']>0?($t['current_volume']/$t['capacity_litres'])*100:0;
      $col=$pct>90?'#dc2626':($pct>70?'#d97706':'#059669');
      $tCol=$t['temperature']>8?'#dc2626':($t['temperature']>6?'#fbbf24':'#059669');
    ?>
    <div class="tkc">
      <div class="tkh">
        <div><div class="tkn"><?= htmlspecialchars($t['tank_name']) ?></div><div class="fi"><?= htmlspecialchars($t['location']??'') ?></div></div>
        <span class="bx bx-<?= $t['status']==='active'?'act':'inact' ?>"><?= ucfirst($t['status']) ?></span>
      </div>
      <div class="fi">Current Volume</div>
      <div style="font-size:22px;font-weight:700;color:<?= $col ?>;margin:4px 0;"><?= number_format($t['current_volume'],0) ?>L <span style="font-size:13px;color:#94a3b8;">/ <?= number_format($t['capacity_litres'],0) ?>L</span></div>
      <div class="tkf"><div class="tkfb" style="width:<?= $pct ?>%;background:<?= $col ?>;"></div></div>
      <div class="tks"><span><?= number_format($pct,1) ?>% full</span><span><?= number_format($t['capacity_litres']-$t['current_volume'],0) ?>L free</span></div>
      <?php if($t['temperature']!==null):?>
      <div style="display:flex;align-items:center;gap:8px;margin-top:10px;padding:8px 12px;background:#f1f5f9;border-radius:8px;border:1px solid #e2e8f0;">
        <i class="fas fa-thermometer-half" style="color:<?= $tCol ?>;"></i>
        <span style="font-weight:700;color:<?= $tCol ?>;font-size:15px;"><?= $t['temperature'] ?>°C</span>
        <span style="font-size:11px;color:#94a3b8;"><?= $t['temperature']<=4?'✅ Excellent':($t['temperature']<=6?'✅ Optimal':($t['temperature']<=8?'⚠️ Monitor':'🚨 Too High')) ?></span>
      </div>
      <?php endif;?>
      <button class="btn btn-o" style="width:100%;margin-top:14px;" onclick="openTankModal('<?= $t['tank_id'] ?>','<?= htmlspecialchars(addslashes($t['tank_name'])) ?>',<?= $t['current_volume'] ?>,<?= $t['temperature']??0 ?>,'<?= $t['status'] ?>','<?= htmlspecialchars(addslashes($t['notes']??'')) ?>')"><i class="fas fa-edit"></i> Update Tank</button>
    </div>
    <?php endforeach;?>
  </div>
</div>

<!-- REPORTS -->
<div class="pg" id="pg-reports">
  <div class="rf">
    <div><label>Report Type</label><select id="rpt-type">
      <option value="deliveries">Delivery Report</option>
      <option value="quality">Quality Report</option>
      <option value="spoilage">Spoilage Report</option>
      <option value="payment">Payment Report</option>
    </select></div>
    <div><label>Date From</label><input type="date" id="rpt-from" value="<?= date('Y-m-01') ?>"></div>
    <div><label>Date To</label><input type="date" id="rpt-to" value="<?= date('Y-m-d') ?>"></div>
    <div><label>Farmer</label><select id="rpt-farmer"><option value="">All Farmers</option><?php foreach($farmers as $f):?><option value="<?= $f['farmer_id'] ?>"><?= htmlspecialchars($f['full_name']) ?></option><?php endforeach;?></select></div>
    <button class="btn btn-p" onclick="generateReport()" style="padding:9px 20px;align-self:flex-end;"><i class="fas fa-chart-bar"></i> Generate</button>
  </div>
  <div id="rpt-result" style="text-align:center;padding:60px;color:rgba(255, 255, 255, 0.3);">
    <i class="fas fa-chart-bar" style="font-size:40px;display:block;margin-bottom:12px;color:rgba(16,217,126,0.3);"></i>
    Select report type and date range, then click Generate
  </div>
</div>

<!-- AI ADVISOR -->
<div class="pg" id="pg-ai-advisor">
<style>
.ai-wrap{display:grid;grid-template-columns:1fr 1fr;gap:22px;align-items:start;}
.ai-input-panel{background:white;border-radius:18px;border:1px solid var(--border);overflow:hidden;}
.ai-panel-header{padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px;}
.ai-panel-header h3{font-size:15px;font-weight:700;color:#0f172a;}
.ai-cards-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;padding:18px;}
.ai-input-card{background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px 16px;transition:all .2s;}
.ai-input-card:hover{border-color:#00875a;box-shadow:0 0 0 3px rgba(5,150,105,0.08);}
.ai-input-card label{display:block;font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.8px;margin-bottom:6px;}
.ai-input-card .unit{font-size:10px;color:#94a3b8;margin-top:3px;}
.ai-input-card input,.ai-input-card select{width:100%;background:transparent;border:none;outline:none;font-size:20px;font-weight:700;color:#0f172a;font-family:inherit;padding:0;}
.ai-input-card input::placeholder{color:#cbd5e1;font-size:18px;}
.ai-input-card select{font-size:14px;cursor:pointer;color:#0f172a;}
.ai-input-card select option{background:#ffffff;color:#0f172a;}
.ai-analyze-btn{margin:0 18px 18px;width:calc(100% - 36px);padding:14px;background:linear-gradient(135deg,#059669,#047857);color:white;border:none;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;font-family:inherit;display:flex;align-items:center;justify-content:center;gap:10px;transition:all .3s;}
.ai-analyze-btn:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(5,150,105,0.3);}
.ai-analyze-btn:disabled{opacity:0.6;transform:none;}
.ai-result-panel{background:white;border-radius:18px;border:1px solid var(--border);overflow:hidden;}
.ai-summary-grid{display:grid;grid-template-columns:repeat(4,1fr);border-bottom:1px solid #e2e8f0;background:#f8fafc;}
.ai-summary-box{padding:18px 14px;text-align:center;border-right:1px solid #e5e7eb;}
.ai-summary-box:last-child{border-right:none;}
.ai-summary-box .label{font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px;}
.ai-summary-box .value{font-size:22px;font-weight:700;}
.ai-summary-box .sublabel{font-size:10px;color:#94a3b8;margin-top:4px;}
.ai-analysis-body{padding:20px;}
.ai-verdict-box{border-radius:12px;padding:14px 16px;margin-bottom:16px;}
.ai-verdict-box p{font-size:14px;font-weight:600;line-height:1.6;}
.ai-checklist{margin-bottom:16px;}
.ai-check-item{display:flex;align-items:flex-start;gap:10px;padding:7px 0;border-bottom:1px solid #f1f5f9;font-size:13px;color:#1e293b;line-height:1.5;}
.ai-check-item:last-child{border-bottom:none;}
.ai-check-icon{flex-shrink:0;margin-top:1px;}
.ai-section-title{font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.8px;margin:14px 0 8px;display:flex;align-items:center;gap:7px;}
.ai-empty{text-align:center;padding:60px 20px;color:#94a3b8;}
.ai-empty i{font-size:44px;display:block;margin-bottom:14px;color:rgba(139,92,246,0.3);}
</style>
<div class="ai-wrap">
  <div class="ai-input-panel">
    <div class="ai-panel-header">
      <i class="fas fa-robot" style="color:#8b5cf6;font-size:18px;"></i>
      <h3>AI Quality Advisor</h3>
    </div>
    <div class="ai-cards-grid">
      <div class="ai-input-card">
        <label>Farmer Name</label>
        <input id="ai-farmer" type="text" placeholder="Optional" style="font-size:14px;">
        <div class="unit">name</div>
      </div>
      <div class="ai-input-card">
        <label>Quantity</label>
        <input id="ai-qty" type="number" step="0.1" placeholder="0">
        <div class="unit">litres</div>
      </div>
      <div class="ai-input-card">
        <label>Temperature</label>
        <input id="ai-temp" type="number" step="0.1" placeholder="0">
        <div class="unit">°C · ideal ≤6°C</div>
      </div>
      <div class="ai-input-card">
        <label>Fat Content</label>
        <input id="ai-fat" type="number" step="0.1" placeholder="0">
        <div class="unit">% · ideal ≥3.5%</div>
      </div>
      <div class="ai-input-card">
        <label>Protein</label>
        <input id="ai-protein" type="number" step="0.1" placeholder="0">
        <div class="unit">% · ideal ≥3.0%</div>
      </div>
      <div class="ai-input-card">
        <label>pH / Acidity</label>
        <input id="ai-ph" type="number" step="0.01" placeholder="0">
        <div class="unit">pH · ideal 6.6–6.8</div>
      </div>
      <div class="ai-input-card">
        <label>SNF</label>
        <input id="ai-snf" type="number" step="0.1" placeholder="0">
        <div class="unit">% · ideal ≥8.5%</div>
      </div>
      <div class="ai-input-card">
        <label>Water Content</label>
        <input id="ai-water" type="number" step="0.1" placeholder="0">
        <div class="unit">% · ideal &lt;5%</div>
      </div>
      <div class="ai-input-card">
        <label>Hours Stored</label>
        <input id="ai-hours" type="number" step="0.5" placeholder="0">
        <div class="unit">hrs since milking</div>
      </div>
      <div class="ai-input-card" style="grid-column:span 2;">
        <label>Antibiotic Test</label>
        <select id="ai-antibiotic">
          <option value="negative">Negative</option>
          <option value="positive">Positive</option>
          <option value="pending">Pending</option>
        </select>
        <div class="unit">result</div>
      </div>
      <div class="ai-input-card">
        <label>Smell Check</label>
        <select id="ai-smell">
          <option value="normal">Normal</option>
          <option value="slightly_off">Slightly Off</option>
          <option value="sour">Sour</option>
          <option value="bad">Bad</option>
        </select>
        <div class="unit">sensory</div>
      </div>
    </div>
    <button class="ai-analyze-btn" id="ai-btn" onclick="runAI()">
      <i class="fas fa-robot"></i> Analyze with AI
    </button>
  </div>
  <div class="ai-result-panel">
    <div id="ai-result">
      <div class="ai-empty">
        <i class="fas fa-robot"></i>
        Enter milk parameters on the left<br>and click <strong style="color:#64748b;">Analyze with AI</strong>
      </div>
    </div>
  </div>
</div>
</div>

<!-- ANNOUNCEMENTS -->
<div class="pg" id="pg-announcements">
  <div class="tc">
    <div class="th"><h3>Announcements</h3><div class="ta"><button class="btn btn-p" onclick="openModal('annModal')"><i class="fas fa-plus"></i> New Announcement</button></div></div>
    <?php foreach($announcements as $a):?>
    <div class="pcard">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
        <div>
          <span style="font-size:15px;font-weight:700;color:#0f172a;"><?= htmlspecialchars($a['title']) ?></span>
          <div class="fi" style="margin-top:3px;"><?= date('d M Y H:i',strtotime($a['created_at'])) ?> · <?= htmlspecialchars($a['posted_by_name']??'Staff') ?></div>
        </div>
        <div style="display:flex;gap:8px;">
          <span class="bx" style="background:<?= ['urgent'=>'#fee2e2','high'=>'#fef3c7','normal'=>'#dbeafe','low'=>'#f1f5f9'][$a['priority']] ?>;color:<?= ['urgent'=>'#dc2626','high'=>'#d97706','normal'=>'#2563eb','low'=>'#94a3b8'][$a['priority']] ?>;"><?= ucfirst($a['priority']) ?></span>
          <span class="bx" style="background:#f1f5f9;color:#475569;"><?= ucfirst($a['target']) ?></span>
        </div>
      </div>
      <p style="font-size:14px;color:#374151;line-height:1.7;"><?= htmlspecialchars($a['content']) ?></p>
    </div>
    <?php endforeach;?>
  </div>
</div>

<!-- STAFF MANAGEMENT -->
<div class="pg" id="pg-staff-mgmt">
  <div class="tc">
    <div class="th"><h3>Staff Members</h3><div class="ta"><button class="btn btn-p" onclick="openModal('staffModal')"><i class="fas fa-user-plus"></i> Add Staff</button></div></div>
    <div style="overflow-x:auto;"><table><thead><tr><th>Staff</th><th>Contact</th><th>Role</th><th>Username</th><th>Last Login</th><th>Status</th></tr></thead><tbody>
    <?php foreach($staffList as $s):?>
    <tr>
      <td><div class="fc"><div class="fav" style="background:linear-gradient(135deg,#2563eb,#1d4ed8);"><?= strtoupper(substr($s['full_name'],0,2)) ?></div><div><div class="fn"><?= htmlspecialchars($s['full_name']) ?></div><div class="fi"><?= $s['staff_id'] ?></div></div></div></td>
      <td><div><?= htmlspecialchars($s['phone']??'—') ?></div><div class="fi"><?= htmlspecialchars($s['email']) ?></div></td>
      <td><span class="bx" style="background:rgba(139,92,246,0.12);color:#a78bfa;"><?= ucfirst(str_replace('_',' ',$s['role'])) ?></span></td>
      <td style="font-family:monospace;font-size:13px;color:rgba(255,255,255,0.6);"><?= htmlspecialchars($s['username']) ?></td>
      <td style="font-size:12px;color:#94a3b8;"><?= $s['last_login']?date('d M Y H:i',strtotime($s['last_login'])):'Never' ?></td>
      <td><span class="bx bx-<?= $s['status']==='active'?'act':'inact' ?>"><?= ucfirst($s['status']) ?></span></td>
    </tr>
    <?php endforeach;?>
    </tbody></table></div>
  </div>
</div>

<!-- MESSAGES -->
<div class="pg" id="pg-messages">
  <div class="tc">
    <div class="th"><h3><i class="fas fa-envelope" style="margin-right:8px;color:#3b82f6;"></i>Contact Messages</h3></div>
    <?php if(empty($contactMsgs)):?>
    <div style="text-align:center;padding:50px;color:#94a3b8;">No messages yet</div>
    <?php else: foreach($contactMsgs as $m):?>
    <div class="pcard" style="<?= $m['status']==='unread'?'border-left:3px solid #3b82f6;':'' ?>">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;">
        <div>
          <span style="font-weight:700;color:#0d1f2d;font-size:15px;"><?= htmlspecialchars($m['subject']) ?></span>
          <div class="fi" style="color:#4a6070;font-size:12px;margin-top:3px;"><?= htmlspecialchars($m['name']) ?> · <?= htmlspecialchars($m['email']) ?></div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
          <?php if($m['status']==='unread'):?><span class="bx" style="background:rgba(59,130,246,0.15);color:#3b82f6;">New</span><?php endif;?>
          <span style="font-size:11px;color:#94a3b8;"><?= date('d M Y H:i',strtotime($m['created_at'])) ?></span>
        </div>
      </div>
      <p style="font-size:14px;color:#374151;line-height:1.6;"><?= htmlspecialchars($m['message']) ?></p>
    </div>
    <?php endforeach; endif;?>
  </div>
</div>

<!-- SETTINGS -->
<div class="pg" id="pg-settings">
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
    <div class="tc">
      <div class="th"><h3><i class="fas fa-tag" style="color:#f0a500;margin-right:8px;"></i>Milk Price Configuration</h3></div>
      <div class="mc" style="padding:20px;">
        <form id="priceForm">
        <?php foreach(['A','B','C'] as $g):
          $pc=array_values(array_filter($prices,fn($p)=>$p['grade']===$g))[0]??['price_per_litre'=>['A'=>55,'B'=>45,'C'=>35][$g],'quality_bonus'=>['A'=>5,'B'=>0,'C'=>0][$g]];
        ?>
        <div style="background:#f8fafc;border-radius:12px;padding:16px;margin-bottom:12px;border:1px solid #e2e8f0;">
          <div style="font-weight:700;color:#0f172a;margin-bottom:12px;font-size:14px;"><span class="bx bx-<?= strtolower($g) ?>">Grade <?= $g ?></span></div>
          <div class="fg2">
            <div class="fg"><label>Price per Litre (KES)</label><input type="number" name="price_<?= $g ?>" step="0.5" value="<?= $pc['price_per_litre'] ?>"></div>
            <div class="fg"><label>Quality Bonus (KES)</label><input type="number" name="bonus_<?= $g ?>" step="0.5" value="<?= $pc['quality_bonus'] ?>"></div>
          </div>
        </div>
        <?php endforeach;?>
        <?php if($staffRole==='admin'):?>
        <button type="button" class="btn btn-p" onclick="submitPrices()" style="width:100%;padding:11px;font-size:14px;justify-content:center;"><i class="fas fa-save"></i> Save Price Changes</button>
        <?php else:?><p style="color:#94a3b8;font-size:13px;text-align:center;">Admin access required to change prices</p><?php endif;?>
        </form>
      </div>
    </div>
    <div class="tc">
      <div class="th"><h3><i class="fas fa-info-circle" style="color:#00875a;margin-right:8px;"></i>System Information</h3></div>
      <div class="mc" style="padding:20px;">
        <?php foreach([
          ['Cooperative','Githunguri DFCS'],['System Version','GDMS v2.0'],
          ['PHP Version',PHP_VERSION],['Total Farmers',$totalFarmers],
          ['Total Deliveries',$db->fetchOne("SELECT COUNT(*) as c FROM milk_deliveries")['c']],
          ['Payments Completed',$db->fetchOne("SELECT COUNT(*) as c FROM payments WHERE payment_status='completed'")['c']],
          ['Staff Members',$db->fetchOne("SELECT COUNT(*) as c FROM staff")['c']],
          ['Access Code',ACCESS_CODE],
        ] as $row):?>
        <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f1f5f9;font-size:13px;">
          <span style="color:#64748b;"><?= $row[0] ?></span>
          <span style="font-weight:600;color:#0f172a;"><?= $row[1] ?></span>
        </div>
        <?php endforeach;?>
      </div>
    </div>
  </div>
</div>

<!-- LOGS -->
<div class="pg" id="pg-logs">
  <div class="tc">
    <div class="th"><h3><i class="fas fa-list-alt" style="color:#8b5cf6;margin-right:8px;"></i>Activity Logs</h3><div class="ta"><input type="text" class="si-f" placeholder="Search..." oninput="filterTbl(this,'logTbl')"></div></div>
    <div style="overflow-x:auto;"><table id="logTbl"><thead><tr><th>Date/Time</th><th>User Type</th><th>User ID</th><th>Action</th><th>Details</th></tr></thead><tbody>
    <?php foreach($actLogs as $l):?>
    <tr>
      <td style="font-size:11px;color:#64748b;"><?= date('d M Y H:i',strtotime($l['created_at'])) ?></td>
      <td><span class="bx" style="background:<?= $l['user_type']==='staff'?'rgba(59,130,246,0.12)':'rgba(16,217,126,0.12)' ?>;color:<?= $l['user_type']==='staff'?'#2563eb':'#059669' ?>"><?= ucfirst($l['user_type']) ?></span></td>
      <td style="font-size:12px;color:rgba(255,255,255,0.6);"><?= htmlspecialchars($l['user_id']) ?></td>
      <td style="font-weight:600;font-size:13px;"><?= htmlspecialchars($l['action']) ?></td>
      <td style="font-size:12px;color:#64748b;max-width:300px;"><?= htmlspecialchars($l['details']??'—') ?></td>
    </tr>
    <?php endforeach;?>
    </tbody></table></div>
  </div>
</div>

</div></div>

<!-- RECORD DELIVERY MODAL -->
<div class="ov" id="dlvModal">
<div class="modal">
  <div class="mh"><h3><i class="fas fa-tint" style="color:#00875a;margin-right:8px;"></i>Record Milk Delivery</h3><button class="mx" onclick="closeModal('dlvModal')">✕</button></div>
  <div class="mc">
    <form id="dlvForm">
      <div class="fsec">📋 Delivery Information</div>
      <div class="fg3">
        <div class="fg"><label>Farmer *</label><select name="farmer_id" id="dlv-farmer" required><option value="">Select farmer...</option><?php foreach($farmers as $f):?><option value="<?= $f['farmer_id'] ?>"><?= htmlspecialchars($f['full_name']) ?></option><?php endforeach;?></select></div>
        <div class="fg"><label>Date *</label><input type="date" name="delivery_date" value="<?= date('Y-m-d') ?>" required></div>
        <div class="fg"><label>Time *</label><input type="time" name="delivery_time" value="<?= date('H:i') ?>" required></div>
        <div class="fg"><label>Session *</label><select name="session" required><option value="morning">Morning</option><option value="evening">Evening</option></select></div>
        <div class="fg"><label>Quantity (Litres) *</label><input type="number" name="quantity_litres" step="0.1" min="0.1" required placeholder="e.g. 25.5"></div>
        <div class="fg"><label>Storage Tank</label><select name="storage_tank"><option value="">Select tank...</option><?php foreach($tanks as $t):?><option value="<?= $t['tank_id'] ?>"><?= htmlspecialchars($t['tank_name']) ?></option><?php endforeach;?></select></div>
      </div>
      <div class="fsec">🧪 Milk Quality Parameters <span style="font-size:10px;font-weight:400;text-transform:none;letter-spacing:0;">(determines grade — temperature not included)</span></div>
      <div class="fg3">
        <div class="fg"><label>Fat Content (%)</label><input type="number" name="fat_content" step="0.01" placeholder="e.g. 3.8"><small>Ideal: ≥3.5%</small></div>
        <div class="fg"><label>Protein (%)</label><input type="number" name="protein_content" step="0.01" placeholder="e.g. 3.2"><small>Ideal: ≥3.0%</small></div>
        <div class="fg"><label>pH / Acidity</label><input type="number" name="acidity" step="0.01" placeholder="e.g. 6.7"><small>Ideal: 6.6–6.8</small></div>
        <div class="fg"><label>SNF (%)</label><input type="number" name="snf" step="0.01" placeholder="e.g. 8.8"><small>Ideal: ≥8.5%</small></div>
        <div class="fg"><label>Water Content (%)</label><input type="number" name="water_content" step="0.1" placeholder="0"><small>Ideal: &lt;5%</small></div>
        <div class="fg"><label>Antibiotic Test</label><select name="antibiotic_test"><option value="negative">Negative ✅</option><option value="positive">Positive ❌</option><option value="pending">Pending</option></select></div>
      </div>
      <div class="fsec red">🌡️ Spoilage Detection <span style="font-size:10px;font-weight:400;text-transform:none;letter-spacing:0;">(independent of quality grade)</span></div>
      <div class="fg3">
        <div class="fg"><label>Temperature (°C)</label><input type="number" name="temperature" step="0.1" placeholder="e.g. 4.5"><small>≤6°C=Safe · &gt;8°C=High risk</small></div>
        <div class="fg"><label>Smell Check</label><select name="smell_check"><option value="normal">Normal</option><option value="slightly_off">Slightly Off</option><option value="sour">Sour</option><option value="bad">Bad</option></select></div>
        <div class="fg"><label>Visual Check</label><select name="visual_check"><option value="normal">Normal</option><option value="slightly_off">Slightly Off</option><option value="clotted">Clotted</option><option value="watery">Watery</option><option value="yellow">Yellow</option></select></div>
      </div>
      <div class="fg"><label>Notes</label><textarea name="notes" rows="2" placeholder="Additional notes..."></textarea></div>
    </form>
  </div>
  <div class="mf"><button class="btn btn-o" onclick="closeModal('dlvModal')">Cancel</button><button class="btn btn-p" id="dlvBtn" onclick="submitDelivery()"><i class="fas fa-save"></i> Save Delivery</button></div>
</div></div>

<!-- FARMER MODAL -->
<div class="ov" id="farmerModal">
<div class="modal">
  <div class="mh"><h3><i class="fas fa-user-plus" style="color:#00875a;margin-right:8px;"></i>Add New Farmer</h3><button class="mx" onclick="closeModal('farmerModal')">✕</button></div>
  <div class="mc">
    <form id="farmerForm">
      <div class="fsec">Personal Information</div>
      <div class="fg2">
        <div class="fg"><label>Full Name *</label><input type="text" name="full_name" required placeholder="Full legal name"></div>
        <div class="fg"><label>National ID *</label><input type="text" name="id_number" required placeholder="ID number"></div>
        <div class="fg"><label>Phone Number *</label><input type="tel" name="phone" required placeholder="+254 7XX XXX XXX"></div>
        <div class="fg"><label>Email Address</label><input type="email" name="email" placeholder="farmer@email.com"></div>
      </div>
      <div class="fsec">Farm Details</div>
      <div class="fg2">
        <div class="fg"><label>Location</label><input type="text" name="location" placeholder="e.g. Githunguri Town"></div>
        <div class="fg"><label>Ward</label><input type="text" name="ward" placeholder="e.g. Githunguri"></div>
      </div>
      <p style="font-size:12px;color:#94a3b8;margin-top:8px;">Default password: <strong style="color:rgba(255,255,255,0.6);">password</strong></p>
    </form>
  </div>
  <div class="mf"><button class="btn btn-o" onclick="closeModal('farmerModal')">Cancel</button><button class="btn btn-p" id="farmerBtn" onclick="submitFarmer()"><i class="fas fa-user-plus"></i> Add Farmer</button></div>
</div></div>

<!-- PAYMENT MODAL -->
<div class="ov" id="payModal">
<div class="modal">
  <div class="mh"><h3><i class="fas fa-money-check-alt" style="color:#f0a500;margin-right:8px;"></i>Process Payment</h3><button class="mx" onclick="closeModal('payModal')">✕</button></div>
  <div class="mc">
    <form id="payForm">
      <div class="fg2">
        <div class="fg"><label>Farmer *</label><select name="farmer_id" required onchange="payPreview(this.value)"><option value="">Select farmer...</option><?php foreach($farmers as $f):?><option value="<?= $f['farmer_id'] ?>"><?= htmlspecialchars($f['full_name']) ?></option><?php endforeach;?></select></div>
        <div class="fg"><label>Payment Method *</label><select name="payment_method" required><option value="mpesa">M-Pesa</option><option value="bank_transfer">Bank Transfer</option><option value="cash">Cash</option><option value="cheque">Cheque</option></select></div>
        <div class="fg"><label>Period Start *</label><input type="date" name="period_start" id="pay-start" value="<?= date('Y-m-01') ?>" required onchange="payPreview(document.querySelector('[name=farmer_id]').value)"></div>
        <div class="fg"><label>Period End *</label><input type="date" name="period_end" id="pay-end" value="<?= date('Y-m-d') ?>" required onchange="payPreview(document.querySelector('[name=farmer_id]').value)"></div>
      </div>
      <div id="pay-preview" style="background:#f8fafc;border-radius:11px;padding:14px;margin:10px 0;display:none;border:1px solid #e2e8f0;"></div>
      <div class="fg2">
        <div class="fg"><label>Deductions (KES)</label><input type="number" name="deductions" value="0" step="0.01" min="0"></div>
        <div class="fg"><label>Deduction Reason</label><input type="text" name="deduction_reason" placeholder="e.g. Advance repayment"></div>
      </div>
      <div class="fg"><label>Notes</label><input type="text" name="notes" placeholder="Payment notes..."></div>
    </form>
  </div>
  <div class="mf"><button class="btn btn-o" onclick="closeModal('payModal')">Cancel</button><button class="btn btn-p" id="payBtn" onclick="submitPayment()"><i class="fas fa-check"></i> Process Payment</button></div>
</div></div>

<!-- TANK MODAL -->
<div class="ov" id="tankModal">
<div class="modal">
  <div class="mh"><h3><i class="fas fa-database" style="color:#00875a;margin-right:8px;"></i>Update Tank — <span id="tank-name-disp"></span></h3><button class="mx" onclick="closeModal('tankModal')">✕</button></div>
  <div class="mc">
    <form id="tankForm">
      <input type="hidden" name="tank_id" id="tank-id">
      <div class="fg2">
        <div class="fg"><label>Current Volume (Litres)</label><input type="number" name="current_volume" id="tank-vol" step="0.1" min="0"></div>
        <div class="fg"><label>Temperature (°C)</label><input type="number" name="temperature" id="tank-temp" step="0.1"><small>≤4°C=Excellent · ≤6°C=Optimal · &gt;8°C=High Risk</small></div>
        <div class="fg"><label>Status</label><select name="status" id="tank-status"><option value="active">Active</option><option value="full">Full</option><option value="empty">Empty</option><option value="maintenance">Maintenance</option></select></div>
      </div>
      <div class="fg"><label>Notes</label><textarea name="notes" id="tank-notes" rows="2" placeholder="Tank notes..."></textarea></div>
    </form>
  </div>
  <div class="mf"><button class="btn btn-o" onclick="closeModal('tankModal')">Cancel</button><button class="btn btn-p" onclick="submitTank()"><i class="fas fa-save"></i> Save</button></div>
</div></div>

<!-- ANNOUNCEMENT MODAL -->
<div class="ov" id="annModal">
<div class="modal">
  <div class="mh"><h3><i class="fas fa-bullhorn" style="color:#f0a500;margin-right:8px;"></i>New Announcement</h3><button class="mx" onclick="closeModal('annModal')">✕</button></div>
  <div class="mc">
    <form id="annForm">
      <div class="fg"><label>Title *</label><input type="text" name="title" required placeholder="Announcement title"></div>
      <div class="fg"><label>Message *</label><textarea name="content" required rows="4" placeholder="Write your announcement here..."></textarea></div>
      <div class="fg3">
        <div class="fg"><label>Target Audience</label><select name="target"><option value="all">Everyone</option><option value="farmers">Farmers Only</option><option value="staff">Staff Only</option></select></div>
        <div class="fg"><label>Priority</label><select name="priority"><option value="normal">Normal</option><option value="high">High</option><option value="urgent">Urgent</option><option value="low">Low</option></select></div>
        <div class="fg"><label>Expires On (optional)</label><input type="date" name="expires_at"></div>
      </div>
    </form>
  </div>
  <div class="mf"><button class="btn btn-o" onclick="closeModal('annModal')">Cancel</button><button class="btn btn-p" onclick="submitAnnouncement()"><i class="fas fa-paper-plane"></i> Post</button></div>
</div></div>

<!-- STAFF MODAL -->
<div class="ov" id="staffModal">
<div class="modal">
  <div class="mh"><h3>Add Staff Member</h3><button class="mx" onclick="closeModal('staffModal')">✕</button></div>
  <div class="mc">
    <form id="staffForm">
      <div class="fg2">
        <div class="fg"><label>Full Name *</label><input type="text" name="full_name" required></div>
        <div class="fg"><label>Email *</label><input type="email" name="email" required></div>
        <div class="fg"><label>Phone</label><input type="tel" name="phone"></div>
        <div class="fg"><label>Username *</label><input type="text" name="username" required></div>
        <div class="fg"><label>Role</label><select name="role"><option value="data_entry">Data Entry</option><option value="quality_inspector">Quality Inspector</option><option value="accountant">Accountant</option><option value="manager">Manager</option><?php if($staffRole==='admin'):?><option value="admin">Admin</option><?php endif;?></select></div>
        <div class="fg"><label>Access Code</label><input type="text" name="access_code" value="<?= ACCESS_CODE ?>"></div>
      </div>
      <p style="font-size:12px;color:#94a3b8;">Default password: <strong style="color:rgba(255,255,255,0.6);">password</strong></p>
    </form>
  </div>
  <div class="mf"><button class="btn btn-o" onclick="closeModal('staffModal')">Cancel</button><button class="btn btn-p" onclick="submitStaff()"><i class="fas fa-user-plus"></i> Add Staff</button></div>
</div></div>

<!-- M-PESA MODAL -->
<div class="ov" id="mpesaModal">
<div class="modal" style="max-width:460px;">
  <div class="mh"><h3><i class="fas fa-mobile-alt" style="color:#00875a;margin-right:8px;"></i>Send M-Pesa Payment</h3><button class="mx" onclick="closeModal('mpesaModal')">✕</button></div>
  <div class="mc" style="padding:24px;">
    <div id="mpesa-details" style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:16px;margin-bottom:20px;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;text-align:center;">
        <div><div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Farmer</div><div id="mpesa-farmer" style="font-weight:700;color:#0f172a;font-size:14px;"></div></div>
        <div><div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Amount</div><div id="mpesa-amount-disp" style="font-weight:700;color:#00875a;font-size:18px;"></div></div>
      </div>
    </div>
    <div class="fg">
      <label>M-Pesa Phone Number *</label>
      <input type="tel" id="mpesa-phone" placeholder="e.g. 0712345678" style="font-size:16px;font-weight:600;">
      <small>Must be the farmer's registered M-Pesa number</small>
    </div>
    <input type="hidden" id="mpesa-pid">
    <input type="hidden" id="mpesa-fid">
    <input type="hidden" id="mpesa-amt">
    <div id="mpesa-result" style="display:none;padding:12px 14px;border-radius:10px;font-size:13px;margin-top:12px;"></div>
  </div>
  <div class="mf">
    <button class="btn btn-o" onclick="closeModal('mpesaModal')">Cancel</button>
    <button class="btn btn-p" id="mpesa-btn" onclick="confirmMpesa()" style="background:linear-gradient(135deg,#00875a,#006644);">
      <i class="fas fa-paper-plane"></i> Send KES <span id="mpesa-btn-amt"></span>
    </button>
  </div>
</div></div>

<script src="../js/dashboard.js"></script>
<script>
const cData = <?= json_encode(array_values($chartData)) ?>;
const qData = {A:<?= $gradeACnt ?>,B:<?= $gradeBCnt ?>,C:<?= $gradeCCnt ?>,R:<?= $rejCnt ?>};
if(document.getElementById('colChart')){
  new Chart(document.getElementById('colChart'),{type:'line',data:{labels:cData.map(d=>d.dl),datasets:[{label:'Litres',data:cData.map(d=>parseFloat(d.lt)),borderColor:'#00875a',backgroundColor:'rgba(0,135,90,0.10)',tension:0.4,fill:true,pointBackgroundColor:'#00875a',pointBorderColor:'#f8f2f2ff',pointBorderWidth:2,pointRadius:5,pointRadius:4}]},options:{plugins:{legend:{display:false}},scales:{x:{grid:{color:'#f1f5f9ff'},ticks:{color:'#f6f9fcff',font:{size:11}}},y:{grid:{color:'#f9f4f1ff'},ticks:{color:'#94a3b8',font:{size:11}}}}}});
}
if(document.getElementById('qualChart')){
  new Chart(document.getElementById('qualChart'),{type:'doughnut',data:{labels:['Grade A','Grade B','Grade C','Rejected'],datasets:[{data:[qData.A,qData.B,qData.C,qData.R],backgroundColor:['#00875a','#2563eb','#f59e0b','#dc2626'],borderColor:'rgba(0,0,0,0)',borderWidth:3,borderColor:'#ffffff'}]},options:{plugins:{legend:{labels:{color:'#09703dff',font:{size:11}}}},cutout:'65%'}});
}
function openPaymentForFarmer(id){document.querySelector('[name=farmer_id]').value=id;openModal('payModal');payPreview(id);}
</script>
</body>
</html>
