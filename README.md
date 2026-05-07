# 🥛 Githunguri Dairy Management System (GDMS v10)

A full-stack web-based management system built for **Githunguri Dairy Farmers Cooperative Society** to streamline milk collection, farmer management, payments, and AI-powered milk quality analysis.

---

## 🌟 Features

### 👨‍🌾 Farmer Portal
- Farmer registration & login
- Personal dashboard with delivery history
- View payment records and milk collection data

### 🧑‍💼 Staff/Admin Portal
- Admin dashboard with full system control
- Manage farmers, milk deliveries & tank levels
- Announcements management

### 💳 M-Pesa Payment Integration (Safaricom Daraja API)
- STK Push – prompt farmers to pay directly on their phones
- B2C Payments – send payments directly to farmers
- Automated payment callbacks & processing

### 🤖 AI Milk Quality Analysis (Powered by Groq + LLaMA 3)
- Analyzes milk parameters (fat, protein, pH, SNF, density, etc.)
- Grades milk: **Grade A / B / C / Rejected**
- Detects spoilage risk based on temperature and sensory data

### 📊 Reports & Analytics
- Generate milk delivery reports
- Track payment previews
- Monitor milk tank levels

---

## 🛠️ Technologies Used

| Layer | Technology |
|-------|-----------|
| Frontend | HTML5, CSS3, JavaScript |
| Backend | PHP (PDO) |
| Database | MySQL |
| Payments | M-Pesa Daraja API (STK Push & B2C) |
| AI Analysis | Groq API (LLaMA 3.3-70B) |

---

## ⚙️ Installation & Setup

### Requirements
- PHP 7.4+, MySQL 5.7+, XAMPP/WAMP

### Steps
1. Clone the repo: `git clone https://github.com/254-David/gdms_v10.git`
2. Move to `htdocs/` folder
3. Import `gdms_v2/assets/milkdairy_db.sql` into phpMyAdmin as `milkdairy_db`
4. Start Apache & MySQL in XAMPP
5. Visit: `http://localhost/gdms_v10/gdms_v2/`

---

## 🔐 Access

| Portal | URL |
|--------|-----|
| Home | `/index.php` |
| Farmer Login | `/farmer/login.php` |
| Staff Login | `/staff/login.php` |
| Access Code | `GDFC2024` |

---

## 📄 Documentation
📎 [MILK DAIRY MANAGEMENT SYSTEM.docx](./MILK%20DAIRY%20MANAGEMENT%20SYSTEM.docx)

---

## 👨‍💻 Author
**David** – [254-David](https://github.com/254-David)
