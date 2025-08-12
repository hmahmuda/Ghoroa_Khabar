# Ghoroa Khabar - Food Delivery System

A secure PHP-based food delivery system with user authentication, order management, and payment processing.

## 🚀 Features

- **User Authentication**: Secure login/registration with password hashing
- **Food Management**: Browse and search food items
- **Order System**: Place orders with quantity selection
- **Payment Processing**: Multiple payment methods (Cash, bKash, Online)
- **Admin Dashboard**: Manage orders and view statistics
- **Responsive Design**: Mobile-friendly interface

## 🔒 Security Improvements Made

### 1. SQL Injection Prevention
- ✅ Replaced all direct SQL queries with prepared statements
- ✅ Added input sanitization and validation
- ✅ Implemented proper parameter binding

### 2. Authentication & Authorization
- ✅ Secure password hashing using `password_hash()`
- ✅ Session management with proper validation
- ✅ Role-based access control (Customer/Admin)
- ✅ Secure logout functionality

### 3. Input Validation
- ✅ Email validation using `filter_var()`
- ✅ Input sanitization with `htmlspecialchars()`
- ✅ Data type validation and casting
- ✅ Length and format validation

### 4. Error Handling
- ✅ Comprehensive error handling with try-catch blocks
- ✅ User-friendly error messages
- ✅ Database connection error handling
- ✅ Graceful failure handling

## 📋 Setup Instructions

### Prerequisites
- XAMPP (Apache + MySQL + PHP)
- PHP 7.4 or higher
- MySQL 5.7 or higher

### Installation Steps

1. **Clone/Download the project**
   ```bash
   # Place the project in your XAMPP htdocs folder
   C:\xampp\htdocs\Ghoroa_khabar_project\
   ```

2. **Set up the database**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `ghoroa_khabar`
   - Import the `database_schema.sql` file or run the SQL commands manually

3. **Configure database connection**
   - Edit `db.php` if needed (default settings work with XAMPP)
   - Database: `ghoroa_khabar`
   - Username: `root`
   - Password: `` (empty for XAMPP)

4. **Start the application**
   - Start XAMPP (Apache + MySQL)
   - Visit: http://localhost/Ghoroa_khabar_project/

## 👥 Default Users

### Admin User
- **Email**: admin@ghoroa.com
- **Password**: admin123
- **Role**: Admin

### Customer User
- **Email**: john@example.com
- **Password**: customer123
- **Role**: Customer

## 📁 File Structure

```
Ghoroa_khabar_project/
├── css/
│   └── style.css
├── images/
│   ├── logo.png
│   └── food-images/
├── db.php                          # Database connection & helpers
├── login.php                       # User login
├── register.php                    # User registration
├── logout.php                      # User logout
├── foods.php                       # Display all foods
├── food-search.php                 # Search functionality
├── order.php                       # Order processing
├── payment.php                     # Payment processing
├── admin_dashboard.php             # Admin panel
├── database_schema.sql             # Database structure
├── README.md                       # This file
└── HTML files (home.html, etc.)
```

## 🔧 Database Schema

### Tables
1. **users** - User accounts and authentication
2. **categories** - Food categories
3. **dishes** - Food items with prices
4. **orders** - Customer orders
5. **payments** - Payment records

### Key Features
- Foreign key constraints for data integrity
- Proper indexing for performance
- Timestamp fields for tracking
- Enum fields for status management

## 🛡️ Security Features

### Authentication
- Password hashing with `password_hash()`
- Session-based authentication
- Role-based access control
- Secure logout with session destruction

### Data Protection
- Prepared statements prevent SQL injection
- Input sanitization prevents XSS
- Email validation
- Data type validation

### Session Security
- Session timeout handling
- Secure session management
- CSRF protection (basic implementation)

## 🚀 Usage Guide

### For Customers
1. **Register/Login**: Create account or login
2. **Browse Foods**: View available dishes
3. **Search**: Use search functionality
4. **Place Order**: Select quantity and order
5. **Make Payment**: Choose payment method
6. **Track Order**: View order status

### For Admins
1. **Login**: Use admin credentials
2. **Dashboard**: View order statistics
3. **Manage Orders**: Update order status
4. **View Reports**: Monitor revenue and orders

## 🔧 Customization

### Adding New Food Items
1. Add dish to `dishes` table
2. Upload image to `images/` folder
3. Update category if needed

### Modifying Payment Methods
Edit the payment options in `payment.php`:
```php
$valid_methods = ['cash', 'bkash', 'online', 'card'];
```

### Styling Changes
Modify `css/style.css` for design changes

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check XAMPP is running
   - Verify database name in `db.php`
   - Ensure MySQL service is active

2. **Login Issues**
   - Check if user exists in database
   - Verify password is correct
   - Clear browser cookies/sessions

3. **Image Not Loading**
   - Check image file exists in `images/` folder
   - Verify file permissions
   - Check image path in database

4. **Order Not Processing**
   - Check user is logged in
   - Verify dish exists in database
   - Check database connection

## 📞 Support

For issues or questions:
1. Check the troubleshooting section
2. Verify database setup
3. Check error logs in XAMPP
4. Ensure all files are properly uploaded

## 🔄 Updates & Maintenance

### Regular Maintenance
- Backup database regularly
- Update PHP version when needed
- Monitor error logs
- Review security practices

### Future Enhancements
- Email notifications
- SMS integration
- Advanced reporting
- Mobile app development
- Payment gateway integration

---
 




