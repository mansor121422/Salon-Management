# 🚀 Quick Reference Card - Sprint 1

## Essential Commands

```bash
# Start development server
php spark serve

# Run migrations
php spark migrate

# Rollback migrations
php spark migrate:rollback

# Seed database
php spark db:seed UserSeeder

# Clear cache
php spark cache:clear

# View routes
php spark routes
```

---

## Default Login Credentials

### Receptionist
```
Username: receptionist
Password: receptionist123
```

### Admin
```
Username: admin
Password: admin123
```

---

## Important URLs

| Page | URL |
|------|-----|
| Login | http://localhost:8080/login |
| Dashboard | http://localhost:8080/dashboard |
| New Appointment | http://localhost:8080/appointments/create |
| Logout | http://localhost:8080/logout |

---

## Database Info

```
Database Name: salon_management
Host: localhost
Port: 3306
Username: root
Password: (empty)
```

### Tables
- `users` - Receptionist/Admin accounts
- `appointments` - Customer appointments

---

## File Locations

### Controllers
```
app/Controllers/AuthController.php
app/Controllers/DashboardController.php
app/Controllers/AppointmentController.php
```

### Models
```
app/Models/UserModel.php
app/Models/AppointmentModel.php
```

### Views
```
app/Views/layouts/main.php
app/Views/auth/login.php
app/Views/dashboard/index.php
app/Views/appointments/create.php
app/Views/appointments/confirmation.php
```

### Config
```
app/Config/Routes.php
app/Config/Database.php
```

---

## Appointment Status Flow

```
pending → confirmed → completed
   ↓
cancelled
```

---

## Service Types

1. Haircut
2. Hair Coloring
3. Hair Styling
4. Hair Treatment
5. Manicure
6. Pedicure
7. Facial
8. Makeup

---

## Common Issues & Solutions

### Issue: Can't login
**Solution:** Run `php spark db:seed UserSeeder`

### Issue: Database connection error
**Solution:** 
1. Check MySQL is running
2. Verify database exists: `salon_management`
3. Check credentials in `app/Config/Database.php`

### Issue: Migrations fail
**Solution:**
```bash
php spark migrate:rollback
php spark migrate
```

### Issue: Port 8080 in use
**Solution:** `php spark serve --port=8081`

### Issue: Session not working
**Solution:** Check `writable/session` folder permissions

---

## Development Workflow

1. **Start Server**
   ```bash
   php spark serve
   ```

2. **Make Changes**
   - Edit files in `app/` directory
   - Changes reflect immediately (no restart needed)

3. **Test Changes**
   - Refresh browser
   - Check functionality

4. **Database Changes**
   - Create migration: `php spark make:migration MigrationName`
   - Run migration: `php spark migrate`

---

## Color Scheme

```css
Primary: #667eea (Purple-Blue)
Secondary: #764ba2 (Purple)
Success: #28a745 (Green)
Danger: #dc3545 (Red)
Warning: #ffc107 (Yellow)
Info: #17a2b8 (Cyan)
```

---

## Form Validation Rules

### Customer Name
- Required
- Min: 3 characters
- Max: 255 characters

### Phone Number
- Required
- Min: 10 characters
- Max: 20 characters

### Email
- Optional
- Must be valid email format

### Service Type
- Required
- Must be from predefined list

### Appointment Date
- Required
- Must be valid date
- Cannot be in the past

### Appointment Time
- Required
- Must be valid time format

---

## Session Data Structure

```php
[
    'user_id'   => 1,
    'username'  => 'receptionist',
    'full_name' => 'Receptionist User',
    'role'      => 'receptionist',
    'logged_in' => true
]
```

---

## API Endpoints (Routes)

| Method | Route | Controller | Action |
|--------|-------|------------|--------|
| GET | /login | AuthController | login |
| POST | /login | AuthController | authenticate |
| GET | /logout | AuthController | logout |
| GET | /dashboard | DashboardController | index |
| GET | /appointments/create | AppointmentController | create |
| POST | /appointments/store | AppointmentController | store |
| GET | /appointments/confirmation/:id | AppointmentController | confirmation |
| POST | /appointments/update-status/:id | AppointmentController | updateStatus |

---

## Testing Checklist

- [ ] Login works
- [ ] Dashboard displays
- [ ] Can create appointment
- [ ] Confirmation page shows
- [ ] Can confirm appointment
- [ ] Can cancel appointment
- [ ] Can complete appointment
- [ ] Statistics update correctly
- [ ] Logout works
- [ ] Responsive on mobile

---

## Git Commands (If using version control)

```bash
# Check status
git status

# Add files
git add .

# Commit changes
git commit -m "Sprint 1 complete"

# Push to repository
git push origin main
```

---

## Team Contacts

**Project Leader:** Mansor M. Malik
**Members:**
- Francis Sebastian A. Malilay
- Benz Cal Menguito
- Lawrence Lagsil
- John Raph F. Visayas

---

## Next Sprint Planning

### Potential Features for Sprint 2:
- [ ] Customer-facing QR code website
- [ ] Online appointment request form
- [ ] Real-time availability checker
- [ ] Email notifications
- [ ] SMS notifications
- [ ] Calendar view
- [ ] Service duration tracking
- [ ] Stylist assignment

---

## Performance Benchmarks

**Target Load Times:**
- Login page: < 1s
- Dashboard: < 2s
- Form submission: < 2s

**Database Queries:**
- Should be optimized
- Use indexes where needed
- Avoid N+1 queries

---

## Security Notes

✅ Passwords hashed with bcrypt
✅ CSRF protection enabled
✅ SQL injection prevention (Query Builder)
✅ XSS protection (esc() function)
✅ Session-based authentication

---

## Backup & Recovery

### Backup Database
```bash
mysqldump -u root salon_management > backup.sql
```

### Restore Database
```bash
mysql -u root salon_management < backup.sql
```

---

## Documentation Files

- `README_SPRINT1.md` - Main documentation
- `SETUP_INSTRUCTIONS.md` - Setup guide
- `TESTING_GUIDE.md` - Testing procedures
- `QUICK_REFERENCE.md` - This file
- `database_setup.sql` - Manual DB setup

---

**Last Updated:** Sprint 1 Completion
**Version:** 1.0.0
**Status:** ✅ Production Ready
