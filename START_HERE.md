# 🚀 START HERE - Sprint 1 Setup

## Welcome to the Salon Management System!

This guide will get you up and running in **5 minutes**.

---

## ⚡ Quick Start (5 Steps)

### Step 1: Check Prerequisites ✓
Make sure you have:
- [ ] XAMPP installed and running
- [ ] MySQL running (green light in XAMPP)
- [ ] Apache running (green light in XAMPP)
- [ ] PHP 8.2+ (check with `php -v`)

### Step 2: Create Database 🗄️
Open phpMyAdmin: http://localhost/phpmyadmin

Click "New" and create database:
```
Database name: salon_management
Collation: utf8mb4_general_ci
```

Or run this SQL:
```sql
CREATE DATABASE salon_management;
```

### Step 3: Run Migrations 📊
Open terminal in project folder:
```bash
cd "C:\xampp\htdocs\Chanelle Salon Management System"
php spark migrate
```

You should see:
```
✓ Running: 2024-01-01-000001_CreateUsersTable
✓ Running: 2024-01-01-000002_CreateAppointmentsTable
```

### Step 4: Create Default Users 👤
Still in terminal:
```bash
php spark db:seed UserSeeder
```

You should see:
```
✓ Seeded: UserSeeder
```

### Step 5: Start Server 🌐
```bash
php spark serve
```

You should see:
```
CodeIgniter development server started on http://localhost:8080
```

---

## 🎉 You're Ready!

Open your browser and go to:
**http://localhost:8080/login**

### Login Credentials:
```
Username: receptionist
Password: receptionist123
```

---

## 📋 What to Do Next

1. **Login** to the system
2. **Explore the Dashboard** - see the statistics and empty appointment list
3. **Create Your First Appointment**:
   - Click "New Appointment"
   - Fill in the form:
     - Name: "Test Customer"
     - Phone: "09171234567"
     - Service: "Haircut"
     - Date: Tomorrow
     - Time: "10:00"
   - Click "Create Appointment"
4. **View Confirmation** - see the success page
5. **Manage Appointments** - go back to dashboard and try:
   - Confirming the appointment
   - Creating more appointments
   - Completing appointments

---

## 🆘 Troubleshooting

### Problem: "Database connection failed"
**Solution:**
1. Check MySQL is running in XAMPP
2. Verify database `salon_management` exists
3. Check username is `root` with empty password

### Problem: "Table not found"
**Solution:**
```bash
php spark migrate
```

### Problem: "Can't login"
**Solution:**
```bash
php spark db:seed UserSeeder
```

### Problem: "Port 8080 already in use"
**Solution:**
```bash
php spark serve --port=8081
```
Then use: http://localhost:8081/login

### Problem: "Migration already ran"
**Solution:**
```bash
php spark migrate:rollback
php spark migrate
```

---

## 📚 Documentation Files

Once you're up and running, check these files:

1. **README_SPRINT1.md** - Complete project overview
2. **SETUP_INSTRUCTIONS.md** - Detailed setup guide
3. **TESTING_GUIDE.md** - How to test all features
4. **QUICK_REFERENCE.md** - Quick commands and info
5. **SPRINT1_SUMMARY.md** - What was built in Sprint 1

---

## 🎯 Quick Test

After setup, verify everything works:

- [ ] Can login with receptionist/receptionist123
- [ ] Dashboard loads and shows statistics
- [ ] Can click "New Appointment"
- [ ] Can fill and submit appointment form
- [ ] Confirmation page appears
- [ ] Appointment appears in dashboard
- [ ] Can confirm/cancel appointments
- [ ] Can logout

If all checked ✓ - **You're good to go!** 🎉

---

## 🎨 Features Overview

### What You Can Do:
✅ **Login** - Secure authentication for receptionist
✅ **Dashboard** - View all appointments and statistics
✅ **Create Appointments** - Book new customer appointments
✅ **Manage Status** - Confirm, complete, or cancel appointments
✅ **View Details** - See complete appointment information

### Services Available:
- Haircut
- Hair Coloring
- Hair Styling
- Hair Treatment
- Manicure
- Pedicure
- Facial
- Makeup

---

## 👥 Team

**Uniteam:**
- Leader: Mansor M. Malik
- Francis Sebastian A. Malilay
- Benz Cal Menguito
- Lawrence Lagsil
- John Raph F. Visayas

---

## 🎬 Demo Mode

Want to show this to someone? Here's a quick demo flow:

1. **Login** (receptionist/receptionist123)
2. **Show empty dashboard** - explain the statistics
3. **Create appointment** - fill form with sample data
4. **Show confirmation** - point out the details
5. **Return to dashboard** - show the new appointment
6. **Confirm it** - demonstrate status change
7. **Create 2 more** - show multiple appointments
8. **Manage them** - confirm one, cancel one

**Demo time: 5 minutes**

---

## 📞 Need Help?

1. Check the troubleshooting section above
2. Review `SETUP_INSTRUCTIONS.md`
3. Look at `QUICK_REFERENCE.md`
4. Contact team leader

---

## ✅ Setup Checklist

Before you start developing or demoing:

- [ ] XAMPP running
- [ ] Database created
- [ ] Migrations run
- [ ] Users seeded
- [ ] Server started
- [ ] Can access login page
- [ ] Can login successfully
- [ ] Dashboard loads
- [ ] Can create appointment
- [ ] All features working

---

## 🚀 Ready to Code?

If you want to modify or extend the system:

### Key Files to Know:
```
Controllers: app/Controllers/
Models: app/Models/
Views: app/Views/
Routes: app/Config/Routes.php
Database: app/Config/Database.php
```

### Common Tasks:
```bash
# Create new controller
php spark make:controller ControllerName

# Create new model
php spark make:model ModelName

# Create new migration
php spark make:migration MigrationName

# View all routes
php spark routes

# Clear cache
php spark cache:clear
```

---

## 🎓 Learning Resources

- **CodeIgniter 4 Docs:** https://codeigniter.com/user_guide/
- **PHP Manual:** https://www.php.net/manual/
- **MySQL Docs:** https://dev.mysql.com/doc/

---

## 🎉 Congratulations!

You've successfully set up Sprint 1 of the Salon Management System!

**What's Next?**
- Test all features
- Show it to your client
- Plan Sprint 2 features
- Gather feedback

---

**Happy Coding! 💻**

*Last Updated: Sprint 1 Completion*
