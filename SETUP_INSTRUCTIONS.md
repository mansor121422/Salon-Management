# Sprint 1 - Salon Management System Setup Instructions

## Overview
This is Sprint 1 of the Local Salon Management System with receptionist-led booking workflow.

### Features Included:
✅ Login System (Receptionist Authentication)
✅ Appointment Creation Form
✅ Appointment Submission & Validation
✅ Confirmation Page with Details
✅ Dashboard with Appointment List
✅ Status Management (Pending, Confirmed, Completed, Cancelled)
✅ Professional CSS Styling
✅ Responsive Design

---

## Setup Instructions

### 1. Database Setup

Create the database in MySQL:
```sql
CREATE DATABASE salon_management;
```

### 2. Update Database Configuration

The database configuration is already set in `app/Config/Database.php`:
- Database: `salon_management`
- Username: `root`
- Password: `` (empty - adjust if needed)

If your MySQL has a different username/password, update:
```php
'username' => 'your_username',
'password' => 'your_password',
```

### 3. Run Migrations

Open terminal in your project directory and run:
```bash
php spark migrate
```

This will create the `users` and `appointments` tables.

### 4. Seed Default Users

Run the seeder to create default login accounts:
```bash
php spark db:seed UserSeeder
```

This creates:
- **Receptionist Account**
  - Username: `receptionist`
  - Password: `receptionist123`
  
- **Admin Account**
  - Username: `admin`
  - Password: `admin123`

### 5. Start the Development Server

```bash
php spark serve
```

The application will be available at: `http://localhost:8080`

---

## How to Use

### 1. Login
- Navigate to `http://localhost:8080/login`
- Use the receptionist credentials:
  - Username: `receptionist`
  - Password: `receptionist123`

### 2. Dashboard
- After login, you'll see the dashboard with:
  - Statistics (Total, Pending, Confirmed, Completed appointments)
  - List of all appointments
  - Quick actions to confirm/cancel appointments

### 3. Create Appointment
- Click "New Appointment" button
- Fill in the form:
  - Customer Name (required)
  - Phone Number (required)
  - Email (optional)
  - Service Type (dropdown with salon services)
  - Appointment Date (required)
  - Appointment Time (required)
  - Additional Notes (optional)
- Click "Create Appointment"

### 4. Confirmation Page
- After creating an appointment, you'll see a confirmation page with:
  - Success message
  - Complete appointment details
  - Appointment ID
  - Current status
  - Options to view all appointments or create another

### 5. Manage Appointments
- From the dashboard, you can:
  - View all appointments in a table
  - Confirm pending appointments
  - Cancel appointments
  - Mark confirmed appointments as completed

---

## Project Structure

```
app/
├── Controllers/
│   ├── AuthController.php          # Login/Logout
│   ├── AppointmentController.php   # Appointment CRUD
│   └── DashboardController.php     # Dashboard view
├── Models/
│   ├── UserModel.php               # User data
│   └── AppointmentModel.php        # Appointment data
├── Views/
│   ├── layouts/
│   │   └── main.php                # Main layout template
│   ├── auth/
│   │   └── login.php               # Login page
│   ├── dashboard/
│   │   └── index.php               # Dashboard
│   └── appointments/
│       ├── create.php              # Create appointment form
│       └── confirmation.php        # Confirmation page
├── Database/
│   ├── Migrations/
│   │   ├── 2024-01-01-000001_CreateUsersTable.php
│   │   └── 2024-01-01-000002_CreateAppointmentsTable.php
│   └── Seeds/
│       └── UserSeeder.php          # Default users
└── Config/
    ├── Routes.php                  # Application routes
    └── Database.php                # Database config
```

---

## Database Schema

### Users Table
- id (Primary Key)
- username (Unique)
- password (Hashed)
- full_name
- role (receptionist/admin)
- created_at
- updated_at

### Appointments Table
- id (Primary Key)
- customer_name
- customer_phone
- customer_email
- service_type
- appointment_date
- appointment_time
- status (pending/confirmed/completed/cancelled)
- notes
- created_at
- updated_at

---

## Available Services

The system includes these service types:
- Haircut
- Hair Coloring
- Hair Styling
- Hair Treatment
- Manicure
- Pedicure
- Facial
- Makeup

---

## Troubleshooting

### Database Connection Error
- Check if MySQL is running
- Verify database credentials in `app/Config/Database.php`
- Ensure `salon_management` database exists

### Migration Errors
- Drop existing tables if needed: `php spark migrate:rollback`
- Run migrations again: `php spark migrate`

### Login Not Working
- Ensure UserSeeder was run: `php spark db:seed UserSeeder`
- Check if users exist in database

### Port Already in Use
- Use a different port: `php spark serve --port=8081`

---

## Next Steps (Future Sprints)

Sprint 2 could include:
- Customer-facing QR code website
- Real-time schedule availability
- SMS/Email notifications
- Calendar view
- Service duration management
- Stylist assignment
- Reporting and analytics

---

## Team Information

**Project:** Local Salon Management System
**Team:** Uniteam
- Leader: Mansor M. Malik
- Francis Sebastian A. Malilay
- Benz Cal Menguito
- Lawrence Lagsil
- John Raph F. Visayas

---

## Support

For issues or questions, contact the development team.

**Sprint 1 Complete! ✅**
