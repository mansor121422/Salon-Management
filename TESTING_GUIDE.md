# Sprint 1 Testing Guide

## Pre-Testing Checklist

Before testing, ensure:
- [ ] MySQL is running
- [ ] Database `salon_management` is created
- [ ] Migrations have been run: `php spark migrate`
- [ ] Users have been seeded: `php spark db:seed UserSeeder`
- [ ] Development server is running: `php spark serve`

---

## Test Cases

### 1. Authentication Tests

#### Test 1.1: Login with Valid Credentials
**Steps:**
1. Navigate to `http://localhost:8080/login`
2. Enter username: `receptionist`
3. Enter password: `receptionist123`
4. Click "Login"

**Expected Result:**
- ✅ Redirected to dashboard
- ✅ Welcome message shows "Welcome, Receptionist User!"
- ✅ Navbar shows user name and logout button

#### Test 1.2: Login with Invalid Credentials
**Steps:**
1. Navigate to `http://localhost:8080/login`
2. Enter username: `wronguser`
3. Enter password: `wrongpass`
4. Click "Login"

**Expected Result:**
- ✅ Error message displayed: "Username not found"
- ✅ Remains on login page

#### Test 1.3: Logout
**Steps:**
1. Login successfully
2. Click "Logout" button in navbar

**Expected Result:**
- ✅ Redirected to login page
- ✅ Session destroyed
- ✅ Cannot access dashboard without logging in again

---

### 2. Dashboard Tests

#### Test 2.1: View Dashboard
**Steps:**
1. Login as receptionist
2. View dashboard

**Expected Result:**
- ✅ Statistics cards display correct counts
- ✅ Appointments table shows all appointments
- ✅ "New Appointment" button is visible
- ✅ Status badges display correctly

#### Test 2.2: Dashboard Without Login
**Steps:**
1. Logout if logged in
2. Try to access `http://localhost:8080/dashboard` directly

**Expected Result:**
- ✅ Redirected to login page
- ✅ Cannot access dashboard without authentication

---

### 3. Appointment Creation Tests

#### Test 3.1: Create Valid Appointment
**Steps:**
1. Login as receptionist
2. Click "New Appointment"
3. Fill in form:
   - Customer Name: "Test Customer"
   - Phone: "09171234567"
   - Email: "test@email.com"
   - Service: "Haircut"
   - Date: Tomorrow's date
   - Time: "10:00"
   - Notes: "Test appointment"
4. Click "Create Appointment"

**Expected Result:**
- ✅ Redirected to confirmation page
- ✅ Success message displayed
- ✅ All details shown correctly
- ✅ Status is "Pending"
- ✅ Unique appointment ID generated

#### Test 3.2: Create Appointment with Missing Required Fields
**Steps:**
1. Click "New Appointment"
2. Leave Customer Name empty
3. Fill other fields
4. Try to submit

**Expected Result:**
- ✅ Browser validation prevents submission
- ✅ "Please fill out this field" message appears

#### Test 3.3: Create Appointment Without Email (Optional Field)
**Steps:**
1. Click "New Appointment"
2. Fill all required fields
3. Leave email empty
4. Submit form

**Expected Result:**
- ✅ Appointment created successfully
- ✅ Email field shows as empty in confirmation
- ✅ No errors occur

#### Test 3.4: Date Validation
**Steps:**
1. Click "New Appointment"
2. Try to select a past date

**Expected Result:**
- ✅ Past dates are disabled/not selectable
- ✅ Minimum date is today

---

### 4. Appointment Management Tests

#### Test 4.1: Confirm Pending Appointment
**Steps:**
1. View dashboard with pending appointments
2. Click "Confirm" button on a pending appointment

**Expected Result:**
- ✅ Status changes to "Confirmed"
- ✅ Success message displayed
- ✅ Statistics updated
- ✅ "Complete" button now available

#### Test 4.2: Cancel Pending Appointment
**Steps:**
1. View dashboard with pending appointments
2. Click "Cancel" button on a pending appointment

**Expected Result:**
- ✅ Status changes to "Cancelled"
- ✅ Success message displayed
- ✅ Statistics updated
- ✅ No action buttons available

#### Test 4.3: Complete Confirmed Appointment
**Steps:**
1. View dashboard with confirmed appointments
2. Click "Complete" button on a confirmed appointment

**Expected Result:**
- ✅ Status changes to "Completed"
- ✅ Success message displayed
- ✅ Statistics updated
- ✅ No action buttons available

---

### 5. UI/UX Tests

#### Test 5.1: Responsive Design - Mobile View
**Steps:**
1. Open browser developer tools (F12)
2. Toggle device toolbar (mobile view)
3. Navigate through all pages

**Expected Result:**
- ✅ All pages display correctly on mobile
- ✅ Forms are usable
- ✅ Tables are readable
- ✅ Buttons are clickable

#### Test 5.2: Navigation
**Steps:**
1. Login and test all navigation links
2. Click navbar brand
3. Click menu items

**Expected Result:**
- ✅ All links work correctly
- ✅ Active page is indicated
- ✅ No broken links

#### Test 5.3: Animations and Transitions
**Steps:**
1. Observe page load animations
2. Hover over buttons
3. Submit forms

**Expected Result:**
- ✅ Smooth fade-in animations
- ✅ Button hover effects work
- ✅ No jarring transitions

---

### 6. Data Validation Tests

#### Test 6.1: Phone Number Format
**Steps:**
1. Create appointment with various phone formats:
   - "09171234567"
   - "0917-123-4567"
   - "+639171234567"

**Expected Result:**
- ✅ All formats accepted
- ✅ Stored correctly in database

#### Test 6.2: Service Type Selection
**Steps:**
1. Create appointment
2. Try each service type from dropdown

**Expected Result:**
- ✅ All 8 service types available
- ✅ Selection saved correctly

---

### 7. Session Management Tests

#### Test 7.1: Session Persistence
**Steps:**
1. Login
2. Navigate to different pages
3. Refresh browser

**Expected Result:**
- ✅ Remains logged in
- ✅ User data persists
- ✅ No re-login required

#### Test 7.2: Multiple Tab Behavior
**Steps:**
1. Login in one tab
2. Open new tab
3. Navigate to dashboard

**Expected Result:**
- ✅ Already logged in
- ✅ Session shared across tabs

---

### 8. Database Tests

#### Test 8.1: Data Persistence
**Steps:**
1. Create multiple appointments
2. Logout
3. Login again
4. Check dashboard

**Expected Result:**
- ✅ All appointments still visible
- ✅ Data persists correctly
- ✅ Correct order (by date/time)

#### Test 8.2: Timestamp Recording
**Steps:**
1. Create appointment
2. Check database directly

**Expected Result:**
- ✅ created_at timestamp recorded
- ✅ updated_at timestamp recorded
- ✅ Timestamps in correct format

---

## Performance Tests

### Test 9.1: Page Load Speed
**Expected:**
- Login page: < 1 second
- Dashboard: < 2 seconds
- Create form: < 1 second

### Test 9.2: Form Submission Speed
**Expected:**
- Appointment creation: < 2 seconds
- Status update: < 1 second

---

## Browser Compatibility

Test on:
- [ ] Google Chrome (latest)
- [ ] Mozilla Firefox (latest)
- [ ] Microsoft Edge (latest)
- [ ] Safari (if available)

---

## Bug Reporting Template

If you find a bug, report it with:

```
**Bug Title:** [Short description]

**Steps to Reproduce:**
1. 
2. 
3. 

**Expected Result:**
[What should happen]

**Actual Result:**
[What actually happened]

**Browser:** [Chrome/Firefox/etc.]
**Screenshot:** [If applicable]
```

---

## Test Results Summary

After completing all tests, fill in:

| Test Category | Pass | Fail | Notes |
|--------------|------|------|-------|
| Authentication | ☐ | ☐ | |
| Dashboard | ☐ | ☐ | |
| Appointment Creation | ☐ | ☐ | |
| Appointment Management | ☐ | ☐ | |
| UI/UX | ☐ | ☐ | |
| Data Validation | ☐ | ☐ | |
| Session Management | ☐ | ☐ | |
| Database | ☐ | ☐ | |

**Overall Status:** ☐ PASS ☐ FAIL

**Tested By:** _______________
**Date:** _______________
**Notes:** _______________

---

## Quick Test Script

For rapid testing, use this sequence:

```bash
# 1. Setup
php spark migrate
php spark db:seed UserSeeder
php spark serve

# 2. Open browser to http://localhost:8080/login

# 3. Quick test flow:
- Login (receptionist/receptionist123)
- Create 3 appointments
- Confirm 1 appointment
- Cancel 1 appointment
- Complete 1 confirmed appointment
- Logout
- Login again
- Verify all data persists
```

---

**Happy Testing! 🧪**
