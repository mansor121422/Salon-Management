# 💇 Local Salon Management System - Sprint 1

## Project Overview

A web-based salon management system designed for local beauty salons with a receptionist-led booking workflow. This Sprint 1 implementation includes core features for appointment scheduling and management.

### Team: Uniteam
- **Leader:** Mansor M. Malik
- Francis Sebastian A. Malilay
- Benz Cal Menguito
- Lawrence Lagsil
- John Raph F. Visayas

---

## ✨ Sprint 1 Features

### ✅ Completed Features

1. **Authentication System**
   - Secure login for receptionist
   - Session management
   - Role-based access (receptionist/admin)

2. **Appointment Creation**
   - Comprehensive booking form
   - Customer information capture
   - Service type selection
   - Date and time scheduling
   - Optional notes field

3. **Appointment Confirmation**
   - Success confirmation page
   - Complete appointment details display
   - Unique appointment ID generation
   - Quick action buttons

4. **Dashboard**
   - Real-time statistics
   - Appointment list view
   - Status management (Pending, Confirmed, Completed, Cancelled)
   - Quick action buttons for status updates

5. **Professional UI/UX**
   - Modern gradient design
   - Responsive layout
   - Smooth animations
   - Intuitive navigation
   - Mobile-friendly interface

---

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 or higher
- MySQL 5.7 or higher
- XAMPP/WAMP/LAMP or similar
- Composer (optional, already included in CodeIgniter 4)

### Installation Steps

1. **Clone or extract the project**
   ```bash
   cd C:\xampp\htdocs\Chanelle Salon Management System
   ```

2. **Create the database**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create database: `salon_management`
   - Or run: `database_setup.sql`

3. **Configure database** (already configured)
   - File: `app/Config/Database.php`
   - Database: `salon_management`
   - Username: `root`
   - Password: `` (empty)

4. **Run migrations**
   ```bash
   php spark migrate
   ```

5. **Seed default users**
   ```bash
   php spark db:seed UserSeeder
   ```

6. **Start the server**
   ```bash
   php spark serve
   ```

7. **Access the application**
   - URL: http://localhost:8080/login
   - Username: `receptionist`
   - Password: `receptionist123`

---

## 📱 How to Use

### For Receptionist

1. **Login**
   - Navigate to the login page
   - Enter credentials
   - Access the dashboard

2. **View Dashboard**
   - See appointment statistics
   - View all appointments in table format
   - Check pending, confirmed, and completed counts

3. **Create New Appointment**
   - Click "New Appointment" button
   - Fill in customer details:
     - Name (required)
     - Phone number (required)
     - Email (optional)
   - Select service type from dropdown
   - Choose date and time
   - Add optional notes
   - Submit the form

4. **Manage Appointments**
   - **Pending appointments:** Confirm or Cancel
   - **Confirmed appointments:** Mark as Completed
   - View all appointment details in the table

5. **Confirmation Page**
   - Review appointment details
   - See unique appointment ID
   - Create another appointment or return to dashboard

---

## 🎨 Service Types Available

- Haircut
- Hair Coloring
- Hair Styling
- Hair Treatment
- Manicure
- Pedicure
- Facial
- Makeup

---

## 📊 Database Structure

### Users Table
```sql
- id (Primary Key)
- username (Unique)
- password (Hashed with bcrypt)
- full_name
- role (receptionist/admin)
- created_at, updated_at
```

### Appointments Table
```sql
- id (Primary Key)
- customer_name
- customer_phone
- customer_email (nullable)
- service_type
- appointment_date
- appointment_time
- status (pending/confirmed/completed/cancelled)
- notes (nullable)
- created_at, updated_at
```

---

## 🔐 Default Accounts

### Receptionist Account
- **Username:** receptionist
- **Password:** receptionist123
- **Role:** receptionist

### Admin Account
- **Username:** admin
- **Password:** admin123
- **Role:** admin

---

## 📁 Project Structure

```
app/
├── Controllers/
│   ├── AuthController.php          # Authentication logic
│   ├── AppointmentController.php   # Appointment CRUD operations
│   └── DashboardController.php     # Dashboard display
├── Models/
│   ├── UserModel.php               # User database operations
│   └── AppointmentModel.php        # Appointment database operations
├── Views/
│   ├── layouts/
│   │   └── main.php                # Main layout with navbar & footer
│   ├── auth/
│   │   └── login.php               # Login page
│   ├── dashboard/
│   │   └── index.php               # Dashboard with statistics
│   └── appointments/
│       ├── create.php              # Appointment creation form
│       └── confirmation.php        # Success confirmation page
├── Database/
│   ├── Migrations/                 # Database table definitions
│   └── Seeds/                      # Default data seeders
└── Config/
    ├── Routes.php                  # URL routing
    └── Database.php                # Database configuration
```

---

## 🎯 Sprint 1 Objectives - COMPLETED ✅

- [x] User authentication (login/logout)
- [x] Appointment creation form
- [x] Form validation
- [x] Appointment submission to database
- [x] Confirmation page with details
- [x] Dashboard with appointment list
- [x] Status management system
- [x] Professional CSS styling
- [x] Responsive design
- [x] Database migrations
- [x] Seed data for testing

---

## 🔧 Troubleshooting

### Database Connection Error
```bash
# Check MySQL is running
# Verify credentials in app/Config/Database.php
# Ensure database 'salon_management' exists
```

### Migration Issues
```bash
# Rollback migrations
php spark migrate:rollback

# Run migrations again
php spark migrate
```

### Login Not Working
```bash
# Re-run the seeder
php spark db:seed UserSeeder
```

### Port Already in Use
```bash
# Use different port
php spark serve --port=8081
```

---

## 🚀 Future Enhancements (Next Sprints)

### Sprint 2 Planned Features:
- Customer-facing QR code website
- Online appointment requests
- Real-time schedule availability checker
- Social media integration tracking

### Sprint 3+ Ideas:
- SMS/Email notifications
- Calendar view
- Service duration management
- Stylist assignment
- Customer history
- Reporting and analytics
- Payment tracking
- Inventory management

---

## 📸 Screenshots

### Login Page
- Clean, modern design with gradient background
- Demo credentials displayed
- Secure authentication

### Dashboard
- Statistics cards (Total, Pending, Confirmed, Completed)
- Comprehensive appointment table
- Quick action buttons
- Responsive layout

### Create Appointment
- User-friendly form
- Service dropdown
- Date and time pickers
- Validation feedback

### Confirmation Page
- Success animation
- Complete appointment details
- Status badge
- Quick navigation options

---

## 🤝 Contributing

This is an academic project for the Uniteam group. For questions or suggestions, contact the team members.

---

## 📄 License

This project is developed for educational purposes as part of a system analysis and design course.

---

## 📞 Support

For technical issues or questions:
- Contact: Team Uniteam
- Project Leader: Mansor M. Malik

---

**Sprint 1 Status: ✅ COMPLETE**

All core features implemented and tested. Ready for demonstration and Sprint 2 planning.
