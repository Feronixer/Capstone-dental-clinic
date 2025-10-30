# Test Execution Template - Notification System

## Quick Start Guide

### 1. Pre-Test Setup (15 minutes)

```bash
# 1. Create test database
php artisan migrate:fresh --seed

# 2. Create test accounts (or use seeder)
# Patient: test_patient@clinic.com / password
# Staff: test_staff@clinic.com / password  
# Admin: test_admin@clinic.com / password

# 3. Clear all caches
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
```

### 2. Generate Test Data

Run this in Tinker or create a seeder:

```php
php artisan tinker

// Create 50 notifications for patient
$patient = User::where('email', 'test_patient@clinic.com')->first();
for ($i = 1; $i <= 50; $i++) {
    Notification::create([
        'user_id' => $patient->id,
        'type' => Notification::TYPE_APPOINTMENT_CONFIRMED,
        'title' => "Test Notification $i",
        'message' => "This is test notification number $i",
        'is_read' => $i % 3 == 0, // Every 3rd is read
    ]);
}

// Create pending appointment requests
$service = Service::first();
for ($i = 1; $i <= 5; $i++) {
    AppointmentRequest::create([
        'patient_id' => $patient->id,
        'service_id' => $service->id,
        'requested_datetime' => now()->addDays($i),
        'duration_minutes' => 60,
        'request_type' => 'walk-in',
        'reason' => "Test walk-in request $i",
        'status' => 'Pending'
    ]);
}
```

---

## Test Execution Tracker

### Day 1: Critical & High Priority Patient Tests

| Test ID | Test Name | Status | Result | Notes | Tester | Date |
|---------|-----------|--------|--------|-------|--------|------|
| TC-P-001 | View Notifications Page | ⬜ | - | - | - | - |
| TC-P-002 | Notification List Display | ⬜ | - | - | - | - |
| TC-P-004 | Pagination Display | ⬜ | - | - | - | - |
| TC-P-005 | Pagination - Next Page | ⬜ | - | - | - | - |
| TC-P-006 | Pagination - Previous Page | ⬜ | - | - | - | - |
| TC-P-007 | Pagination - Specific Page | ⬜ | - | - | - | - |
| TC-P-010 | Filter - All Notifications | ⬜ | - | - | - | - |
| TC-P-011 | Filter - Unread Only | ⬜ | - | - | - | - |
| TC-P-012 | Filter - Read Only | ⬜ | - | - | - | - |
| TC-P-013 | Filter Persistence | ⬜ | - | - | - | - |
| TC-P-015 | Mark as Read | ⬜ | - | - | - | - |
| TC-P-016 | Mark as Unread | ⬜ | - | - | - | - |
| TC-P-017 | Delete Notification | ⬜ | - | - | - | - |
| TC-P-019 | Mark All as Read | ⬜ | - | - | - | - |
| TC-P-020 | Clear Read Notifications | ⬜ | - | - | - | - |

### Day 2: Staff & Admin Tests

| Test ID | Test Name | Status | Result | Notes | Tester | Date |
|---------|-----------|--------|--------|-------|--------|------|
| TC-S-001 | View Pending Requests | ⬜ | - | - | - | - |
| TC-S-003 | Approve Walk-in Request | ⬜ | - | - | - | - |
| TC-S-004 | Approve Reschedule Request | ⬜ | - | - | - | - |
| TC-S-005 | Deny Request | ⬜ | - | - | - | - |
| TC-A-001 | View Pending (Admin) | ⬜ | - | - | - | - |
| TC-A-002 | Approve Request (Admin) | ⬜ | - | - | - | - |
| TC-A-003 | Deny Request (Admin) | ⬜ | - | - | - | - |

### Day 3: Integration Tests

| Test ID | Test Name | Status | Result | Notes | Tester | Date |
|---------|-----------|--------|--------|-------|--------|------|
| TC-I-001 | Complete Walk-in Flow | ⬜ | - | - | - | - |
| TC-I-002 | Complete Reschedule Flow | ⬜ | - | - | - | - |
| TC-I-003 | Denial Flow | ⬜ | - | - | - | - |
| TC-I-006 | Authorization Test | ⬜ | - | - | - | - |
| TC-I-007 | CSRF Protection | ⬜ | - | - | - | - |
| TC-I-011 | Notification Creation | ⬜ | - | - | - | - |
| TC-I-012 | Denied Request Notification | ⬜ | - | - | - | - |
| TC-I-013 | Dual Notification Reschedule | ⬜ | - | - | - | - |

### Day 4: UI/UX & Responsive Tests

| Test ID | Test Name | Status | Result | Notes | Tester | Date |
|---------|-----------|--------|--------|-------|--------|------|
| TC-P-021 | Responsive - Mobile | ⬜ | - | - | - | - |
| TC-P-022 | Responsive - Tablet | ⬜ | - | - | - | - |
| TC-P-023 | Hover Effects | ⬜ | - | - | - | - |
| TC-P-024 | Active States | ⬜ | - | - | - | - |
| TC-P-025 | Icons and Colors | ⬜ | - | - | - | - |

---

## Manual Test Script Examples

### Script 1: Patient Notification Pagination (10 min)

**Scenario:** Test basic pagination functionality

```
1. Login as: test_patient@clinic.com / password
2. Navigate to: /patient/notifications
3. Verify: Page shows "Showing 1 to 20 of 50 results"
4. Verify: Page numbers 1, 2, 3 visible
5. Verify: Previous button is disabled (grayed out)
6. Verify: Next button is enabled (blue)
7. Click: Next button
8. Verify: URL changes to ?page=2
9. Verify: Shows "Showing 21 to 40 of 50 results"
10. Verify: Previous button now enabled
11. Click: Page number "3"
12. Verify: URL changes to ?page=3
13. Verify: Shows "Showing 41 to 50 of 50 results"
14. Verify: Next button is disabled (last page)
15. Click: Previous button
16. Verify: Returns to page 2

PASS ✅ / FAIL ❌: _______
Notes: _________________________________
```

### Script 2: Filter and Pagination (10 min)

**Scenario:** Test filter persistence across pages

```
1. Login as: test_patient@clinic.com
2. Navigate to: /patient/notifications
3. Click: "Unread" filter button
4. Verify: URL changes to ?filter=unread
5. Verify: Only unread notifications shown (blue highlight)
6. Verify: "Unread" button is highlighted
7. Note: Current unread count: _______
8. Click: Next button (if available)
9. Verify: URL is ?filter=unread&page=2
10. Verify: "Unread" filter still active
11. Verify: Still only unread notifications shown
12. Click: "Read" filter
13. Verify: URL changes to ?filter=read
14. Verify: Only read notifications shown (no highlight)
15. Verify: Pagination reset to page 1

PASS ✅ / FAIL ❌: _______
Notes: _________________________________
```

### Script 3: Mark as Read/Unread (5 min)

**Scenario:** Test marking notifications

```
1. Login as: test_patient@clinic.com
2. Navigate to: /patient/notifications
3. Find: First UNREAD notification (blue highlight)
4. Note: Unread badge count before: _______
5. Click: "Mark as Read" button
6. Verify: Page reloads
7. Verify: Notification no longer has blue highlight
8. Verify: Button changed to "Mark as Unread"
9. Verify: Unread badge decreased by 1
10. Click: "Mark as Unread" button
11. Verify: Page reloads
12. Verify: Notification has blue highlight again
13. Verify: Button changed to "Mark as Read"
14. Verify: Unread badge increased by 1

PASS ✅ / FAIL ❌: _______
Notes: _________________________________
```

### Script 4: Staff Approve Request (10 min)

**Scenario:** Complete approval workflow

```
1. Login as: test_staff@clinic.com
2. Navigate to: /staff/notifications
3. Verify: Pending requests displayed
4. Select: First walk-in request
5. Note: Patient name: _____________
6. Note: Requested date/time: _____________
7. Click: "Approve" button
8. If required, Enter: Duration (e.g., 60 minutes)
9. Click: "Confirm" button
10. Verify: Success message appears
11. Verify: Request removed from pending list
12. Logout from staff account

13. Login as: Patient from step 5
14. Click: Bell icon in header
15. Verify: New notification badge visible
16. Click: "View All Notifications"
17. Verify: "Appointment Request Approved" notification present
18. Verify: Notification is unread (blue highlight)
19. Verify: Date/time matches request

PASS ✅ / FAIL ❌: _______
Notes: _________________________________
```

### Script 5: Reschedule Flow (15 min)

**Scenario:** Complete reschedule workflow

```
1. Login as: test_patient@clinic.com
2. Navigate to: /patient/calendar
3. Click: Existing appointment
4. Click: "Reschedule" button
5. Select: New date/time
6. Click: "Submit Reschedule Request"
7. Logout from patient account

8. Login as: test_staff@clinic.com
9. Navigate to: /staff/notifications
10. Find: Reschedule request
11. Verify: Shows old appointment date
12. Verify: Shows new requested date
13. Click: "Approve" button
14. Click: "Confirm"
15. Verify: Success message
16. Logout from staff account

17. Login as: test_patient@clinic.com
18. Navigate to: /patient/notifications
19. Verify: TWO new notifications received:
    - "Previous Appointment Cancelled"
    - "Appointment Request Approved"
20. Navigate to: /patient/calendar
21. Verify: Old appointment marked cancelled
22. Verify: New appointment shows confirmed

PASS ✅ / FAIL ❌: _______
Notes: _________________________________
```

---

## Bug Report Template

**Bug ID:** BUG-NOTIF-XXX  
**Test Case ID:** TC-X-XXX  
**Severity:** Critical / High / Medium / Low  

**Summary:**
[One-line description of the issue]

**Steps to Reproduce:**
1. 
2. 
3. 

**Expected Result:**
[What should happen]

**Actual Result:**
[What actually happened]

**Screenshots:**
[Attach if applicable]

**Browser/Device:**
- Browser: Chrome 120.0.0.0
- OS: Windows 11
- Screen Size: 1920x1080

**Additional Notes:**
[Any other relevant information]

**Assigned To:** _____________  
**Status:** Open / In Progress / Resolved / Closed  
**Resolution Date:** _____________

---

## Daily Test Summary Report

**Date:** _______________  
**Tester:** _______________  
**Environment:** Development / Staging / Production

### Tests Executed Today

| Category | Planned | Executed | Passed | Failed | Blocked |
|----------|---------|----------|--------|--------|---------|
| Patient Tests | | | | | |
| Staff Tests | | | | | |
| Admin Tests | | | | | |
| Integration Tests | | | | | |
| **TOTAL** | | | | | |

### Pass Rate: _______% (Passed / Executed × 100)

### Issues Found Today

| Bug ID | Severity | Test Case | Description | Status |
|--------|----------|-----------|-------------|--------|
| | | | | |
| | | | | |
| | | | | |

### Blocked Tests

| Test ID | Reason Blocked | Expected Resolution |
|---------|----------------|---------------------|
| | | |
| | | |

### Notes & Observations

_____________________________________________
_____________________________________________
_____________________________________________

### Tomorrow's Plan

- [ ] Complete remaining _______ tests
- [ ] Retest failed cases
- [ ] Test bug fixes
- [ ] ______________________

**Sign-off:** _______________  
**Date:** _______________

---

## Test Completion Checklist

### Pre-Release Verification

- ⬜ All Critical tests passed
- ⬜ All High priority tests passed
- ⬜ All bugs resolved or documented
- ⬜ Cross-browser testing complete
- ⬜ Mobile testing complete
- ⬜ Performance tests passed
- ⬜ Security tests passed
- ⬜ Regression tests passed
- ⬜ User acceptance testing complete
- ⬜ Documentation updated
- ⬜ Test report generated
- ⬜ Sign-off received from stakeholders

### Final Sign-off

**Test Lead:** _______________ Date: _______  
**Developer:** _______________ Date: _______  
**Project Manager:** _______________ Date: _______  

---

## Quick Reference - Test URLs

```
# Patient URLs
http://localhost/patient/notifications
http://localhost/patient/notifications?filter=unread
http://localhost/patient/notifications?filter=read
http://localhost/patient/notifications?page=2

# Staff URLs
http://localhost/staff/notifications

# Admin URLs
http://localhost/admin/notifications
```

## Quick Reference - Test Accounts

```
Patient:  test_patient@clinic.com / password
Staff:    test_staff@clinic.com / password
Admin:    test_admin@clinic.com / password
```

## Quick Reference - Database Queries

```sql
-- Check notification counts
SELECT COUNT(*) FROM notifications WHERE user_id = X;

-- Check unread count
SELECT COUNT(*) FROM notifications WHERE user_id = X AND is_read = 0;

-- Check pending requests
SELECT COUNT(*) FROM appointment_requests WHERE status = 'Pending';

-- View recent notifications
SELECT * FROM notifications WHERE user_id = X ORDER BY created_at DESC LIMIT 20;
```

---

**Document Version:** 1.0  
**Last Updated:** October 30, 2025  
**Status:** Ready for Use

