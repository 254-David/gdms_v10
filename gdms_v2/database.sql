-- =====================================================
-- GDMS v2 — Githunguri Dairy Management System
-- Enhanced Database Schema
-- =====================================================

CREATE DATABASE IF NOT EXISTS milkdairy_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE milkdairy_db;

-- ===== STAFF =====
CREATE TABLE IF NOT EXISTS staff (
    id INT AUTO_INCREMENT PRIMARY KEY,
    staff_id VARCHAR(20) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    role ENUM('admin','manager','quality_inspector','data_entry','accountant') DEFAULT 'data_entry',
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    access_code VARCHAR(20) NOT NULL,
    status ENUM('active','inactive','suspended') DEFAULT 'active',
    profile_notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);
-- Default password: "password"
INSERT IGNORE INTO staff (staff_id,full_name,email,phone,role,username,password,access_code) VALUES
('STF001','John Mwangi','admin@githunguri.coop','0712345678','admin','admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','GDFC2024'),
('STF002','Mary Wanjiku','manager@githunguri.coop','0723456789','manager','manager','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','GDFC2024'),
('STF003','Peter Kamau','quality@githunguri.coop','0734567890','quality_inspector','quality','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','GDFC2024');

-- ===== FARMERS =====
CREATE TABLE IF NOT EXISTS farmers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_id VARCHAR(20) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    id_number VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    location VARCHAR(100),
    ward VARCHAR(100),
    subcounty VARCHAR(100) DEFAULT 'Githunguri',
    county VARCHAR(100) DEFAULT 'Kiambu',
    farm_size DECIMAL(10,2) DEFAULT 0,
    number_of_cows INT DEFAULT 0,
    cow_breeds VARCHAR(255),
    bank_name VARCHAR(100),
    bank_account VARCHAR(50),
    mpesa_number VARCHAR(20),
    registration_date DATE DEFAULT (CURRENT_DATE),
    approval_status ENUM('pending','approved','rejected') DEFAULT 'approved',
    status ENUM('active','inactive','suspended') DEFAULT 'active',
    performance_score INT DEFAULT 100,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    total_deliveries INT DEFAULT 0,
    total_litres DECIMAL(10,2) DEFAULT 0,
    total_earnings DECIMAL(10,2) DEFAULT 0
);
INSERT IGNORE INTO farmers (farmer_id,full_name,email,phone,id_number,password,location,ward,number_of_cows,cow_breeds,bank_name,bank_account,mpesa_number,total_deliveries,total_litres,total_earnings,performance_score) VALUES
('FRM001','Alice Wambui Njoroge','alice@email.com','0701234567','12345678','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Githunguri Town','Githunguri',5,'Friesian,Ayrshire','Equity Bank','1234567890','0701234567',8,198.5,10932.50,95),
('FRM002','James Kariuki Mwangi','james@email.com','0712345678','23456789','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Komothai','Komothai',8,'Friesian','KCB Bank','9876543210','0712345678',6,240.5,13227.50,98),
('FRM003','Grace Nyambura Kimani','grace@email.com','0723456789','34567890','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Ngewa','Ngewa',3,'Jersey,Ayrshire','Cooperative Bank','5678901234','0723456789',5,96.0,4320.00,82),
('FRM004','Samuel Njoroge Gitau','samuel@email.com','0745678901','45678901','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Kiambu','Kiambu',4,'Friesian','Equity Bank','3456789012','0745678901',3,68.0,3060.00,77);

-- ===== MILK DELIVERIES (Enhanced with more quality parameters) =====
CREATE TABLE IF NOT EXISTS milk_deliveries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    delivery_id VARCHAR(30) UNIQUE NOT NULL,
    farmer_id VARCHAR(20) NOT NULL,
    recorded_by VARCHAR(20) NOT NULL,
    delivery_date DATE NOT NULL,
    delivery_time TIME NOT NULL,
    session ENUM('morning','evening') NOT NULL,
    quantity_litres DECIMAL(8,2) NOT NULL,
    -- SPOILAGE PARAMETERS (temperature-based)
    temperature DECIMAL(5,2) DEFAULT NULL COMMENT 'Celsius — for spoilage detection',
    smell_check ENUM('normal','slightly_off','sour','bad') DEFAULT 'normal',
    visual_check ENUM('normal','slightly_off','clotted','watery','yellow') DEFAULT 'normal',
    storage_hours DECIMAL(5,2) DEFAULT 0,
    -- QUALITY PARAMETERS (grade-determining)
    fat_content DECIMAL(5,2) DEFAULT NULL COMMENT 'Percentage — ideal >3.5%',
    protein_content DECIMAL(5,2) DEFAULT NULL COMMENT 'Percentage — ideal >3.0%',
    acidity DECIMAL(5,3) DEFAULT NULL COMMENT 'pH — ideal 6.6-6.8',
    titratable_acidity DECIMAL(5,2) DEFAULT NULL COMMENT 'Degrees Thörner — ideal 16-18',
    snf DECIMAL(5,2) DEFAULT NULL COMMENT 'Solid Non-Fat % — ideal >8.5%',
    density DECIMAL(7,4) DEFAULT NULL COMMENT 'g/ml — ideal 1.028-1.033',
    water_content DECIMAL(5,2) DEFAULT NULL COMMENT 'Adulteration % — ideal <5%',
    lactose DECIMAL(5,2) DEFAULT NULL COMMENT 'Percentage — ideal 4.5-5.0%',
    conductivity DECIMAL(5,2) DEFAULT NULL COMMENT 'mS/cm — ideal 4.0-5.5',
    freezing_point DECIMAL(6,3) DEFAULT NULL COMMENT 'Celsius — ideal -0.530 to -0.560',
    somatic_cell_count ENUM('low','medium','high') DEFAULT 'low' COMMENT 'low=<200k, medium=200-400k, high=>400k SCC/ml',
    antibiotic_test ENUM('negative','positive','pending') DEFAULT 'pending',
    alcohol_test ENUM('pass','fail','pending') DEFAULT 'pending' COMMENT 'Alcohol stability test',
    added_water_test ENUM('pass','fail','pending') DEFAULT 'pass',
    -- COMPUTED RESULTS
    quality_grade ENUM('A','B','C','rejected') DEFAULT NULL,
    quality_score INT DEFAULT NULL COMMENT '0-100 composite quality score',
    quality_status VARCHAR(20) DEFAULT NULL,
    quality_issues TEXT DEFAULT NULL,
    spoilage_risk ENUM('low','medium','high','critical') DEFAULT 'low',
    spoilage_score INT DEFAULT NULL COMMENT '0-100 spoilage risk score',
    rejection_reason TEXT DEFAULT NULL,
    -- PAYMENT
    storage_tank VARCHAR(50) DEFAULT NULL,
    price_per_litre DECIMAL(6,2) DEFAULT 45.00,
    total_amount DECIMAL(10,2) DEFAULT 0,
    payment_status ENUM('pending','paid','processing') DEFAULT 'pending',
    -- MISC
    notes TEXT DEFAULT NULL,
    ai_analysis TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT IGNORE INTO milk_deliveries (delivery_id,farmer_id,recorded_by,delivery_date,delivery_time,session,quantity_litres,temperature,smell_check,visual_check,fat_content,protein_content,acidity,titratable_acidity,snf,density,water_content,lactose,somatic_cell_count,antibiotic_test,alcohol_test,quality_grade,quality_score,quality_status,spoilage_risk,spoilage_score,storage_tank,price_per_litre,total_amount,payment_status) VALUES
('DLV20241101001','FRM001','STF003','2024-11-01','06:30:00','morning',25.5,4.2,'normal','normal',3.8,3.2,6.70,16.5,8.8,1.030,0,4.8,'low','negative','pass','A',94,'excellent','low',8,'TK001',55.00,1402.50,'paid'),
('DLV20241101002','FRM002','STF003','2024-11-01','07:00:00','morning',42.0,4.5,'normal','normal',4.1,3.4,6.65,17.0,9.0,1.031,0,4.9,'low','negative','pass','A',97,'excellent','low',5,'TK001',55.00,2310.00,'paid'),
('DLV20241101003','FRM003','STF003','2024-11-01','06:45:00','morning',18.0,5.0,'normal','normal',3.2,3.0,6.60,17.5,8.5,1.029,2,4.6,'medium','negative','pass','B',78,'good','low',12,'TK002',45.00,810.00,'paid'),
('DLV20241102001','FRM001','STF003','2024-11-02','06:30:00','morning',24.0,4.0,'normal','normal',3.9,3.3,6.72,16.0,8.9,1.030,0,4.9,'low','negative','pass','A',96,'excellent','low',6,'TK001',55.00,1320.00,'paid'),
('DLV20241102002','FRM002','STF003','2024-11-02','07:15:00','morning',38.5,4.3,'normal','normal',4.0,3.3,6.68,16.8,8.8,1.031,0,4.8,'low','negative','pass','A',95,'excellent','low',7,'TK001',55.00,2117.50,'paid'),
('DLV20241103001','FRM001','STF003','2024-11-03','06:35:00','morning',26.0,4.1,'normal','normal',3.7,3.1,6.69,16.2,8.7,1.030,0,4.7,'low','negative','pass','A',92,'excellent','low',6,'TK001',55.00,1430.00,'pending'),
('DLV20241103002','FRM003','STF003','2024-11-03','07:00:00','morning',20.0,5.2,'slightly_off','normal',3.0,2.9,6.58,18.5,8.3,1.028,3,4.5,'medium','negative','pass','B',72,'good','low',15,'TK002',45.00,900.00,'pending'),
('DLV20241103003','FRM004','STF003','2024-11-03','08:30:00','morning',15.0,8.5,'slightly_off','slightly_off',3.4,3.0,6.55,19.0,8.4,1.029,1,4.6,'medium','negative','fail','C',60,'acceptable','high',72,'TK002',35.00,525.00,'pending'),
('DLV20241104001','FRM002','STF003','2024-11-04','06:50:00','morning',45.0,4.2,'normal','normal',4.2,3.5,6.71,16.5,9.1,1.032,0,5.0,'low','negative','pass','A',98,'excellent','low',5,'TK001',55.00,2475.00,'pending'),
('DLV20241104002','FRM004','STF003','2024-11-04','09:00:00','morning',12.0,9.2,'sour','slightly_off',3.1,2.8,6.50,20.5,8.1,1.027,4,4.3,'high','negative','fail','C',52,'acceptable','high',85,'TK003',35.00,420.00,'pending');

-- ===== STORAGE TANKS =====
CREATE TABLE IF NOT EXISTS storage_tanks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tank_id VARCHAR(20) UNIQUE NOT NULL,
    tank_name VARCHAR(100) NOT NULL,
    capacity_litres DECIMAL(10,2) NOT NULL,
    current_volume DECIMAL(10,2) DEFAULT 0,
    temperature DECIMAL(5,2) DEFAULT NULL,
    status ENUM('active','maintenance','full','empty') DEFAULT 'empty',
    last_cleaned DATE DEFAULT NULL,
    next_cleaning_due DATE DEFAULT NULL,
    location VARCHAR(100) DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
INSERT IGNORE INTO storage_tanks (tank_id,tank_name,capacity_litres,current_volume,temperature,status,last_cleaned,next_cleaning_due,location) VALUES
('TK001','Tank A — Primary Cooling',5000.00,1200.00,4.5,'active','2024-11-01','2024-11-08','Main Collection Center'),
('TK002','Tank B — Secondary Cooling',3000.00,800.00,4.8,'active','2024-10-28','2024-11-04','Main Collection Center'),
('TK003','Tank C — Buffer',2000.00,0.00,5.0,'empty','2024-10-20','2024-11-03','Secondary Center');

-- ===== PAYMENTS =====
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    payment_id VARCHAR(30) UNIQUE NOT NULL,
    farmer_id VARCHAR(20) NOT NULL,
    processed_by VARCHAR(20) NOT NULL,
    payment_period_start DATE NOT NULL,
    payment_period_end DATE NOT NULL,
    total_litres DECIMAL(10,2) NOT NULL,
    grade_a_litres DECIMAL(10,2) DEFAULT 0,
    grade_b_litres DECIMAL(10,2) DEFAULT 0,
    grade_c_litres DECIMAL(10,2) DEFAULT 0,
    base_amount DECIMAL(10,2) NOT NULL,
    quality_bonus DECIMAL(10,2) DEFAULT 0,
    deductions DECIMAL(10,2) DEFAULT 0,
    deduction_reason TEXT DEFAULT NULL,
    net_amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('bank_transfer','mpesa','cash','cheque') DEFAULT 'mpesa',
    payment_status ENUM('pending','processing','completed','failed') DEFAULT 'pending',
    transaction_ref VARCHAR(100) DEFAULT NULL,
    payment_date TIMESTAMP NULL,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT IGNORE INTO payments (payment_id,farmer_id,processed_by,payment_period_start,payment_period_end,total_litres,grade_a_litres,grade_b_litres,base_amount,quality_bonus,net_amount,payment_method,payment_status,transaction_ref,payment_date) VALUES
('PAY202411001','FRM001','STF002','2024-11-01','2024-11-02',49.5,49.5,0,2722.50,247.50,2970.00,'mpesa','completed','MPE241116001','2024-11-16 10:00:00'),
('PAY202411002','FRM002','STF002','2024-11-01','2024-11-02',80.5,80.5,0,4427.50,402.50,4830.00,'bank_transfer','completed','BNK241116002','2024-11-16 10:30:00'),
('PAY202411003','FRM003','STF002','2024-11-01','2024-11-01',18.0,0,18.0,810.00,0,810.00,'mpesa','completed','MPE241116003','2024-11-16 11:00:00');

-- ===== PRICE CONFIG =====
CREATE TABLE IF NOT EXISTS price_config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    grade ENUM('A','B','C') NOT NULL,
    price_per_litre DECIMAL(6,2) NOT NULL,
    quality_bonus DECIMAL(6,2) DEFAULT 0,
    effective_date DATE NOT NULL,
    updated_by VARCHAR(20) DEFAULT NULL,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT IGNORE INTO price_config (grade,price_per_litre,quality_bonus,effective_date,status) VALUES
('A',55.00,5.00,'2024-01-01','active'),
('B',45.00,0.00,'2024-01-01','active'),
('C',35.00,0.00,'2024-01-01','active');

-- ===== ANNOUNCEMENTS =====
CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    target ENUM('all','farmers','staff') DEFAULT 'all',
    priority ENUM('low','normal','high','urgent') DEFAULT 'normal',
    posted_by VARCHAR(20) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    expires_at DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT IGNORE INTO announcements (title,content,target,priority,posted_by) VALUES
('Welcome to GDMS v2 Platform','We are pleased to announce the launch of our upgraded digital management system with enhanced quality testing parameters and spoilage detection.','all','high','STF001'),
('Updated Milk Prices — 2024','Grade A milk: KES 55/L + KES 5 quality bonus. Grade B: KES 45/L. Grade C: KES 35/L. Prices reviewed quarterly.','farmers','high','STF001'),
('Quality Standards Reminder','Milk must be cooled to below 6°C before delivery. Ensure alcohol test is passed. Antibiotic withdrawal periods must be observed.','farmers','normal','STF003'),
('Collection Schedule Update','Morning collection: 6:00 AM – 9:00 AM. Evening collection: 4:00 PM – 7:00 PM. Late deliveries may not be accepted.','farmers','normal','STF002');

-- ===== CONTACT MESSAGES =====
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread','read','replied') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===== SYSTEM LOGS =====
CREATE TABLE IF NOT EXISTS system_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_type ENUM('staff','farmer') NOT NULL,
    user_id VARCHAR(20) NOT NULL,
    action VARCHAR(255) NOT NULL,
    details TEXT DEFAULT NULL,
    ip_address VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===== NOTIFICATIONS =====
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    target_type ENUM('farmer','staff','all') NOT NULL,
    target_id VARCHAR(20) DEFAULT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info','warning','alert','success','payment','quality') DEFAULT 'info',
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
