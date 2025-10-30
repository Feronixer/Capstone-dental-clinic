# Testing Guide - Notification System
**Quick Start Guide for Testing the Notification System**

---

## 📚 Documentation Overview

This testing suite includes **4 comprehensive documents**:

1. **TEST_CASES_NOTIFICATION_SYSTEM.md** (58 Test Cases)
   - Detailed test cases for Patient, Staff, and Admin
   - Covers functional, UI/UX, integration, and security tests
   - Organized by priority (Critical, High, Medium, Low)

2. **TEST_EXECUTION_TEMPLATE.md**
   - Day-by-day test execution tracker
   - Manual test scripts (copy-paste ready)
   - Bug report template
   - Daily summary report template

3. **NotificationTestSeeder.php** (Database Seeder)
   - Automatically creates test data
   - 50 notifications for pagination testing
   - 5 walk-in requests + 2 reschedule requests
   - Ready-to-use test account

4. **This README** - Quick start guide

---

## 🚀 Quick Start (5 Minutes)

### Step 1: Set Up Test Data

```bash
# Run the notification test seeder
php artisan db:seed --class=NotificationTestSeeder
```

**This creates:**
- ✅ 50 notifications (33 unread, 17 read)
- ✅ 5 pending walk-in requests
- ✅ 2 pending reschedule requests
- ✅ 2 existing appointments
- ✅ Test patient account

### Step 2: Login and Test

**Test Account:**
- Email: `test_patient@clinic.com`
- Password: `password`

**Test URLs:**
- Patient: `http://localhost/patient/notifications`
- Staff: `http://localhost/staff/notifications`
- Admin: `http://localhost/admin/notifications`

### Step 3: Run Basic Tests

Open **TEST_EXECUTION_TEMPLATE.md** and follow **Script 1** (10 minutes):
- Tests basic pagination (Previous/Next)
- Tests page number navigation
- Verifies "Showing X to Y of Z" display

---

## 📋 Test Execution Plan

### Day 1: Patient Core Features (2-3 hours)
Focus on high-priority patient tests:

**Tests to run:**
- TC-P-001 to TC-P-009 (Pagination tests)
- TC-P-010 to TC-P-014 (Filter tests)
- TC-P-015 to TC-P-020 (Action tests)

**Use these scripts:**
- Script 1: Pagination (10 min)
- Script 2: Filter and Pagination (10 min)
- Script 3: Mark as Read/Unread (5 min)

### Day 2: Staff & Admin (1-2 hours)
Test appointment request management:

**Tests to run:**
- TC-S-001 to TC-S-008 (Staff tests)
- TC-A-001 to TC-A-004 (Admin tests)

**Use these scripts:**
- Script 4: Staff Approve Request (10 min)
- Script 5: Reschedule Flow (15 min)

### Day 3: Integration & Security (2 hours)
End-to-end workflows and security:

**Tests to run:**
- TC-I-001 to TC-I-003 (E2E flows)
- TC-I-006 to TC-I-008 (Security)
- TC-I-011 to TC-I-013 (Notifications)

### Day 4: UI/UX & Polish (1 hour)
Responsive design and visual tests:

**Tests to run:**
- TC-P-021 to TC-P-025 (Responsive & UI)
- TC-S-009 to TC-S-010 (Staff UI)

---

## 🎯 Test Case Summary

### By User Role

| Role | Test Cases | Coverage |
|------|-----------|----------|
| **Patient** | 30 tests | Pagination, Filters, Actions, UI |
| **Staff** | 10 tests | Approve/Deny requests, UI |
| **Admin** | 4 tests | Same as Staff with role verification |
| **Integration** | 10 tests | E2E flows, Security, Performance |
| **Cross-cutting** | 4 tests | Authorization, CSRF, Concurrent |
| **Total** | **58 tests** | Complete system coverage |

### By Priority

| Priority | Count | Focus Area |
|----------|-------|------------|
| **Critical** | 4 | E2E flows, Security |
| **High** | 37 | Core functionality |
| **Medium** | 15 | UI/UX, Edge cases |
| **Low** | 2 | Nice-to-have features |

---

## 🧪 How to Use Test Documents

### For Manual Testing

1. **Open TEST_CASES_NOTIFICATION_SYSTEM.md**
   - Find test case by ID (e.g., TC-P-001)
   - Read objective and preconditions
   - Follow test steps exactly
   - Compare actual vs expected results

2. **Use TEST_EXECUTION_TEMPLATE.md**
   - Copy the test script
   - Follow step-by-step instructions
   - Mark PASS ✅ or FAIL ❌
   - Document any issues found

3. **Track Progress**
   - Use the execution tracker tables
   - Update status: ⬜ → ✅ or ❌
   - Fill in tester name and date
   - Add notes for any anomalies

### For Bug Reporting

When you find a bug:

1. Open **TEST_EXECUTION_TEMPLATE.md**
2. Scroll to **Bug Report Template**
3. Fill in all fields:
   - Bug ID (e.g., BUG-NOTIF-001)
   - Test Case ID
   - Severity (Critical/High/Medium/Low)
   - Steps to reproduce
   - Expected vs Actual
   - Screenshots if applicable

4. Report to development team

---

## 🔧 Advanced Testing

### Running All Tests Programmatically

If you want to create automated tests, here's the structure:

```php
// Feature Test Example
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Notification;

class PatientNotificationTest extends TestCase
{
    /** @test */
    public function patient_can_view_notifications_page()
    {
        $patient = User::where('email', 'test_patient@clinic.com')->first();
        
        $response = $this->actingAs($patient)
            ->get('/patient/notifications');
        
        $response->assertStatus(200);
        $response->assertSee('Notifications');
        $response->assertViewHas('notifications');
    }
    
    /** @test */
    public function pagination_displays_20_notifications_per_page()
    {
        $patient = User::where('email', 'test_patient@clinic.com')->first();
        
        $response = $this->actingAs($patient)
            ->get('/patient/notifications');
        
        $notifications = $response->viewData('notifications');
        $this->assertLessThanOrEqual(20, $notifications->count());
    }
    
    /** @test */
    public function filter_shows_only_unread_notifications()
    {
        $patient = User::where('email', 'test_patient@clinic.com')->first();
        
        $response = $this->actingAs($patient)
            ->get('/patient/notifications?filter=unread');
        
        $notifications = $response->viewData('notifications');
        
        foreach ($notifications as $notification) {
            $this->assertFalse($notification->is_read);
        }
    }
}
```

Run with:
```bash
php artisan test --filter PatientNotificationTest
```

---

## 📊 Test Coverage Matrix

### Patient Notification Features

| Feature | Test Cases | Status |
|---------|-----------|--------|
| View notifications list | TC-P-001, TC-P-002 | ⬜ |
| Pagination (20/page) | TC-P-004 to TC-P-009 | ⬜ |
| Filter (All/Unread/Read) | TC-P-010 to TC-P-014 | ⬜ |
| Mark read/unread | TC-P-015, TC-P-016 | ⬜ |
| Delete notifications | TC-P-017, TC-P-018 | ⬜ |
| Bulk actions | TC-P-019, TC-P-020 | ⬜ |
| Header dropdown | TC-P-027 | ⬜ |
| Badge count | TC-P-026 | ⬜ |
| Auto-refresh | TC-P-028 | ⬜ |
| Responsive design | TC-P-021, TC-P-022 | ⬜ |

### Staff/Admin Features

| Feature | Test Cases | Status |
|---------|-----------|--------|
| View pending requests | TC-S-001, TC-A-001 | ⬜ |
| Approve walk-in | TC-S-003, TC-A-002 | ⬜ |
| Approve reschedule | TC-S-004 | ⬜ |
| Deny request | TC-S-005, TC-A-003 | ⬜ |
| Patient notification | TC-I-011, TC-I-012 | ⬜ |

---

## 🎨 Browser Testing Matrix

### Desktop Browsers

| Browser | Version | Status | Tester | Date |
|---------|---------|--------|--------|------|
| Chrome | Latest | ⬜ | - | - |
| Firefox | Latest | ⬜ | - | - |
| Edge | Latest | ⬜ | - | - |
| Safari | Latest | ⬜ | - | - |

### Mobile Devices

| Device | OS | Browser | Status | Tester | Date |
|--------|----|---------| -------|--------|------|
| iPhone SE | iOS 17 | Safari | ⬜ | - | - |
| iPhone 14 | iOS 17 | Safari | ⬜ | - | - |
| Samsung S23 | Android | Chrome | ⬜ | - | - |
| iPad | iOS 17 | Safari | ⬜ | - | - |

### Viewport Testing

| Size | Resolution | Status |
|------|-----------|--------|
| Mobile S | 320x568 | ⬜ |
| Mobile M | 375x667 | ⬜ |
| Mobile L | 425x926 | ⬜ |
| Tablet | 768x1024 | ⬜ |
| Desktop | 1920x1080 | ⬜ |

---

## 🐛 Common Issues & Solutions

### Issue 1: "No notifications found"
**Solution:** Run the seeder again
```bash
php artisan db:seed --class=NotificationTestSeeder
```

### Issue 2: "Pagination not working"
**Possible causes:**
- Cache issue → Clear cache: `php artisan cache:clear`
- Route issue → Clear routes: `php artisan route:clear`
- View issue → Clear views: `php artisan view:clear`

### Issue 3: "Filter not persisting"
**Check:**
- URL contains `?filter=unread` or `?filter=read`
- Filter button is highlighted
- Clear browser cache (Ctrl+Shift+Del)

### Issue 4: "Badge not updating"
**Solutions:**
- Hard refresh page (Ctrl+F5)
- Check browser console for JavaScript errors
- Verify notification created in database:
  ```sql
  SELECT COUNT(*) FROM notifications WHERE user_id = X AND is_read = 0;
  ```

---

## 📝 Test Data Management

### Reset Test Data

To start fresh:

```bash
# Method 1: Reseed everything
php artisan migrate:fresh --seed
php artisan db:seed --class=NotificationTestSeeder

# Method 2: Just delete test notifications
php artisan tinker
>>> Notification::where('user_id', User::where('email', 'test_patient@clinic.com')->first()->id)->delete();
>>> AppointmentRequest::where('patient_id', User::where('email', 'test_patient@clinic.com')->first()->id)->delete();
```

### Add More Notifications

```bash
php artisan tinker
>>> $patient = User::where('email', 'test_patient@clinic.com')->first();
>>> for ($i = 1; $i <= 20; $i++) {
...     Notification::create([
...         'user_id' => $patient->id,
...         'type' => 'general',
...         'title' => "Extra Test $i",
...         'message' => "Additional test notification",
...         'is_read' => false,
...     ]);
... }
```

---

## ✅ Test Completion Checklist

Before marking testing as complete:

### Functional Testing
- [ ] All Critical tests passed
- [ ] All High priority tests passed
- [ ] At least 80% of Medium tests passed
- [ ] All major bugs fixed

### Cross-Browser Testing
- [ ] Tested on Chrome
- [ ] Tested on Firefox
- [ ] Tested on Safari (if Mac available)
- [ ] Tested on Edge

### Responsive Testing
- [ ] Tested on mobile (375px width)
- [ ] Tested on tablet (768px width)
- [ ] Tested on desktop (1920px width)

### Integration Testing
- [ ] Patient receives notifications when staff approves
- [ ] Patient receives notifications when staff denies
- [ ] Reschedule creates 2 notifications
- [ ] Badge count updates correctly
- [ ] Dropdown shows recent notifications

### Security Testing
- [ ] CSRF protection verified
- [ ] Authorization checks verified
- [ ] No SQL injection vulnerabilities
- [ ] No XSS vulnerabilities

### Documentation
- [ ] All bugs documented
- [ ] Test results recorded
- [ ] Screenshots captured for issues
- [ ] Test report generated

---

## 📧 Support & Questions

If you encounter any issues with the test cases or need clarification:

1. **Check the test case details** in TEST_CASES_NOTIFICATION_SYSTEM.md
2. **Review the test scripts** in TEST_EXECUTION_TEMPLATE.md
3. **Verify test data** is seeded correctly
4. **Check system logs** at `storage/logs/laravel.log`
5. **Contact the development team** with:
   - Test case ID
   - Steps taken
   - Expected vs actual results
   - Screenshots

---

## 🎉 Getting Started Checklist

**Ready to begin testing? Complete these steps:**

- [ ] Read this README completely
- [ ] Run NotificationTestSeeder (`php artisan db:seed --class=NotificationTestSeeder`)
- [ ] Verify test account works (login as test_patient@clinic.com)
- [ ] Open TEST_EXECUTION_TEMPLATE.md
- [ ] Run Script 1 (Basic Pagination - 10 min)
- [ ] Document results
- [ ] Proceed to Day 1 tests

**Estimated Total Testing Time:** 8-12 hours (can be split across 4 days)

---

**Good luck with testing! 🚀**

**Remember:** Thorough testing ensures a quality product. Take your time, document everything, and don't hesitate to report even minor issues.

---

**Document Version:** 1.0  
**Created:** October 30, 2025  
**Last Updated:** October 30, 2025  
**Status:** Ready for Use ✅

