<?php
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','milkdairy_db');
define('SYSTEM_NAME','Githunguri Dairy Management System');
define('COOPERATIVE_NAME','Githunguri Dairy Farmers Cooperative Society');
define('GROQ_API_KEY','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');
define('GROQ_API_URL','https://api.groq.com/openai/v1/chat/completions');
define('GROQ_MODEL','llama-3.3-70b-versatile');
define('BASE_URL','https://triston-nonglutenous-nickolas.ngrok-free.dev/gdms_v10/gdms_v2/');
define('ACCESS_CODE','GDFC2024');

// ============================================================
// M-PESA DARAJA API (Safaricom)
// ============================================================
define('MPESA_CONSUMER_KEY',    'isggp3fCEpVHEsGvFIo8quVwRw2gBZNSyFS61fsAK0UePURJ');
define('MPESA_CONSUMER_SECRET', '032Rh0eanY3CAZGKwgvedbRNfQR0rRGaBGMGsApT2AqpY40BbHbUSPLX9JKMZg4L');
define('MPESA_SHORTCODE',       '174379');    // STK Push shortcode (keep for other uses)
define('MPESA_B2C_SHORTCODE',   '600989');    // B2C sandbox shortcode — different from STK
define('MPESA_PASSKEY',         'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919');
define('MPESA_ENV',             'sandbox');
define('MPESA_CALLBACK_URL',    BASE_URL.'php/mpesa_callback.php');
define('MPESA_STK_URL',         'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
define('MPESA_TOKEN_URL',       'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');
define('MPESA_INITIATOR_NAME',      'testapi');
define('MPESA_SECURITY_CREDENTIAL', 'hSxk4cMWfjecIVLZYu4Oz8htb4MvHzNz/DCi9PQBwJfJlTnrHiPtbvd81NNIArnPE6zAEl3xvnfL2iL2K0ewTntykbCkDIYfp0UdgfX9S9ip470Lwl0ZHFR+GiYg5i8WKtUUw44bjDFss0b+xNTs+XgvlhYjF9SWii4o79fksOsiCsxGBNzQ495CqrUERbZC51viuLvRkDM+esRfqUhVu1nC7oDCm8tez7TEdtyaEjzbUw9ZNC8GB6D/gEvXXEVZgsoaaFJkVpL+NofDyS5zhC4WB9rAUOH/lIuc5eExtxWB4yzJDUT7vM/kHIKJllDm+VuR5THCp4/DyAOjEKgmmw==');
define('MPESA_B2C_URL',             'https://sandbox.safaricom.co.ke/mpesa/b2c/v3/paymentrequest');
define('MPESA_B2C_RESULT_URL',      BASE_URL.'php/mpesa_b2c_result.php');
define('MPESA_B2C_TIMEOUT_URL',     BASE_URL.'php/mpesa_b2c_timeout.php');
class Database {
    private static $instance=null;
    private $conn;
    private function __construct(){
        try{
            $this->conn=new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",DB_USER,DB_PASS,
                [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
        }catch(PDOException $e){die(json_encode(['error'=>'DB Error: '.$e->getMessage()]));}
    }
    public static function getInstance(){if(!self::$instance)self::$instance=new Database();return self::$instance;}
    public function fetchAll($sql,$p=[]){$s=$this->conn->prepare($sql);$s->execute($p);return $s->fetchAll();}
    public function fetchOne($sql,$p=[]){$s=$this->conn->prepare($sql);$s->execute($p);return $s->fetch();}
    public function insert($sql,$p=[]){$s=$this->conn->prepare($sql);$s->execute($p);return $this->conn->lastInsertId();}
    public function update($sql,$p=[]){$s=$this->conn->prepare($sql);$s->execute($p);return $s->rowCount();}
}

function sanitize($d){return htmlspecialchars(strip_tags(trim($d)));}
function hashPassword($p){return password_hash($p,PASSWORD_DEFAULT);}
function verifyPassword($p,$h){return password_verify($p,$h);}
function formatCurrency($a){return 'KES '.number_format($a,2);}
function isStaffLoggedIn(){return isset($_SESSION['staff_id'])&&!empty($_SESSION['staff_id'])&&isset($_SESSION['logged_in'])&&$_SESSION['logged_in']===true;}
function isFarmerLoggedIn(){return isset($_SESSION['farmer_id'])&&!empty($_SESSION['farmer_id'])&&isset($_SESSION['farmer_logged_in'])&&$_SESSION['farmer_logged_in']===true;}
function requireStaffLogin(){if(!isStaffLoggedIn()){header('Location: ../staff/login.php');exit;}}
function requireFarmerLogin(){if(!isFarmerLoggedIn()){header('Location: ../farmer/login.php');exit;}}

function logActivity($uType,$uId,$action,$details=null){
    try{$db=Database::getInstance();$ip=$_SERVER['REMOTE_ADDR']??'unknown';
        $db->insert("INSERT INTO system_logs(user_type,user_id,action,details,ip_address) VALUES(?,?,?,?,?)",[$uType,$uId,$action,$details,$ip]);
    }catch(Exception $e){}
}

// ============================================================
// MILK QUALITY GRADING
// Based on: fat, protein, pH, SNF, density, water, lactose,
// titratable acidity, SCC, antibiotic, alcohol test, added water
// Temperature is NOT a quality factor — it affects spoilage only
// ============================================================
function getQualityGrade($params) {
    $fat      = $params['fat'] ?? null;
    $protein  = $params['protein'] ?? null;
    $ph       = $params['ph'] ?? null;
    $snf      = $params['snf'] ?? null;
    $density  = $params['density'] ?? null;
    $water    = $params['water'] ?? 0;
    $lactose  = $params['lactose'] ?? null;
    $ta       = $params['titratable_acidity'] ?? null;
    $scc      = $params['somatic_cell_count'] ?? 'low';
    $antibiotic = $params['antibiotic'] ?? 'negative';
    $alcohol  = $params['alcohol'] ?? 'pass';
    $addedWater = $params['added_water'] ?? 'pass';

    $issues = []; $score = 100; $grade = 'A';

    // --- INSTANT REJECTION ---
    if ($antibiotic === 'positive') return ['grade'=>'rejected','score'=>0,'status'=>'rejected','issues'=>['Antibiotic positive — milk cannot be used']];
    if ($water > 15) return ['grade'=>'rejected','score'=>0,'status'=>'rejected','issues'=>['Excessive water adulteration (>'.$water.'%)']];
    if ($ph !== null && ($ph < 5.8 || $ph > 7.2)) return ['grade'=>'rejected','score'=>0,'status'=>'rejected','issues'=>['pH critically out of range ('.$ph.')']];
    if ($addedWater === 'fail') { $issues[] = 'Added water test failed'; $score -= 30; $grade = 'C'; }
    if ($alcohol === 'fail') { $issues[] = 'Alcohol stability test failed'; $score -= 15; $grade = max_grade($grade,'B'); }

    // --- FAT CONTENT ---
    if ($fat !== null) {
        if ($fat >= 3.8) { /* premium */ }
        elseif ($fat >= 3.5) { $score -= 5; }
        elseif ($fat >= 3.0) { $score -= 15; $issues[] = 'Below optimal fat ('.$fat.'%)'; $grade = max_grade($grade,'B'); }
        else { $score -= 30; $issues[] = 'Low fat content ('.$fat.'%)'; $grade = max_grade($grade,'C'); }
    }

    // --- PROTEIN ---
    if ($protein !== null) {
        if ($protein >= 3.2) { /* excellent */ }
        elseif ($protein >= 3.0) { $score -= 5; }
        elseif ($protein >= 2.8) { $score -= 12; $issues[] = 'Below optimal protein ('.$protein.'%)'; $grade = max_grade($grade,'B'); }
        else { $score -= 25; $issues[] = 'Low protein ('.$protein.'%)'; $grade = max_grade($grade,'C'); }
    }

    // --- pH/ACIDITY ---
    if ($ph !== null) {
        if ($ph >= 6.6 && $ph <= 6.8) { /* ideal */ }
        elseif ($ph >= 6.4 && $ph <= 7.0) { $score -= 8; $issues[] = 'pH slightly off ('.$ph.')'; $grade = max_grade($grade,'B'); }
        elseif ($ph >= 6.0 && $ph <= 7.2) { $score -= 20; $issues[] = 'pH out of optimal range ('.$ph.')'; $grade = max_grade($grade,'C'); }
    }

    // --- TITRATABLE ACIDITY ---
    if ($ta !== null) {
        if ($ta >= 16 && $ta <= 18) { /* normal */ }
        elseif ($ta >= 14 && $ta <= 20) { $score -= 8; $issues[] = 'Titratable acidity slightly off ('.$ta.'°T)'; $grade = max_grade($grade,'B'); }
        else { $score -= 18; $issues[] = 'Titratable acidity abnormal ('.$ta.'°T)'; $grade = max_grade($grade,'C'); }
    }

    // --- SNF ---
    if ($snf !== null) {
        if ($snf >= 8.8) { /* good */ }
        elseif ($snf >= 8.5) { $score -= 5; }
        elseif ($snf >= 8.0) { $score -= 12; $issues[] = 'Low SNF ('.$snf.'%)'; $grade = max_grade($grade,'B'); }
        else { $score -= 20; $issues[] = 'Very low SNF ('.$snf.'%)'; $grade = max_grade($grade,'C'); }
    }

    // --- DENSITY ---
    if ($density !== null) {
        if ($density >= 1.028 && $density <= 1.033) { /* normal */ }
        elseif ($density >= 1.026 && $density <= 1.035) { $score -= 8; $issues[] = 'Density slightly off ('.$density.' g/ml)'; $grade = max_grade($grade,'B'); }
        else { $score -= 20; $issues[] = 'Density abnormal ('.$density.' g/ml)'; $grade = max_grade($grade,'C'); }
    }

    // --- WATER CONTENT ---
    if ($water > 5) { $score -= 20; $issues[] = 'Elevated water content ('.$water.'%)'; $grade = max_grade($grade,'C'); }
    elseif ($water > 2) { $score -= 8; $issues[] = 'Slight water content ('.$water.'%)'; $grade = max_grade($grade,'B'); }

    // --- LACTOSE ---
    if ($lactose !== null) {
        if ($lactose >= 4.5 && $lactose <= 5.0) { /* ideal */ }
        elseif ($lactose >= 4.0 && $lactose <= 5.5) { $score -= 5; }
        else { $score -= 15; $issues[] = 'Lactose out of range ('.$lactose.'%)'; $grade = max_grade($grade,'B'); }
    }

    // --- SOMATIC CELL COUNT ---
    if ($scc === 'high') { $score -= 25; $issues[] = 'High somatic cell count (mastitis risk)'; $grade = max_grade($grade,'C'); }
    elseif ($scc === 'medium') { $score -= 10; $issues[] = 'Moderate somatic cell count'; $grade = max_grade($grade,'B'); }

    $score = max(0, $score);
    $statusMap = ['A'=>'excellent','B'=>'good','C'=>'acceptable','rejected'=>'rejected'];

    // Override grade by score if needed
    if ($score >= 88 && $grade === 'A') $grade = 'A';
    elseif ($score >= 70 && $grade === 'A') $grade = 'B';
    elseif ($score < 70 && $grade === 'A') $grade = 'C';

    return ['grade'=>$grade,'score'=>$score,'status'=>$statusMap[$grade],'issues'=>$issues];
}

function max_grade($current, $new) {
    $order = ['A'=>1,'B'=>2,'C'=>3,'rejected'=>4];
    return ($order[$new] > $order[$current]) ? $new : $current;
}

// ============================================================
// SPOILAGE DETECTION
// Based on: temperature, smell, visual, storage hours, alcohol
// Completely independent from quality grade
// ============================================================
function getSpoilageRisk($params) {
    $temp    = $params['temperature'] ?? null;
    $smell   = $params['smell'] ?? 'normal';
    $visual  = $params['visual'] ?? 'normal';
    $hours   = $params['storage_hours'] ?? 0;
    $alcohol = $params['alcohol'] ?? 'pass';

    $score = 0; $issues = [];

    // Temperature scoring (primary factor)
    if ($temp !== null) {
        if ($temp <= 4)       { $score += 5;  }
        elseif ($temp <= 6)   { $score += 15; }
        elseif ($temp <= 8)   { $score += 40; $issues[] = 'High temperature ('.$temp.'°C)'; }
        elseif ($temp <= 10)  { $score += 65; $issues[] = 'Very high temperature ('.$temp.'°C)'; }
        else                  { $score += 90; $issues[] = 'Critical temperature ('.$temp.'°C) — milk likely spoiled'; }
    }

    // Smell check
    if ($smell === 'slightly_off') { $score += 15; $issues[] = 'Slightly off smell detected'; }
    elseif ($smell === 'sour')     { $score += 35; $issues[] = 'Sour smell — high spoilage risk'; }
    elseif ($smell === 'bad')      { $score += 60; $issues[] = 'Bad smell — milk likely spoiled'; }

    // Visual check
    if ($visual === 'slightly_off')  { $score += 10; $issues[] = 'Slight visual abnormality'; }
    elseif ($visual === 'clotted')   { $score += 40; $issues[] = 'Clotted milk — reject for spoilage'; }
    elseif ($visual === 'watery')    { $score += 20; $issues[] = 'Watery appearance'; }
    elseif ($visual === 'yellow')    { $score += 25; $issues[] = 'Yellow discolouration'; }

    // Storage hours
    if ($hours > 24)     { $score += 30; $issues[] = 'Stored over 24 hours'; }
    elseif ($hours > 12) { $score += 15; $issues[] = 'Stored over 12 hours — monitor closely'; }
    elseif ($hours > 8)  { $score += 8;  $issues[] = 'Stored over 8 hours'; }

    // Alcohol test failed = unstable milk
    if ($alcohol === 'fail') { $score += 25; $issues[] = 'Alcohol stability test failed'; }

    $score = min(100, $score);

    if ($score >= 70)      $risk = 'critical';
    elseif ($score >= 45)  $risk = 'high';
    elseif ($score >= 20)  $risk = 'medium';
    else                   $risk = 'low';

    return ['risk'=>$risk,'score'=>$score,'issues'=>$issues];
}

function getPricePerLitre($grade){
    try{
        $db=Database::getInstance();
        $p=$db->fetchOne("SELECT price_per_litre FROM price_config WHERE grade=? AND status='active' ORDER BY effective_date DESC LIMIT 1",[$grade]);
        return $p?floatval($p['price_per_litre']):['A'=>55,'B'=>45,'C'=>35,'rejected'=>0][$grade]??45;
    }catch(Exception $e){return ['A'=>55,'B'=>45,'C'=>35,'rejected'=>0][$grade]??45;}
}
function getQualityBonus($grade){
    try{
        $db=Database::getInstance();
        $p=$db->fetchOne("SELECT quality_bonus FROM price_config WHERE grade=? AND status='active' ORDER BY effective_date DESC LIMIT 1",[$grade]);
        return $p?floatval($p['quality_bonus']):($grade==='A'?5:0);
    }catch(Exception $e){return $grade==='A'?5:0;}
}
function generateDeliveryId(){return 'DLV'.date('Ymd').str_pad(rand(1,9999),4,'0',STR_PAD_LEFT);}
function generatePaymentId(){return 'PAY'.date('Ym').str_pad(rand(1,999),3,'0',STR_PAD_LEFT);}
function generateFarmerId() {
    $db = Database::getInstance();
    do {
        $max = $db->fetchOne("SELECT MAX(CAST(SUBSTRING(farmer_id, 4) AS UNSIGNED)) as maxid FROM farmers");
        $nextNum = ($max['maxid'] ?? 0) + 1;
        $farmerId = 'FRM' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
        $exists = $db->fetchOne("SELECT farmer_id FROM farmers WHERE farmer_id = ?", [$farmerId]);
        if($exists) $nextNum++;
    } while($exists);
    
    return $farmerId;
}