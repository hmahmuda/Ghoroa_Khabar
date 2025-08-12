# 🎯 VS Code Setup Guide: Ghoroa Khabar Project

## 📋 **Prerequisites**

### **Required Software:**
- ✅ **XAMPP** (Apache + MySQL + PHP)
- ✅ **VS Code** (Latest version)
- ✅ **Web Browser** (Chrome, Firefox, Edge)

### **System Requirements:**
- Windows 10/11
- PHP 7.4 or higher
- MySQL 5.7 or higher
- At least 2GB RAM

---

## 🚀 **Step-by-Step Setup**

### **Step 1: Install XAMPP**
1. **Download XAMPP** from: https://www.apachefriends.org/
2. **Install XAMPP** in default location: `C:\xampp\`
3. **Start XAMPP Control Panel**
4. **Start Apache and MySQL** services

### **Step 2: Open Project in VS Code**
```bash
# Navigate to project directory
cd C:\xampp\htdocs\Ghoroa_khabar_project

# Open VS Code
code .
```

### **Step 3: Install VS Code Extensions**
Install these extensions for better development experience:

#### **Essential Extensions:**
1. **PHP Intelephense** - PHP language support
2. **PHP Debug** - PHP debugging
3. **MySQL** - MySQL syntax highlighting
4. **HTML CSS Support** - HTML/CSS support
5. **Live Server** - Live preview (optional)
6. **Auto Rename Tag** - HTML tag renaming
7. **Bracket Pair Colorizer** - Code readability
8. **GitLens** - Git integration (if using Git)

#### **Installation Steps:**
1. Open VS Code
2. Press `Ctrl + Shift + X` to open Extensions
3. Search for each extension
4. Click "Install"

---

## ⚙️ **VS Code Configuration**

### **Step 4: Configure PHP Settings**
Create `.vscode/settings.json` in your project:

```json
{
    "php.validate.executablePath": "C:\\xampp\\php\\php.exe",
    "php.suggest.basic": false,
    "php.executablePath": "C:\\xampp\\php\\php.exe",
    "files.associations": {
        "*.php": "php"
    },
    "emmet.includeLanguages": {
        "php": "html"
    }
}
```

### **Step 5: Create Launch Configuration**
Create `.vscode/launch.json` for debugging:

```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/xampp/htdocs/Ghoroa_khabar_project": "${workspaceFolder}"
            }
        }
    ]
}
```

---

## 🗄️ **Database Setup**

### **Step 6: Setup Database**
1. **Open XAMPP Control Panel**
2. **Click "Admin" next to MySQL**
3. **Or go to:** http://localhost/phpmyadmin
4. **Run the setup script:**

```bash
# In your browser, go to:
http://localhost/Ghoroa_khabar_project/setup_database.php
```

5. **Setup Delivery System:**
```bash
# In your browser, go to:
http://localhost/Ghoroa_khabar_project/setup_delivery_system.php
```

---

## 🌐 **Running the Project**

### **Step 7: Start the Application**
1. **Ensure XAMPP is running** (Apache + MySQL)
2. **Open your browser**
3. **Navigate to:** `http://localhost/Ghoroa_khabar_project/`

### **Step 8: Test the Application**
1. **Home Page:** `http://localhost/Ghoroa_khabar_project/home.php`
2. **Login:** `http://localhost/Ghoroa_khabar_project/login.html`
3. **Admin Dashboard:** Login with admin credentials

---

## 🔑 **Default Credentials**

### **Admin Login:**
- **Email:** admin@ghoroa.com
- **Password:** admin123

### **Customer Login:**
- **Email:** customer@ghoroa.com
- **Password:** customer123

---

## 📁 **Project Structure in VS Code**

### **File Organization:**
```
Ghoroa_khabar_project/
├── 📁 .vscode/                    # VS Code settings
├── 📁 css/                        # Stylesheets
├── 📁 images/                     # Food images
├── 📄 db.php                      # Database connection
├── 📄 login.php                   # Login processing
├── 📄 register.php                # User registration
├── 📄 home.php                    # Main page
├── 📄 foods.php                   # Food display
├── 📄 order.php                   # Order processing
├── 📄 payment.php                 # Payment handling
├── 📄 admin_dashboard.php         # Admin panel
├── 📄 admin_delivery.php          # Delivery management
├── 📄 setup_database.php          # Database setup
├── 📄 setup_delivery_system.php   # Delivery setup
└── 📄 details.md                  # Project documentation
```

---

## 🛠️ **VS Code Features for Development**

### **Code Navigation:**
- **Ctrl + P:** Quick file search
- **Ctrl + Shift + P:** Command palette
- **F12:** Go to definition
- **Alt + F12:** Peek definition
- **Ctrl + G:** Go to line

### **PHP Development:**
- **IntelliSense:** Auto-completion for PHP
- **Error Detection:** Real-time error highlighting
- **Debugging:** Set breakpoints and debug
- **Formatting:** Auto-format PHP code

### **Database Integration:**
- **MySQL Extension:** Syntax highlighting
- **Query Execution:** Run SQL queries
- **Database Explorer:** Browse tables

---

## 🔧 **Troubleshooting**

### **Common Issues:**

#### **1. XAMPP Not Starting:**
- Check if ports 80 and 3306 are free
- Run XAMPP as Administrator
- Check Windows Firewall settings

#### **2. Database Connection Error:**
- Ensure MySQL is running in XAMPP
- Check database credentials in `db.php`
- Verify database exists

#### **3. PHP Errors:**
- Check PHP version compatibility
- Enable error reporting in `php.ini`
- Check file permissions

#### **4. Page Not Found:**
- Ensure files are in correct directory
- Check Apache configuration
- Verify file names and extensions

---

## 🎯 **Development Workflow**

### **Daily Development:**
1. **Start XAMPP** (Apache + MySQL)
2. **Open VS Code** with project
3. **Make code changes**
4. **Save files** (Ctrl + S)
5. **Refresh browser** to see changes
6. **Test functionality**

### **Testing Features:**
1. **User Registration/Login**
2. **Food Browsing**
3. **Order Placement**
4. **Payment Processing**
5. **Admin Dashboard**
6. **Delivery Management**

---

## 📊 **Project URLs**

### **Main Pages:**
- **Home:** `http://localhost/Ghoroa_khabar_project/home.php`
- **Login:** `http://localhost/Ghoroa_khabar_project/login.html`
- **Register:** `http://localhost/Ghoroa_khabar_project/register.php`
- **Foods:** `http://localhost/Ghoroa_khabar_project/foods.php`
- **Categories:** `http://localhost/Ghoroa_khabar_project/categories.html`

### **Admin Pages:**
- **Dashboard:** `http://localhost/Ghoroa_khabar_project/admin_dashboard.php`
- **Delivery:** `http://localhost/Ghoroa_khabar_project/admin_delivery.php`
- **Setup:** `http://localhost/Ghoroa_khabar_project/setup_delivery_system.php`

### **Utility Pages:**
- **Database Setup:** `http://localhost/Ghoroa_khabar_project/setup_database.php`
- **Status Check:** `http://localhost/Ghoroa_khabar_project/check_delivery_status.php`
- **Debug:** `http://localhost/Ghoroa_khabar_project/debug_orders.php`

---

## 🎓 **Presentation Tips**

### **Demo Flow:**
1. **Show VS Code setup** and file structure
2. **Demonstrate database** in phpMyAdmin
3. **Walk through user journey:**
   - Registration → Login → Browse → Order → Payment
4. **Show admin features:**
   - Dashboard → Order management → Delivery assignment
5. **Highlight technical features:**
   - Security, responsive design, local adaptation

### **Key Points to Emphasize:**
- ✅ **Full-stack development** with PHP/MySQL
- ✅ **Security implementation** (prepared statements, password hashing)
- ✅ **Local market adaptation** (Sylhet Sadar areas)
- ✅ **Complete delivery system** with tracking
- ✅ **Responsive design** for mobile users
- ✅ **Admin dashboard** for business management

---

## 🚀 **Quick Start Commands**

### **Terminal Commands:**
```bash
# Navigate to project
cd C:\xampp\htdocs\Ghoroa_khabar_project

# Open VS Code
code .

# Start XAMPP (if not already running)
# Open XAMPP Control Panel and start Apache + MySQL

# Access project
# Open browser: http://localhost/Ghoroa_khabar_project/
```

---

## 📝 **Notes for Development**

### **File Permissions:**
- Ensure PHP files are readable by web server
- Check write permissions for uploads (if any)
- Verify database connection permissions

### **Backup Strategy:**
- Regular database backups
- Code version control (Git recommended)
- Configuration file backups

### **Performance Tips:**
- Enable PHP OPcache
- Optimize database queries
- Compress images
- Use browser caching

---

**🎉 Your Ghoroa Khabar project is now ready for development in VS Code!**

**Next Steps:**
1. Follow the setup guide above
2. Test all features
3. Prepare your presentation
4. Practice demonstrating the system

**Good luck with your presentation! 🎓** 