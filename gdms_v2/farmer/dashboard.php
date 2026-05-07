<?php
session_start();
require_once '../includes/config.php';

// Check if farmer is logged in
if(!isset($_SESSION['farmer_id']) || !isset($_SESSION['farmer_logged_in']) || $_SESSION['farmer_logged_in'] !== true){
    header('Location: login.php');
    exit;
}

$db = Database::getInstance();
$farmerId = $_SESSION['farmer_id'];
$farmerName = $_SESSION['farmer_name'];

// Get farmer details
$farmer = $db->fetchOne("SELECT * FROM farmers WHERE farmer_id = ?", [$farmerId]);

// Get farmer statistics
$stats = $db->fetchOne(
    "SELECT 
        COUNT(*) as total_deliveries,
        COALESCE(SUM(quantity_litres), 0) as total_litres,
        COALESCE(AVG(fat_content), 0) as avg_fat,
        COALESCE(AVG(protein_content), 0) as avg_protein,
        COALESCE(SUM(CASE WHEN payment_status = 'paid' THEN total_amount ELSE 0 END), 0) as total_paid,
        COALESCE(SUM(CASE WHEN payment_status = 'pending' THEN total_amount ELSE 0 END), 0) as total_pending,
        MAX(delivery_date) as last_delivery
     FROM milk_deliveries 
     WHERE farmer_id = ?",
    [$farmerId]
);

// Get recent deliveries
$recentDeliveries = $db->fetchAll(
    "SELECT * FROM milk_deliveries 
     WHERE farmer_id = ? 
     ORDER BY delivery_date DESC, delivery_time DESC 
     LIMIT 10",
    [$farmerId]
);

// Get monthly stats for chart
$monthlyData = $db->fetchAll(
    "SELECT 
        DATE_FORMAT(delivery_date, '%b') as month,
        SUM(quantity_litres) as litres,
        COUNT(*) as deliveries
     FROM milk_deliveries 
     WHERE farmer_id = ? 
        AND delivery_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
     GROUP BY YEAR(delivery_date), MONTH(delivery_date)
     ORDER BY delivery_date DESC
     LIMIT 6",
    [$farmerId]
);
$monthlyData = array_reverse($monthlyData);

// Get quality distribution
$qualityStats = $db->fetchAll(
    "SELECT 
        quality_grade,
        COUNT(*) as count,
        COALESCE(AVG(fat_content), 0) as avg_fat,
        COALESCE(AVG(protein_content), 0) as avg_protein
     FROM milk_deliveries 
     WHERE farmer_id = ? AND quality_grade IS NOT NULL
     GROUP BY quality_grade",
    [$farmerId]
);

$qualityData = ['A' => 0, 'B' => 0, 'C' => 0, 'rejected' => 0];
foreach($qualityStats as $q) {
    $qualityData[$q['quality_grade']] = $q['count'];
}

// Get recent payments
$recentPayments = $db->fetchAll(
    "SELECT * FROM payments 
     WHERE farmer_id = ? 
     ORDER BY created_at DESC 
     LIMIT 5",
    [$farmerId]
);

// Get announcements for farmers
$announcements = $db->fetchAll(
    "SELECT * FROM announcements 
     WHERE (target = 'all' OR target = 'farmers') 
        AND (expires_at IS NULL OR expires_at >= CURDATE())
     ORDER BY created_at DESC 
     LIMIT 5"
);

// Get price configuration
$prices = $db->fetchAll("SELECT * FROM price_config WHERE status='active' ORDER BY grade");
$priceMap = [];
foreach($prices as $p) {
    $priceMap[$p['grade']] = $p;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard — GDMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        :root{
            --bg:#f0f4f8; --card:#ffffff; --border:#e2eaed;
            --text:#0d1f2d; --muted:#4a6070;
            --green:#00875a; --green2:#006644; --green3:#d1fae5;
            --amber:#f59e0b;
        }

        body{ font-family:'DM Sans',sans-serif; background:var(--bg); min-height:100vh; color:var(--text); }

        /* NAVBAR */
        .navbar{ background:linear-gradient(135deg,#0a1628 0%,#0d2d1e 100%); border-bottom:1px solid rgba(255,255,255,0.07); padding:14px 32px; display:flex; justify-content:space-between; align-items:center; position:sticky; top:0; z-index:100; box-shadow:0 2px 20px rgba(0,0,0,0.2); }
        .logo{ display:flex; align-items:center; gap:12px; text-decoration:none; }
        .logo-icon{ width:44px; height:44px; background:#f0a500; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; box-shadow:0 3px 10px rgba(240,165,0,0.4); }
        .logo h2{ font-family:'Playfair Display',serif; font-size:17px; color:#fff; }
        .logo p{ font-size:10px; color:rgba(255,255,255,0.45); }
        .user-menu{ display:flex; align-items:center; gap:18px; }
        .user-info{ text-align:right; }
        .user-name{ font-weight:700; font-size:14px; color:#fff; }
        .user-badge{ font-size:11px; color:#fbbf24; font-weight:500; margin-top:1px; }
        .logout-btn{ background:rgba(220,38,38,0.12); color:#fca5a5; border:1px solid rgba(220,38,38,0.25); padding:9px 18px; border-radius:9px; text-decoration:none; font-size:13px; font-weight:600; transition:all .2s; display:flex; align-items:center; gap:7px; }
        .logout-btn:hover{ background:rgba(239,68,68,0.25); transform:translateY(-1px); }

        /* LAYOUT */
        .container{ max-width:1200px; margin:0 auto; padding:32px 28px; }

        /* WELCOME */
        .welcome{ margin-bottom:28px; }
        .welcome h1{ font-family:'Playfair Display',serif; font-size:28px; margin-bottom:5px; color:var(--text); }
        .welcome h1 span{ color:var(--amber); }
        .welcome p{ color:var(--muted); font-size:14px; }

        /* STAT CARDS */
        .stats-grid{ display:grid; grid-template-columns:repeat(4,1fr); gap:18px; margin-bottom:24px; }
        .stat-card{ background:#fff; border:1px solid var(--border); border-radius:18px; padding:22px; box-shadow:0 2px 14px rgba(0,0,0,0.06); transition:all .25s; position:relative; overflow:hidden; }
        .stat-card::after{ content:""; position:absolute; bottom:0; left:0; right:0; height:3px; background:linear-gradient(90deg,#00875a,#00d68f); transform:scaleX(0); transform-origin:left; transition:transform .3s; }
        .stat-card:hover{ transform:translateY(-4px); box-shadow:0 10px 30px rgba(0,0,0,0.10); border-color:#a7f3d0; }
        .stat-card:hover::after{ transform:scaleX(1); }
        .stat-icon{ width:48px; height:48px; border-radius:13px; display:flex; align-items:center; justify-content:center; margin-bottom:16px; font-size:20px; }
        .stat-icon.green{ background:linear-gradient(135deg,#d1fae5,#a7f3d0); color:#065f46; }
        .stat-icon.blue{ background:linear-gradient(135deg,#dbeafe,#bfdbfe); color:#1e40af; }
        .stat-icon.orange{ background:linear-gradient(135deg,#fef3c7,#fde68a); color:#92400e; }
        .stat-icon.purple{ background:linear-gradient(135deg,#ede9fe,#ddd6fe); color:#5b21b6; }
        .stat-value{ font-family:'Playfair Display',serif; font-size:26px; font-weight:700; color:var(--text); margin-bottom:4px; }
        .stat-label{ color:var(--muted); font-size:11px; text-transform:uppercase; letter-spacing:.8px; font-weight:700; }
        .stat-sub{ font-size:12px; color:#7a93a6; margin-top:7px; }

        /* SECTION CARD */
        .section-card{ background:#fff; border:1px solid var(--border); border-radius:18px; padding:24px; margin-bottom:22px; box-shadow:0 2px 14px rgba(0,0,0,0.06); }
        .section-title{ font-weight:700; font-size:16px; color:var(--text); margin-bottom:18px; display:flex; align-items:center; gap:8px; }
        .section-title i{ color:var(--green); font-size:14px; }

        /* PRICE CARDS */
        .price-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:14px; }
        .price-card{ border-radius:14px; padding:22px; text-align:center; transition:all .25s; }
        .price-card.grade-a{ background:linear-gradient(145deg,#ecfdf5,#d1fae5); border:1.5px solid #6ee7b7; }
        .price-card.grade-b{ background:linear-gradient(145deg,#eff6ff,#dbeafe); border:1.5px solid #93c5fd; }
        .price-card.grade-c{ background:linear-gradient(145deg,#fffbeb,#fef3c7); border:1.5px solid #fcd34d; }
        .price-card:hover{ transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,0.10); }
        .price-grade{ font-family:'Playfair Display',serif; font-size:22px; font-weight:700; margin-bottom:8px; }
        .price-card.grade-a .price-grade{ color:#065f46; }
        .price-card.grade-b .price-grade{ color:#1e40af; }
        .price-card.grade-c .price-grade{ color:#92400e; }
        .price-value{ font-size:22px; font-weight:700; color:var(--text); margin-bottom:3px; }
        .price-label{ font-size:11px; color:var(--muted); font-weight:600; text-transform:uppercase; letter-spacing:.5px; }
        .price-bonus{ font-size:11px; color:#b45309; margin-top:6px; font-weight:700; background:rgba(245,158,11,0.15); padding:3px 10px; border-radius:50px; display:inline-block; }

        /* CHARTS */
        .charts-row{ display:grid; grid-template-columns:1fr 1fr; gap:18px; margin-bottom:22px; }
        .chart-card{ background:#fff; border:1px solid var(--border); border-radius:18px; padding:22px; height:290px; box-shadow:0 2px 14px rgba(0,0,0,0.06); }
        .chart-header{ margin-bottom:16px; }
        .chart-title{ font-weight:700; font-size:15px; color:var(--text); margin-bottom:3px; }
        .chart-sub{ color:#7a93a6; font-size:12px; }

        /* TABLES */
        .table-card{ background:#fff; border:1px solid var(--border); border-radius:18px; padding:24px; margin-bottom:22px; box-shadow:0 2px 14px rgba(0,0,0,0.06); }
        .table-header{ display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; }
        .table-title{ font-weight:700; font-size:16px; color:var(--text); display:flex; align-items:center; gap:8px; }
        .table-title i{ color:var(--green); font-size:14px; }
        .view-all{ color:var(--green); text-decoration:none; font-size:13px; font-weight:700; display:flex; align-items:center; gap:5px; background:var(--green3); padding:7px 16px; border-radius:8px; transition:all .2s; }
        .view-all:hover{ background:var(--green); color:white; }
        table{ width:100%; border-collapse:collapse; }
        th{ text-align:left; padding:11px 12px; color:var(--muted); font-weight:700; font-size:11px; text-transform:uppercase; letter-spacing:.6px; border-bottom:2px solid #f0f4f8; background:#f8fafc; }
        th:first-child{ border-radius:8px 0 0 8px; } th:last-child{ border-radius:0 8px 8px 0; }
        td{ padding:13px 12px; border-bottom:1px solid #f0f4f8; font-size:14px; color:var(--text); }
        tr:last-child td{ border-bottom:none; }
        tr:hover td{ background:#fafcff; }
        .badge{ padding:4px 11px; border-radius:20px; font-size:11px; font-weight:700; display:inline-block; }
        .badge.grade-a{ background:#d1fae5; color:#065f46; }
        .badge.grade-b{ background:#dbeafe; color:#1e40af; }
        .badge.grade-c{ background:#fef3c7; color:#92400e; }
        .badge.rejected{ background:#fee2e2; color:#991b1b; }
        .badge.paid{ background:#d1fae5; color:#065f46; }
        .badge.pending{ background:#fef3c7; color:#92400e; }
        .badge.processing{ background:#dbeafe; color:#1e40af; }
        .badge.session{ background:#f1f5f9; color:#475569; }
        .amount{ font-weight:700; color:var(--green); }

        /* EMPTY STATE */
        .empty-state{ text-align:center; padding:48px 20px; }
        .empty-state i{ font-size:36px; color:#cbd5e1; margin-bottom:12px; display:block; }
        .empty-state p{ color:var(--muted); font-size:14px; }

        /* ANNOUNCEMENTS */
        .announcement-card{ background:#f8fafc; border:1px solid var(--border); border-radius:12px; padding:18px; margin-bottom:12px; transition:all .2s; }
        .announcement-card:hover{ background:#fff; box-shadow:0 4px 14px rgba(0,0,0,0.07); }
        .announcement-title{ font-weight:700; font-size:15px; margin-bottom:5px; color:var(--text); }
        .announcement-meta{ font-size:11px; color:var(--muted); margin-bottom:8px; display:flex; align-items:center; gap:6px; }
        .announcement-content{ font-size:13px; color:var(--muted); line-height:1.65; }
        .priority-urgent{ border-left:3px solid #f87171; }
        .priority-high{ border-left:3px solid #f59e0b; }
        .priority-normal{ border-left:3px solid #3b82f6; }
        .priority-low{ border-left:3px solid #e2e8f0; }

        @media(max-width:768px){
            .stats-grid{ grid-template-columns:repeat(2,1fr); }
            .charts-row{ grid-template-columns:1fr; }
            .price-grid{ grid-template-columns:1fr; }
            .container{ padding:20px 16px; }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="dashboard.php" class="logo">
            <div class="logo-icon">🥛</div>
            <div>
                <h2>GDMS</h2>
                <p>Githunguri Dairy</p>
            </div>
        </a>
        
        <div class="user-menu">
            <div class="user-info">
                <div class="user-name"><?= htmlspecialchars($farmerName) ?></div>
                <div class="user-badge">Farmer · ID: <?= $farmerId ?></div>
            </div>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Welcome Section -->
        <div class="welcome">
            <h1>Welcome back, <span><?= htmlspecialchars(explode(' ', $farmerName)[0]) ?></span>! 👋</h1>
            <p>Here's your dairy farming dashboard and recent activities</p>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-tint"></i>
                </div>
                <div class="stat-value"><?= number_format($stats['total_litres'], 1) ?> L</div>
                <div class="stat-label">Total Milk Delivered</div>
                <div class="stat-sub"><?= $stats['total_deliveries'] ?> deliveries</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-value"><?= number_format($stats['avg_fat'], 2) ?>%</div>
                <div class="stat-label">Average Fat Content</div>
                <div class="stat-sub">Protein: <?= number_format($stats['avg_protein'], 2) ?>%</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="stat-value">KES <?= number_format($stats['total_paid'], 0) ?></div>
                <div class="stat-label">Total Earnings</div>
                <div class="stat-sub">Paid to date</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">KES <?= number_format($stats['total_pending'], 0) ?></div>
                <div class="stat-label">Pending Payments</div>
                <div class="stat-sub">Awaiting processing</div>
            </div>
        </div>

        <!-- Current Milk Prices -->
        <div class="section-card">
            <div class="section-title"><i class="fas fa-tags"></i> Current Milk Prices</div>
            <div class="price-grid">
                <?php
                $gradeClass = ['A'=>'grade-a','B'=>'grade-b','C'=>'grade-c'];
                foreach(['A','B','C'] as $grade):
                    $price = $priceMap[$grade]['price_per_litre'] ?? ['A'=>55,'B'=>45,'C'=>35][$grade];
                    $bonus = $priceMap[$grade]['quality_bonus'] ?? 0;
                ?>
                <div class="price-card <?= $gradeClass[$grade] ?>">
                    <div class="price-grade">Grade <?= $grade ?></div>
                    <div class="price-value">KES <?= number_format($price, 2) ?></div>
                    <div class="price-label">per litre</div>
                    <?php if($bonus > 0): ?>
                    <div class="price-bonus">+KES <?= $bonus ?> bonus</div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Charts Row -->
        <?php if(!empty($monthlyData)): ?>
        <div class="charts-row">
            <!-- Monthly Collection Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">Monthly Milk Collection</div>
                    <div class="chart-sub">Last 6 months</div>
                </div>
                <canvas id="collectionChart" style="max-height:180px;"></canvas>
            </div>

            <!-- Quality Distribution Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">Quality Distribution</div>
                    <div class="chart-sub">Based on your deliveries</div>
                </div>
                <canvas id="qualityChart" style="max-height:180px;"></canvas>
            </div>
        </div>
        <?php endif; ?>

        <!-- Recent Deliveries Table -->
        <div class="table-card">
            <div class="table-header">
                <div class="table-title"><i class="fas fa-truck"></i> Recent Milk Deliveries</div>
                <a href="deliveries.php" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <?php if(empty($recentDeliveries)): ?>
                <div class="empty-state">
                    <i class="fas fa-truck"></i>
                    <p>No deliveries recorded yet</p>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Session</th>
                                <th>Quantity</th>
                                <th>Fat %</th>
                                <th>Protein %</th>
                                <th>Grade</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recentDeliveries as $d): ?>
                            <tr>
                                <td><?= date('d M Y', strtotime($d['delivery_date'])) ?></td>
                                <td><span class="badge" style="background:#f1f5f9;color:#475569;"><?= ucfirst($d['session']) ?></span></td>
                                <td><strong><?= number_format($d['quantity_litres'], 1) ?> L</strong></td>
                                <td><?= $d['fat_content'] ? number_format($d['fat_content'], 2) . '%' : '—' ?></td>
                                <td><?= $d['protein_content'] ? number_format($d['protein_content'], 2) . '%' : '—' ?></td>
                                <td>
                                    <?php if($d['quality_grade']): ?>
                                        <span class="badge grade-<?= strtolower($d['quality_grade']) ?>">
                                            Grade <?= $d['quality_grade'] ?>
                                        </span>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted);">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="amount">KES <?= number_format($d['total_amount'], 2) ?></td>
                                <td>
                                    <span class="badge <?= $d['payment_status'] ?>">
                                        <?= ucfirst($d['payment_status']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Payments Table -->
        <div class="table-card">
            <div class="table-header">
                <div class="table-title"><i class="fas fa-money-bill-wave"></i> Recent Payments</div>
                <a href="payments.php" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <?php if(empty($recentPayments)): ?>
                <div class="empty-state">
                    <i class="fas fa-money-bill-wave"></i>
                    <p>No payments processed yet</p>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Period</th>
                                <th>Litres</th>
                                <th>Base Amount</th>
                                <th>Bonus</th>
                                <th>Net Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recentPayments as $p): ?>
                            <tr>
                                <td><?= date('d M Y', strtotime($p['payment_date'])) ?></td>
                                <td><?= date('d M', strtotime($p['payment_period_start'])) ?> - <?= date('d M', strtotime($p['payment_period_end'])) ?></td>
                                <td><?= number_format($p['total_litres'], 1) ?> L</td>
                                <td>KES <?= number_format($p['base_amount'], 2) ?></td>
                                <td style="color: #d97706;">+KES <?= number_format($p['quality_bonus'], 2) ?></td>
                                <td class="amount">KES <?= number_format($p['net_amount'], 2) ?></td>
                                <td>
                                    <span class="badge <?= $p['payment_status'] ?>">
                                        <?= ucfirst($p['payment_status']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Announcements -->
        <?php if(!empty($announcements)): ?>
        <div class="table-card">
            <div class="table-header">
                <div class="table-title"><i class="fas fa-bullhorn"></i> Announcements</div>
            </div>
            <?php foreach($announcements as $a): ?>
            <div class="announcement-card priority-<?= $a['priority'] ?? 'normal' ?>">
                <div class="announcement-title"><?= htmlspecialchars($a['title']) ?></div>
                <div class="announcement-meta">
                    <i class="far fa-clock"></i> <?= date('d M Y', strtotime($a['created_at'])) ?>
                    <?php if($a['priority'] === 'urgent'): ?>
                        <span style="color:#f87171;font-weight:700;">⚠️ Urgent</span>
                    <?php endif; ?>
                </div>
                <div class="announcement-content"><?= nl2br(htmlspecialchars($a['content'])) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <script>
        // Monthly Collection Chart
        const ctx1 = document.getElementById('collectionChart');
        if(ctx1) {
            const chartData = <?= json_encode($monthlyData) ?>;
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: chartData.map(d => d.month),
                    datasets: [{
                        label: 'Litres',
                        data: chartData.map(d => d.litres),
                        borderColor: '#00875a',
                        backgroundColor: 'rgba(0,135,90,0.08)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#00875a',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { backgroundColor: '#161f38' }
                    },
                    scales: {
                        x: {
                            grid: { color: '#eef2f7' },
                            ticks: { color: '#7a93a6' }
                        },
                        y: {
                            grid: { color: '#eef2f7' },
                            ticks: { 
                                color: '#7a93a6',
                                callback: function(value) { return value + 'L'; }
                            }
                        }
                    }
                }
            });
        }

        // Quality Chart
        const ctx2 = document.getElementById('qualityChart');
        if(ctx2) {
            const qualityData = <?= json_encode($qualityData) ?>;
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Grade A', 'Grade B', 'Grade C', 'Rejected'],
                    datasets: [{
                        data: [qualityData.A, qualityData.B, qualityData.C, qualityData.rejected],
                        backgroundColor: [
                            'rgba(46, 204, 113, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(240, 165, 0, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderColor: 'rgba(0,0,0,0)',
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: { 
                                color: '#4a6070',
                                font: { size: 11 },
                                padding: 14
                            }
                        },
                        tooltip: { backgroundColor: '#0d1f2d' }
                    },
                    cutout: '65%'
                }
            });
        }
    </script>
</body>
</html>