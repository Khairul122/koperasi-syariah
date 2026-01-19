# Sistem Informasi Koperasi Simpan Pinjam Syariah

A comprehensive management information system for Sharia-compliant savings and financing cooperatives in Indonesia. This application provides complete functionality for managing cooperative operations while adhering to Islamic finance principles.

## 📋 Table of Contents

- [About](#about)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Islamic Finance Compliance](#islamic-finance-compliance)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Schema](#database-schema)
- [User Roles](#user-roles)
- [Project Structure](#project-structure)
- [Default Credentials](#default-credentials)
- [Screenshots](#screenshots)
- [Security Notes](#security-notes)
- [Contributing](#contributing)
- [License](#license)

## 📖 About

This project is a web-based application designed to automate and streamline the operations of Islamic cooperatives (Koperasi Syariah) in Indonesia. It replaces traditional manual processes with a digital system that ensures transparency, efficiency, and compliance with Sharia principles in all financial transactions.

**Key Objectives:**
- Facilitate member management and savings operations
- Provide Sharia-compliant financing with various Islamic contracts (Akad)
- Ensure transparent financial reporting and record-keeping
- Support cooperative growth through efficient operational workflows
- Maintain compliance with Indonesian cooperative regulations and Sharia principles

## ✨ Features

### Core Modules

#### 1. Authentication & Authorization
- Multi-role login system (Admin, Treasurer, Member)
- Secure session management
- Activity logging for audit trails
- User profile management

#### 2. Member Management (Anggota)
- Complete CRUD operations for member data
- Unique member ID generation (ANG-XXX format)
- NIK (National ID) validation (16 digits)
- Member status management (Active/Inactive)
- Member profile viewing and editing

#### 3. Savings Management (Simpanan)
- **Three Types of Savings:**
  - **Simpanan Pokok** (Principal Savings) - Minimum Rp 100.000
  - **Simpanan Wajib** (Mandatory Savings) - Minimum Rp 20.000
  - **Simpanan Sukarela** (Voluntary Savings) - Minimum Rp 5.000

- **Operations:**
  - Deposit transactions (Setor)
  - Withdrawal transactions (Tarik)
  - Profit distribution (Bagi Hasil)
  - Real-time balance tracking
  - Complete transaction history
  - PDF receipt generation

#### 4. Islamic Financing (Pembiayaan)
- **Supported Akad (Islamic Contracts):**
  - **Murabahah** - Cost-plus financing (sale with markup)
  - **Mudharabah** - Profit-sharing financing
  - **Musyarakah** - Partnership financing
  - **Ijaroh** - Lease/rent financing

- **Features:**
  - Online financing applications by members
  - Margin calculation (NOT interest)
  - Flexible tenor options (1-60 months)
  - Automatic installment calculation
  - Approval workflow by Treasurer
  - Status tracking (Pending → Approved/Rejected → Paid)
  - Minimum savings requirement validation (Rp 100.000)

#### 5. Installment Management (Angsuran)
- Payment recording and tracking
- Installment number tracking
- Remaining balance calculation
- Late payment penalty support
- Payment history and receipts
- PDF invoice generation

#### 6. Reporting System
- **Savings Reports:**
  - Daily, Monthly, and Annual reports
  - Transaction summaries (deposits vs withdrawals)
  - Per-member account statements

- **Financing Reports:**
  - Daily, Monthly, and Annual financing reports
  - Principal, Margin, and Total breakdown
  - Status-based filtering
  - Portfolio quality reports

- **Export Features:**
  - PDF generation with TCPDF
  - Professional formatting
  - Indonesian date formatting
  - Authorized signature sections

#### 7. Dashboard Analytics
- **Admin Dashboard:**
  - Overall statistics and KPIs
  - Transaction charts (30-day trends)
  - Recent activities overview
  - Financial summary

- **Treasurer (Bendahara) Dashboard:**
  - Transaction statistics
  - Recent transactions
  - Pending financing applications
  - Top savers ranking

- **Member Dashboard:**
  - Personal savings summary
  - Active financing overview
  - Payment schedule
  - Transaction history charts

## 🛠 Technology Stack

### Backend
- **Language:** PHP 8.1+
- **Architecture:** Custom MVC (Model-View-Controller) framework
- **Database:** MySQL 8.0+
- **Database Access:** PDO (PHP Data Objects) with prepared statements
- **Routing:** Custom query parameter-based routing

### Frontend
- **Framework:** Bootstrap 4
- **JavaScript:** jQuery
- **Data Visualization:** Chart.js
- **Table Management:** DataTables
- **Notifications:** SweetAlert2
- **Date Picker:** jQuery Datepicker
- **Icons:** Font Awesome

### Third-Party Libraries
- **TCPDF** (tecnickcom/tcpdf) - PDF generation for reports and receipts
- **Composer** - Dependency management

### Development Tools
- **Session Management:** PHP native sessions
- **Password Hashing:** MD5 (upgrading to bcrypt recommended)
- **Logging:** File-based activity logging

## 🕌 Islamic Finance Compliance

This application is specifically designed to ensure **Sharia compliance** in all financial operations:

### Key Principles
1. **No Riba (Interest)** - All transactions use margin or profit-sharing instead of interest
2. **Contract-Based (Akad)** - Every transaction follows clear Islamic contracts
3. **Transparent** - All margins and costs are clearly disclosed
4. **Halal Business** - Financing only for halal business activities

### Islamic Contracts Supported

| Akad Type | Description | Use Case |
|-----------|-------------|----------|
| **Wadiah** | Safekeeping contract | Savings deposits |
| **Murabahah** | Cost-plus sale | Asset financing with disclosed markup |
| **Mudharabah** | Profit-sharing | Business financing with profit/loss sharing |
| **Musyarakah** | Partnership | Joint venture financing |
| **Ijaroh** | Lease/Rent | Equipment or property leasing |

### Terminology Differences
| Conventional | Islamic (Sharia) |
|--------------|------------------|
| Loan (Pinjaman) | Financing (Pembiayaan) |
| Interest (Bunga) | Margin (Margin) |
| Loan Type | Akad (Contract) |
| Customer | Member (Anggota) |

## 🚀 Installation

### Prerequisites
- PHP 8.1 or higher
- MySQL 8.0 or higher
- Web Server (Apache/Nginx) or PHP built-in server
- Composer (for dependency management)

### Step 1: Clone Repository
```bash
git clone https://github.com/yourusername/koperasi-syariah.git
cd koperasi-syariah
```

### Step 2: Install Dependencies
```bash
composer install
```

### Step 3: Database Setup
Create a new MySQL database:
```sql
CREATE DATABASE kp_simpan_pinjam CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Import the database schema:
```bash
mysql -u root -p kp_simpan_pinjam < database/koperasi_syariah.sql
```

### Step 4: Configure Database Connection
Edit `config/koneksi.php`:
```php
<?php
class Koneksi {
    protected $host = "localhost";
    protected $user = "root";
    protected $pass = ""; // Your database password
    protected $db   = "kp_simpan_pinjam";

    // ... rest of the class
}
?>
```

### Step 5: Configure Web Server
**Option A: Apache (Recommended)**
```apache
<VirtualHost *:80>
    ServerName koperasi-syariah.local
    DocumentRoot "C:/laragon/www/koperasi-syariah"

    <Directory "C:/laragon/www/koperasi-syariah">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Option B: PHP Built-in Server (Development Only)**
```bash
php -S localhost:8000
```

### Step 6: Set Directory Permissions
Ensure write permissions for:
```bash
chmod 755 uploads/
chmod 755 logs/
```

### Step 7: Access Application
Open your browser and navigate to:
- Local: `http://localhost/koperasi-syariah`
- Development server: `http://localhost:8000`

## ⚙️ Configuration

### Timezone
The application uses `Asia/Jakarta` (WIB) timezone. This can be changed in configuration files if needed.

### File Upload
Maximum file upload size is configured in `php.ini`:
```ini
upload_max_filesize = 2M
post_max_size = 8M
```

### Error Reporting
For development, error reporting is enabled. Disable for production:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 🗄️ Database Schema

### Main Tables

#### 1. `tb_anggota` - Members
Stores member information including NIK, personal details, and login credentials.

#### 2. `tb_petugas` - Staff/Admin
Stores administrator and treasurer accounts with role-based access.

#### 3. `tb_jenis_simpanan` - Savings Types
Defines available savings products with minimum amounts and Akad Wadiah contracts.

#### 4. `tb_simpanan_anggota` - Member Savings Accounts
Links members to their savings accounts with current balances.

#### 5. `tb_transaksi_simpanan` - Savings Transactions
Records all deposit, withdrawal, and profit-sharing transactions.

#### 6. `tb_pembiayaan` - Financing Applications
Stores financing applications with akad type, margin, tenor, and approval status.

#### 7. `tb_angsuran` - Installment Payments
Records all installment payments for active financing.

### Entity Relationships
- One Member can have multiple Savings Accounts
- One Savings Account has many Transactions
- One Member can have multiple Financing applications
- One Financing application has many Installment payments

## 👥 User Roles

### 1. Admin (Full Access)
**Responsibilities:**
- Member management (CRUD operations)
- Savings type configuration
- User account management
- System configuration
- View all reports

**Access:**
- All modules
- Member management
- System settings

### 2. Bendahara / Treasurer (Operational)
**Responsibilities:**
- Process deposit and withdrawal transactions
- Approve or reject financing applications
- Record installment payments
- Generate receipts and invoices
- Manage member accounts

**Access:**
- Transaction processing
- Financing approval
- Payment recording
- Financial reports

### 3. Anggota / Member (Limited)
**Responsibilities:**
- View personal dashboard
- Apply for financing online
- Check account balances
- View payment schedules
- Update personal profile

**Access:**
- Personal dashboard
- Financing application
- Account history
- Profile settings

## 📁 Project Structure

```
koperasi-syariah/
│
├── config/                    # Configuration files
│   └── koneksi.php           # Database connection class
│
├── controllers/               # Application controllers (17 files)
│   ├── Anggota.php          # Member management
│   ├── Angsuran.php         # Installment management
│   ├── Auth.php             # Authentication
│   ├── Dashboard.php        # Dashboard analytics
│   ├── Laporan.php          # Report generation
│   ├── Pembiayaan.php       # Financing management
│   ├── Petugas.php          # Staff management
│   ├── Simpanan.php         # Savings management
│   └── ...                  # Other controllers
│
├── models/                    # Data models (17 files)
│   ├── Anggota_model.php
│   ├── Angsuran_model.php
│   ├── Auth_model.php
│   ├── Laporan_model.php
│   ├── Pembiayaan_model.php
│   ├── Simpanan_model.php
│   └── ...                  # Other models
│
├── views/                     # View templates (organized by module)
│   ├── anggota/             # Member management views
│   ├── angsuran/            # Installment views
│   ├── auth/                # Login/Registration views
│   ├── dashboard/           # Dashboard views
│   ├── laporan/             # Report views
│   ├── pembiayaan/          # Financing views
│   ├── simpanan/            # Savings views
│   └── template/            # Layout templates
│       ├── footer.php
│       ├── header.php
│       ├── sidebar.php
│       └── topbar.php
│
├── template/                  # Main layout components
│   ├── footer.php           # Footer template
│   ├── header.php           # Header template (CSS, meta tags)
│   ├── sidebar.php          # Navigation sidebar
│   └── topbar.php           # Top navigation bar
│
├── assets/                    # Static assets
│   ├── css/                 # Stylesheets
│   ├── js/                  # JavaScript files
│   ├── img/                 # Images and icons
│   ├── vendor/              # Third-party libraries (Bootstrap, jQuery, etc.)
│   └── dist/                # Distributable assets
│
├── database/                  # Database files
│   └── koperasi_syariah.sql # Database schema
│
├── logs/                      # Application logs
│   └── activity_log.txt     # User activity logs
│
├── uploads/                   # User uploaded files
│
├── vendor/                    # Composer dependencies
│   └── tecnickcom/          # TCPDF library
│
├── composer.json              # Composer configuration
├── index.php                  # Application entry point and router
├── .htaccess                  # Apache URL rewriting (if using)
└── README.md                  # This file
```

## 🔐 Default Credentials

After installation, you can log in with these default accounts:

### Admin Account
- **Username:** `admin`
- **Password:** `admin123` (MD5 hashed in database)
- **Role:** Administrator
- **Access:** Full system access

### Treasurer (Bendahara) Account
- **Username:** `bendahara`
- **Password:** `bendahara123` (MD5 hashed in database)
- **Role:** Treasurer
- **Access:** Transaction processing and financing approval

### Demo Member Account
- **Username:** `noval`
- **Member ID:** `ANG-001`
- **Role:** Member
- **Access:** Personal dashboard and financing application

**⚠️ SECURITY WARNING:** Please change all default passwords immediately after first login!

## 📸 Screenshots

### Login Page
Professional login interface with role-based authentication

### Admin Dashboard
Comprehensive overview of cooperative operations with analytics

### Member Management
Complete member data management with CRUD operations

### Savings Transactions
Easy deposit and withdrawal processing

### Financing Application
Member-friendly financing application form

### Reports
Professional PDF reports with official formatting

*(Note: Screenshots will be added in future updates)*

## 🔒 Security Notes

### Current Implementation
- ✅ PDO prepared statements (SQL injection protection)
- ✅ Input sanitization (htmlspecialchars, strip_tags)
- ✅ Session-based authentication
- ✅ Role-based access control
- ✅ Activity logging

### Recommended Improvements
- ⚠️ **Upgrade password hashing from MD5 to bcrypt or Argon2**
- ⚠️ Implement CSRF (Cross-Site Request Forgery) protection
- ⚠️ Add rate limiting for login attempts
- ⚠️ Enforce HTTPS only for production
- ⚠️ Implement password strength requirements
- ⚠️ Add password reset functionality
- ⚠️ Use environment variables for sensitive configuration
- ⚠️ Implement API rate limiting
- ⚠️ Add two-factor authentication (2FA)

### Security Best Practices
1. Change all default passwords immediately
2. Keep PHP and dependencies updated
3. Use HTTPS in production
4. Regularly backup database
5. Monitor activity logs
6. Implement firewall rules
7. Disable error display in production
8. Use parameterized queries (already implemented)

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards
- Write clear, commented code
- Test thoroughly before submitting
- Update documentation as needed
- Respect Sharia compliance in all financial features

## 📝 Todo / Roadmap

### Version 2.0 (Planned)
- [ ] Upgrade password hashing to bcrypt/Argon2
- [ ] Implement CSRF protection
- [ ] Add SMS/Email notifications
- [ ] Mobile-responsive improvements
- [ ] RESTful API development
- [ ] Multi-branch support
- [ ] Advanced analytics and forecasting
- [ ] Barcode/QR code support for receipts
- [ ] Automatic profit distribution system
- [ ] Mobile app (Android/iOS)

### Version 1.5 (In Progress)
- [ ] Password reset functionality
- [ ] Two-factor authentication
- [ ] Advanced search and filtering
- [ ] Export to Excel functionality
- [ ] Email notifications

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Your Name**
- Project Owner & Developer

## 🙏 Acknowledgments

- Bootstrap Team for the excellent UI framework
- TCPDF community for PDF generation
- Indonesian Sharia Cooperative Community for requirements
- All contributors and testers

## 📞 Support

For support, please contact:
- Email: support@koperasi-syariah.com
- Website: https://koperasi-syariah.com
- Documentation: https://docs.koperasi-syariah.com
- Issue Tracker: https://github.com/yourusername/koperasi-syariah/issues

## 📚 References

- [Indonesian Cooperative Law (UU No. 25/1992)](https://www.dpr.go.id/)
- [Sharia Financial Services Board (Bapepam-LK)](https://ojk.go.id/)
- [Islamic Banking Principles](https://www.bankmuslim.or.id/)

---

**Last Updated:** January 2025

**Version:** 1.4.0

⭐ **Star this repository if you find it helpful!**
