# Test Cases - Notification System
**JValera Dental Clinic Management System**  
**Test Date:** October 30, 2025  
**Version:** 1.0

---

## Table of Contents
1. [Patient Notification System Tests](#1-patient-notification-system-tests)
2. [Staff Notification System Tests](#2-staff-notification-system-tests)
3. [Admin Notification System Tests](#3-admin-notification-system-tests)
4. [Cross-Role Integration Tests](#4-cross-role-integration-tests)

---

# 1. Patient Notification System Tests

## 1.1 Functional Tests - Notification Display

### TC-P-001: View Notifications Page
**Objective:** Verify patient can access notifications page  
**Preconditions:** Patient is logged in  
**Test Steps:**
1. Click on bell icon in header
2. Click "View All Notifications" link
3. Verify redirection to `/patient/notifications`

**Expected Result:**
- ✅ Page loads successfully
- ✅ URL shows `/patient/notifications`
- ✅ Page title displays "Notifications"
- ✅ Header and navigation are visible

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-P-002: Notification List Display
**Objective:** Verify notifications are displayed correctly  
**Preconditions:** 
- Patient is logged in
- Patient has at least 5 notifications

**Test Steps:**
1. Navigate to `/patient/notifications`
2. Observe notification list

**Expected Result:**
- ✅ All notifications for current user are displayed
- ✅ Each notification shows: icon, title, message, timestamp
- ✅ Unread notifications have blue highlight/background
- ✅ Read notifications appear normal
- ✅ Notifications are ordered by date (newest first)

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-P-003: Empty Notifications State
**Objective:** Verify empty state when no notifications exist  
**Preconditions:** Patient has no notifications  
**Test Steps:**
1. Navigate to `/patient/notifications`

**Expected Result:**
- ✅ Empty state icon displayed (bell with slash)
- ✅ Message: "No Notifications"
- ✅ Subtext: "You don't have any notifications yet."
- ✅ No pagination controls shown

**Priority:** Medium  
**Status:** ⬜ To Test

---

## 1.2 Functional Tests - Pagination

### TC-P-004: Pagination Display
**Objective:** Verify pagination controls appear correctly  
**Preconditions:** Patient has more than 20 notifications  
**Test Steps:**
1. Navigate to `/patient/notifications`
2. Scroll to bottom of page

**Expected Result:**
- ✅ Pagination wrapper is visible
- ✅ "Showing X to Y of Z results" displays correct numbers
- ✅ Previous button is disabled on page 1
- ✅ Next button is enabled
- ✅ Page number buttons are visible (1, 2, 3, etc.)
- ✅ Current page (1) is highlighted

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-P-005: Pagination - Next Page
**Objective:** Verify navigation to next page works  
**Preconditions:** Patient on page 1 with multiple pages available  
**Test Steps:**
1. Navigate to `/patient/notifications`
2. Click "Next" button (chevron right)

**Expected Result:**
- ✅ URL updates to `/patient/notifications?page=2`
- ✅ Next 20 notifications displayed (21-40)
- ✅ "Showing 21 to 40 of X results" displays
- ✅ Page 2 button is now highlighted
- ✅ Previous button is now enabled
- ✅ Page scrolls to top

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-P-006: Pagination - Previous Page
**Objective:** Verify navigation to previous page works  
**Preconditions:** Patient is on page 2 or higher  
**Test Steps:**
1. Navigate to `/patient/notifications?page=2`
2. Click "Previous" button (chevron left)

**Expected Result:**
- ✅ URL updates to `/patient/notifications?page=1`
- ✅ First 20 notifications displayed (1-20)
- ✅ "Showing 1 to 20 of X results" displays
- ✅ Page 1 button is highlighted
- ✅ Previous button is disabled
- ✅ Page scrolls to top

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-P-007: Pagination - Specific Page Number
**Objective:** Verify clicking specific page number works  
**Preconditions:** Patient has at least 60 notifications (3+ pages)  
**Test Steps:**
1. Navigate to `/patient/notifications`
2. Click on page number "3"

**Expected Result:**
- ✅ URL updates to `/patient/notifications?page=3`
- ✅ Notifications 41-60 displayed
- ✅ "Showing 41 to 60 of X results" displays
- ✅ Page 3 button is highlighted
- ✅ Both Previous and Next enabled
- ✅ Page scrolls to top

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-P-008: Pagination - Ellipsis Display
**Objective:** Verify ellipsis appears for large page counts  
**Preconditions:** Patient has 100+ notifications (5+ pages)  
**Test Steps:**
1. Navigate to `/patient/notifications?page=3`
2. Observe pagination controls

**Expected Result:**
- ✅ Page 1 shown with ellipsis before current range
- ✅ Current page ± 1 shown (pages 2, 3, 4)
- ✅ Last page shown with ellipsis after current range
- ✅ Format: [<] [1] [...] [2] [3] [4] [...] [Last] [>]

**Priority:** Medium  
**Status:** ⬜ To Test

---

### TC-P-009: Pagination - Last Page Disabled State
**Objective:** Verify Next button is disabled on last page  
**Preconditions:** Patient on last page of notifications  
**Test Steps:**
1. Navigate to last page of notifications
2. Observe Next button

**Expected Result:**
- ✅ Next button is disabled (grayed out)
- ✅ Next button is not clickable
- ✅ Last page number is highlighted
- ✅ Previous button is enabled

**Priority:** Medium  
**Status:** ⬜ To Test

---

## 1.3 Functional Tests - Filtering

### TC-P-010: Filter - All Notifications
**Objective:** Verify "All" filter shows all notifications  
**Preconditions:** Patient has read and unread notifications  
**Test Steps:**
1. Navigate to `/patient/notifications`
2. Ensure "All" filter is active (default)

**Expected Result:**
- ✅ "All" button is highlighted/active
- ✅ Both read and unread notifications displayed
- ✅ URL is `/patient/notifications` (no filter parameter)
- ✅ Count shows total notifications

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-P-011: Filter - Unread Only
**Objective:** Verify "Unread" filter shows only unread notifications  
**Preconditions:** Patient has both read and unread notifications  
**Test Steps:**
1. Navigate to `/patient/notifications`
2. Click "Unread" filter button

**Expected Result:**
- ✅ URL updates to `/patient/notifications?filter=unread`
- ✅ "Unread" button is highlighted/active
- ✅ Only unread notifications displayed
- ✅ Unread count badge shows correct number
- ✅ Pagination resets to page 1
- ✅ "Showing X to Y of Z results" reflects filtered count

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-P-012: Filter - Read Only
**Objective:** Verify "Read" filter shows only read notifications  
**Preconditions:** Patient has both read and unread notifications  
**Test Steps:**
1. Navigate to `/patient/notifications`
2. Click "Read" filter button

**Expected Result:**
- ✅ URL updates to `/patient/notifications?filter=read`
- ✅ "Read" button is highlighted/active
- ✅ Only read notifications displayed
- ✅ No blue highlight on notifications
- ✅ Pagination resets to page 1
- ✅ "Showing X to Y of Z results" reflects filtered count

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-P-013: Filter Persistence Across Pagination
**Objective:** Verify filter is maintained when changing pages  
**Preconditions:** Patient has 40+ unread notifications (2+ pages)  
**Test Steps:**
1. Navigate to `/patient/notifications`
2. Click "Unread" filter
3. Click "Next" to go to page 2

**Expected Result:**
- ✅ URL is `/patient/notifications?filter=unread&page=2`
- ✅ "Unread" filter remains active
- ✅ Only unread notifications shown on page 2
- ✅ Filter button still highlighted

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-P-014: Filter - Empty Result
**Objective:** Verify empty state when filter has no results  
**Preconditions:** Patient has no unread notifications  
**Test Steps:**
1. Navigate to `/patient/notifications`
2. Click "Unread" filter

**Expected Result:**
- ✅ Empty state displayed
- ✅ Message indicates no unread notifications
- ✅ No pagination controls shown
- ✅ Filter remains selectable

**Priority:** Medium  
**Status:** ⬜ To Test

---

## 1.4 Functional Tests - Notification Actions

### TC-P-015: Mark Notification as Read
**Objective:** Verify marking unread notification as read  
**Preconditions:** Patient has at least one unread notification  
**Test Steps:**
1. Navigate to `/patient/notifications`
2. Locate an unread notification (blue highlight)
3. Click "Mark as Read" button

**Expected Result:**
- ✅ Page reloads
- ✅ Notification no longer has blue highlight
- ✅ "Mark as Read" button changes to "Mark as Unread"
- ✅ Unread badge count decreases by 1
- ✅ Notification moved to read section if filtered

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-P-016: Mark Notification as Unread
**Objective:** Verify marking read notification as unread  
**Preconditions:** Patient has at least one read notification  
**Test Steps:**
1. Navigate to `/patient/notifications`
2. Locate a read notification
3. Click "Mark as Unread" button

**Expected Result:**
- ✅ Page reloads
- ✅ Notification now has blue highlight
- ✅ "Mark as Unread" button changes to "Mark as Read"
- ✅ Unread badge count increases by 1
- ✅ Notification appears in unread filter

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-P-017: Delete Single Notification
**Objective:** Verify deleting a single notification  
**Preconditions:** Patient has at least one notification  
**Test Steps:**
1. Navigate to `/patient/notifications`
2. Click "Delete" button on a notification
3. Confirm deletion in modal

**Expected Result:**
- ✅ Confirmation modal appears
- ✅ Modal asks "Are you sure you want to delete this notification?"
- ✅ After confirmation, page reloads
- ✅ Notification is removed from list
- ✅ Total count decreases by 1
- ✅ If unread, unread badge decreases by 1

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-P-018: Delete Notification - Cancel
**Objective:** Verify canceling notification deletion  
**Preconditions:** Patient has at least one notification  
**Test Steps:**
1. Navigate to `/patient/notifications`
2. Click "Delete" button
3. Click "Cancel" in modal

**Expected Result:**
- ✅ Modal closes
- ✅ Notification remains in list
- ✅ No data changed
- ✅ Page does not reload

**Priority:** Medium  
**Status:** ⬜ To Test

---

### TC-P-019: Mark All as Read
**Objective:** Verify marking all notifications as read  
**Preconditions:** Patient has multiple unread notifications  
**Test Steps:**
1. Navigate to `/patient/notifications`
2. Verify unread count in header
3. Click "Mark All as Read" button
4. Confirm in modal

**Expected Result:**
- ✅ Confirmation modal appears
- ✅ After confirmation, page reloads
- ✅ All notifications now appear as read
- ✅ Unread badge shows 0
- ✅ "Mark All as Read" button disappears
- ✅ No blue highlights on any notifications

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-P-020: Clear Read Notifications
**Objective:** Verify clearing all read notifications  
**Preconditions:** Patient has multiple read notifications  
**Test Steps:**
1. Navigate to `/patient/notifications`
2. Click "Clear Read" button
3. Confirm in modal

**Expected Result:**
- ✅ Confirmation modal appears
- ✅ Modal warns about permanent deletion
- ✅ After confirmation, page reloads
- ✅ All read notifications are deleted
- ✅ Only unread notifications remain
- ✅ Total count reflects only unread

**Priority:** High  
**Status:** ⬜ To Test

---

## 1.5 UI/UX Tests - Patient Notifications

### TC-P-021: Responsive Design - Mobile
**Objective:** Verify notifications display correctly on mobile  
**Preconditions:** Mobile device or browser DevTools set to mobile viewport  
**Test Steps:**
1. Set viewport to 375x667 (iPhone SE)
2. Navigate to `/patient/notifications`

**Expected Result:**
- ✅ Layout adjusts to single column
- ✅ Filter buttons stack vertically or wrap
- ✅ Pagination info and controls stack vertically
- ✅ Notification cards are full width
- ✅ Action buttons are touch-friendly
- ✅ Text is readable without zooming
- ✅ No horizontal scrolling

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-P-022: Responsive Design - Tablet
**Objective:** Verify notifications display correctly on tablet  
**Preconditions:** Tablet device or browser DevTools set to tablet viewport  
**Test Steps:**
1. Set viewport to 768x1024 (iPad)
2. Navigate to `/patient/notifications`

**Expected Result:**
- ✅ Layout uses available space efficiently
- ✅ Pagination controls properly aligned
- ✅ Filter buttons in single row
- ✅ Notification cards have appropriate width
- ✅ No layout breaking or overflow

**Priority:** Medium  
**Status:** ⬜ To Test

---

### TC-P-023: Visual States - Hover Effects
**Objective:** Verify hover states work correctly  
**Preconditions:** Desktop browser  
**Test Steps:**
1. Navigate to `/patient/notifications`
2. Hover over various elements

**Expected Result:**
- ✅ Notification cards elevate on hover
- ✅ Filter buttons highlight on hover
- ✅ Page number buttons highlight on hover
- ✅ Action buttons show hover state
- ✅ Cursor changes to pointer on clickable elements
- ✅ Transitions are smooth (no flickering)

**Priority:** Low  
**Status:** ⬜ To Test

---

### TC-P-024: Visual States - Active States
**Objective:** Verify active states display correctly  
**Preconditions:** Desktop browser  
**Test Steps:**
1. Navigate to `/patient/notifications`
2. Observe active elements

**Expected Result:**
- ✅ Current page number has distinct active style
- ✅ Active filter button is highlighted
- ✅ Disabled buttons appear grayed out
- ✅ Active states clearly differentiate from inactive

**Priority:** Medium  
**Status:** ⬜ To Test

---

### TC-P-025: Notification Icons and Colors
**Objective:** Verify notification type icons display correctly  
**Preconditions:** Patient has notifications of different types  
**Test Steps:**
1. Navigate to `/patient/notifications`
2. Observe notification icons

**Expected Result:**
- ✅ Appointment Confirmed: Calendar check icon, green background
- ✅ Appointment Reminder: Bell icon, yellow background
- ✅ Appointment Cancelled: Calendar X icon, red background
- ✅ Appointment Rescheduled: Calendar event icon, blue background
- ✅ Record Updated: File medical icon, blue background
- ✅ Icons are clearly visible and appropriate size

**Priority:** Medium  
**Status:** ⬜ To Test

---

## 1.6 Integration Tests - Patient Notifications

### TC-P-026: Header Notification Badge
**Objective:** Verify notification badge in header updates correctly  
**Preconditions:** Patient logged in  
**Test Steps:**
1. Note current unread count in header badge
2. Navigate to `/patient/notifications`
3. Mark a notification as read
4. Observe header badge

**Expected Result:**
- ✅ Header badge shows correct initial count
- ✅ After marking as read, badge decreases by 1
- ✅ Badge disappears when count reaches 0
- ✅ Badge reappears when unread notifications exist

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-P-027: Header Notification Dropdown
**Objective:** Verify notification dropdown shows recent notifications  
**Preconditions:** Patient has at least 5 notifications  
**Test Steps:**
1. Click bell icon in header
2. Observe dropdown

**Expected Result:**
- ✅ Dropdown shows 5 most recent notifications
- ✅ Each shows icon, title, and brief message
- ✅ Timestamp displayed for each
- ✅ "View All Notifications" link at bottom
- ✅ Clicking link navigates to full page
- ✅ Clicking notification marks it as read

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-P-028: Auto-Refresh Functionality
**Objective:** Verify notification dropdown auto-refreshes  
**Preconditions:** Patient logged in, have another user send notification  
**Test Steps:**
1. Open patient page
2. Wait 30 seconds
3. Observe if new notifications appear in dropdown

**Expected Result:**
- ✅ Dropdown refreshes every 30 seconds
- ✅ Badge count updates automatically
- ✅ New notifications appear without page reload
- ✅ No errors in console

**Priority:** Medium  
**Status:** ⬜ To Test

---

### TC-P-029: Real-time Notification Creation
**Objective:** Verify new notifications appear when created  
**Preconditions:** Two-user test setup (Staff/Admin + Patient)  
**Test Steps:**
1. Patient navigates to `/patient/notifications`
2. Staff/Admin approves an appointment request for this patient
3. Patient refreshes page

**Expected Result:**
- ✅ New notification appears in list
- ✅ Notification is unread (blue highlight)
- ✅ Unread count increases by 1
- ✅ Total count increases
- ✅ Notification appears at top of list

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-P-030: Navigation Persistence
**Objective:** Verify browser back/forward works with filters  
**Preconditions:** Patient on notifications page  
**Test Steps:**
1. Navigate to `/patient/notifications`
2. Click "Unread" filter
3. Click page 2
4. Click browser back button
5. Click browser forward button

**Expected Result:**
- ✅ Back button returns to page 1 with unread filter
- ✅ Back again returns to all notifications
- ✅ Forward button navigates forward correctly
- ✅ Filter and page state preserved
- ✅ No duplicate loads or errors

**Priority:** Medium  
**Status:** ⬜ To Test

---

# 2. Staff Notification System Tests

## 2.1 Functional Tests - Staff Notifications

### TC-S-001: View Pending Requests
**Objective:** Verify staff can view pending appointment requests  
**Preconditions:** Staff user logged in  
**Test Steps:**
1. Navigate to `/staff/notifications`

**Expected Result:**
- ✅ Page loads successfully
- ✅ Pending appointment requests displayed
- ✅ Each request shows: patient name, service, date/time, type
- ✅ Requests ordered by creation date (newest first)
- ✅ Action buttons visible (Approve/Deny)

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-S-002: Empty Pending Requests State
**Objective:** Verify empty state when no pending requests  
**Preconditions:** No pending appointment requests  
**Test Steps:**
1. Navigate to `/staff/notifications`

**Expected Result:**
- ✅ Empty state message displayed
- ✅ No table/list shown
- ✅ Message indicates no pending requests
- ✅ Page renders without errors

**Priority:** Medium  
**Status:** ⬜ To Test

---

### TC-S-003: Approve Walk-in Request
**Objective:** Verify staff can approve walk-in appointment request  
**Preconditions:** 
- Staff logged in
- At least one pending walk-in request exists

**Test Steps:**
1. Navigate to `/staff/notifications`
2. Locate a walk-in request
3. Click "Approve" button
4. Enter duration if required
5. Confirm approval

**Expected Result:**
- ✅ Success message displayed
- ✅ Request removed from pending list
- ✅ Appointment created in system
- ✅ Patient receives notification
- ✅ Request status updated to "Approved"
- ✅ Reviewer and review time recorded

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-S-004: Approve Reschedule Request
**Objective:** Verify staff can approve reschedule request  
**Preconditions:**
- Staff logged in
- Pending reschedule request exists

**Test Steps:**
1. Navigate to `/staff/notifications`
2. Locate a reschedule request
3. Click "Approve" button
4. Confirm approval

**Expected Result:**
- ✅ Success message displayed
- ✅ Request removed from pending list
- ✅ Old appointment cancelled
- ✅ New appointment created
- ✅ Patient receives TWO notifications:
  - Old appointment cancelled
  - New appointment confirmed
- ✅ Request status updated to "Approved"

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-S-005: Deny Appointment Request
**Objective:** Verify staff can deny appointment request  
**Preconditions:**
- Staff logged in
- At least one pending request exists

**Test Steps:**
1. Navigate to `/staff/notifications`
2. Click "Deny" button on a request
3. Enter denial reason
4. Confirm denial

**Expected Result:**
- ✅ Denial modal appears with reason field
- ✅ Reason field is required
- ✅ After submission, success message shown
- ✅ Request removed from pending list
- ✅ Patient receives denial notification with reason
- ✅ Request status updated to "Denied"
- ✅ Denial reason saved

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-S-006: Approve Request - Validation
**Objective:** Verify validation on approval  
**Preconditions:** Staff logged in, pending walk-in request  
**Test Steps:**
1. Navigate to `/staff/notifications`
2. Try to approve walk-in without required duration

**Expected Result:**
- ✅ Validation error shown
- ✅ Duration field highlighted
- ✅ Appropriate error message
- ✅ Request not processed until valid

**Priority:** Medium  
**Status:** ⬜ To Test

---

### TC-S-007: Deny Request - Cancel
**Objective:** Verify canceling denial works  
**Preconditions:** Staff logged in, pending request exists  
**Test Steps:**
1. Navigate to `/staff/notifications`
2. Click "Deny" button
3. Click "Cancel" in modal

**Expected Result:**
- ✅ Modal closes
- ✅ Request remains in pending list
- ✅ No changes made to request
- ✅ No notification sent

**Priority:** Low  
**Status:** ⬜ To Test

---

### TC-S-008: Process Already Processed Request
**Objective:** Verify error when trying to process already processed request  
**Preconditions:** Request already approved/denied  
**Test Steps:**
1. Try to approve/deny an already processed request

**Expected Result:**
- ✅ Error message: "This request has already been processed"
- ✅ Request not processed again
- ✅ No duplicate appointments created
- ✅ No additional notifications sent

**Priority:** High  
**Status:** ⬜ To Test

---

## 2.2 UI/UX Tests - Staff Notifications

### TC-S-009: Request Details Display
**Objective:** Verify all request details displayed correctly  
**Preconditions:** Staff logged in, pending requests exist  
**Test Steps:**
1. Navigate to `/staff/notifications`
2. Observe request cards/rows

**Expected Result:**
- ✅ Patient name and info displayed
- ✅ Service name shown (if applicable)
- ✅ "Other" shown for custom concerns
- ✅ Requested date and time formatted correctly
- ✅ Request type badge (Walk-in/Reschedule)
- ✅ Duration shown
- ✅ Reason/notes visible
- ✅ For reschedule: old appointment info shown

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-S-010: Responsive Design - Staff Notifications
**Objective:** Verify staff notifications responsive on mobile  
**Preconditions:** Mobile viewport  
**Test Steps:**
1. Set viewport to mobile size
2. Navigate to `/staff/notifications`

**Expected Result:**
- ✅ Layout adjusts to mobile
- ✅ Request cards stack properly
- ✅ Action buttons accessible
- ✅ All information visible without horizontal scroll
- ✅ Touch-friendly button sizes

**Priority:** Medium  
**Status:** ⬜ To Test

---

## 2.3 Integration Tests - Staff Notifications

### TC-S-011: Notification Creation for Patient
**Objective:** Verify patient receives notification when request approved  
**Preconditions:** Two-user test (Staff + Patient)  
**Test Steps:**
1. Patient submits appointment request
2. Staff approves request
3. Check patient notifications

**Expected Result:**
- ✅ Patient receives "Appointment Request Approved" notification
- ✅ Notification shows correct date/time
- ✅ Notification marked as unread
- ✅ Patient badge count increases
- ✅ Notification appears in dropdown

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-S-012: Notification for Denied Request
**Objective:** Verify patient receives notification when request denied  
**Preconditions:** Two-user test (Staff + Patient)  
**Test Steps:**
1. Patient submits appointment request
2. Staff denies request with reason
3. Check patient notifications

**Expected Result:**
- ✅ Patient receives "Appointment Request Denied" notification
- ✅ Denial reason included in notification
- ✅ Notification marked as unread
- ✅ Patient can view full details

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-S-013: Dual Notification for Reschedule
**Objective:** Verify patient receives both notifications for reschedule  
**Preconditions:** Patient has existing appointment, submits reschedule  
**Test Steps:**
1. Patient submits reschedule request
2. Staff approves reschedule
3. Check patient notifications

**Expected Result:**
- ✅ Patient receives TWO notifications:
  1. "Previous Appointment Cancelled"
  2. "Appointment Request Approved"
- ✅ Both notifications unread
- ✅ Old appointment shows cancelled status
- ✅ New appointment shows confirmed status
- ✅ Badge count increases by 2

**Priority:** High  
**Status:** ⬜ To Test

---

# 3. Admin Notification System Tests

## 3.1 Functional Tests - Admin Notifications

### TC-A-001: View Pending Requests (Admin)
**Objective:** Verify admin can view pending appointment requests  
**Preconditions:** Admin user logged in  
**Test Steps:**
1. Navigate to `/admin/notifications`

**Expected Result:**
- ✅ Page loads successfully
- ✅ Same functionality as staff notifications
- ✅ Pending requests displayed correctly
- ✅ All request details visible

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-A-002: Approve Request (Admin)
**Objective:** Verify admin can approve requests  
**Preconditions:** Admin logged in, pending request exists  
**Test Steps:**
1. Navigate to `/admin/notifications`
2. Approve a request

**Expected Result:**
- ✅ Same behavior as staff approval
- ✅ Request approved successfully
- ✅ Patient notified
- ✅ Admin recorded as reviewer

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-A-003: Deny Request (Admin)
**Objective:** Verify admin can deny requests  
**Preconditions:** Admin logged in, pending request exists  
**Test Steps:**
1. Navigate to `/admin/notifications`
2. Deny a request with reason

**Expected Result:**
- ✅ Same behavior as staff denial
- ✅ Request denied successfully
- ✅ Patient notified with reason
- ✅ Admin recorded as reviewer

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-A-004: Admin vs Staff Permissions
**Objective:** Verify admin has same notification permissions as staff  
**Preconditions:** Admin and staff accounts  
**Test Steps:**
1. Login as admin
2. Test all notification operations
3. Compare with staff capabilities

**Expected Result:**
- ✅ Admin has all staff notification capabilities
- ✅ No additional restrictions
- ✅ Same UI and functionality
- ✅ Admin activity logged separately

**Priority:** Medium  
**Status:** ⬜ To Test

---

# 4. Cross-Role Integration Tests

## 4.1 End-to-End Workflow Tests

### TC-I-001: Complete Walk-in Appointment Flow
**Objective:** Test complete walk-in appointment workflow  
**Preconditions:** Patient, Staff/Admin accounts ready  
**Test Steps:**
1. **Patient**: Submit walk-in appointment request
2. **Staff**: Navigate to notifications, see new request
3. **Staff**: Approve request with duration
4. **Patient**: Check notifications
5. **Patient**: View appointment in calendar

**Expected Result:**
- ✅ Request appears in staff notifications immediately
- ✅ Approval creates appointment
- ✅ Patient receives confirmation notification
- ✅ Appointment visible in patient calendar
- ✅ All data consistent across system
- ✅ Activity logged

**Priority:** Critical  
**Status:** ⬜ To Test

---

### TC-I-002: Complete Reschedule Flow
**Objective:** Test complete reschedule workflow  
**Preconditions:** Patient has existing appointment  
**Test Steps:**
1. **Patient**: Submit reschedule request
2. **Staff**: See reschedule request with old appointment details
3. **Staff**: Approve reschedule
4. **Patient**: Check notifications (should have 2)
5. **Patient**: View calendar

**Expected Result:**
- ✅ Old appointment cancelled
- ✅ New appointment created
- ✅ Patient receives 2 notifications
- ✅ Calendar updated correctly
- ✅ No orphaned appointments
- ✅ All statuses correct

**Priority:** Critical  
**Status:** ⬜ To Test

---

### TC-I-003: Denial Flow
**Objective:** Test complete denial workflow  
**Preconditions:** Patient and Staff accounts  
**Test Steps:**
1. **Patient**: Submit appointment request
2. **Staff**: Deny request with detailed reason
3. **Patient**: View denial notification
4. **Patient**: Read denial reason

**Expected Result:**
- ✅ Patient receives denial notification
- ✅ Reason clearly displayed
- ✅ No appointment created
- ✅ Request marked as denied
- ✅ Patient can submit new request

**Priority:** High  
**Status:** ⬜ To Test

---

## 4.2 Concurrent User Tests

### TC-I-004: Multiple Staff Processing
**Objective:** Verify system handles multiple staff users  
**Preconditions:** Two staff users logged in  
**Test Steps:**
1. Staff A views pending requests
2. Staff B views pending requests
3. Staff A approves a request
4. Staff B tries to approve same request

**Expected Result:**
- ✅ Both staff see same pending requests
- ✅ After Staff A approves, request disappears
- ✅ Staff B sees error if trying to process same request
- ✅ No duplicate appointments created
- ✅ Only one notification sent to patient

**Priority:** High  
**Status:** ⬜ To Test

---

### TC-I-005: Patient Notification Load Test
**Objective:** Verify pagination handles large notification counts  
**Preconditions:** Patient account with 100+ notifications  
**Test Steps:**
1. Create 100+ notifications for test patient
2. Login as patient
3. Navigate through notification pages

**Expected Result:**
- ✅ All pages load without errors
- ✅ Pagination controls work smoothly
- ✅ No timeouts or slow queries
- ✅ Counts are accurate
- ✅ Filters work on all pages

**Priority:** Medium  
**Status:** ⬜ To Test

---

## 4.3 Security Tests

### TC-I-006: Authorization - Patient Cannot Access Others' Notifications
**Objective:** Verify patients can only see their own notifications  
**Preconditions:** Two patient accounts  
**Test Steps:**
1. Login as Patient A
2. Try to access Patient B's notifications (URL manipulation)

**Expected Result:**
- ✅ Patient A only sees their notifications
- ✅ Cannot view other patient notifications via URL
- ✅ API returns only authorized notifications
- ✅ No information leakage

**Priority:** Critical  
**Status:** ⬜ To Test

---

### TC-I-007: CSRF Protection
**Objective:** Verify CSRF protection on notification actions  
**Preconditions:** Valid user session  
**Test Steps:**
1. Attempt notification actions without CSRF token
2. Attempt with invalid CSRF token

**Expected Result:**
- ✅ Actions rejected without valid CSRF token
- ✅ 419 error returned
- ✅ No state changes occur
- ✅ Security log entry created

**Priority:** Critical  
**Status:** ⬜ To Test

---

### TC-I-008: Staff/Admin Cannot Access Patient Notification Page
**Objective:** Verify role-based access control  
**Preconditions:** Staff/Admin account  
**Test Steps:**
1. Login as staff/admin
2. Try to access `/patient/notifications`

**Expected Result:**
- ✅ Access denied (403 error)
- ✅ Redirected to appropriate page
- ✅ Error message shown
- ✅ No data exposed

**Priority:** High  
**Status:** ⬜ To Test

---

## 4.4 Performance Tests

### TC-I-009: Page Load Performance
**Objective:** Verify notification pages load within acceptable time  
**Preconditions:** Test data loaded  
**Test Steps:**
1. Clear cache
2. Navigate to notification page
3. Measure load time

**Expected Result:**
- ✅ Patient notifications page loads < 2 seconds
- ✅ Staff notifications page loads < 2 seconds
- ✅ Pagination queries optimized (N+1 avoided)
- ✅ No unnecessary database calls

**Priority:** Medium  
**Status:** ⬜ To Test

---

### TC-I-010: Database Query Optimization
**Objective:** Verify efficient database queries  
**Preconditions:** Database query logging enabled  
**Test Steps:**
1. Enable query log
2. Load notification page
3. Review queries executed

**Expected Result:**
- ✅ Eager loading used (with() relationships)
- ✅ No N+1 query problems
- ✅ Indexed columns used in WHERE clauses
- ✅ Query count reasonable (< 10 per page)

**Priority:** Medium  
**Status:** ⬜ To Test

---

# Test Execution Summary

## By Priority

### Critical Priority Tests: 4
- TC-I-001: Complete Walk-in Appointment Flow
- TC-I-002: Complete Reschedule Flow
- TC-I-006: Authorization - Patient Cannot Access Others' Notifications
- TC-I-007: CSRF Protection

### High Priority Tests: 37
- All patient pagination tests (TC-P-001 to TC-P-030)
- Staff approval/denial tests (TC-S-001 to TC-S-008)
- Admin tests (TC-A-001 to TC-A-003)
- Integration tests (TC-I-003, TC-I-004, TC-I-008, TC-I-011, TC-I-012, TC-I-013)

### Medium Priority Tests: 15
- UI/UX responsive tests
- Edge case tests
- Performance tests

### Low Priority Tests: 2
- TC-S-007: Deny Request - Cancel
- TC-P-023: Visual States - Hover Effects

## Total Test Cases: 58

---

# Test Environment Setup

## Required Test Accounts

1. **Patient Account**
   - Username: `test_patient@clinic.com`
   - Role: Patient (role_id: 3)
   - Has appointments and notifications

2. **Staff Account**
   - Username: `test_staff@clinic.com`
   - Role: Staff (role_id: 2)
   - Can approve/deny requests

3. **Admin Account**
   - Username: `test_admin@clinic.com`
   - Role: Admin (role_id: 1)
   - Full system access

## Test Data Requirements

1. **Notifications**: 100+ for pagination testing
2. **Appointment Requests**: Mix of pending, approved, denied
3. **Appointments**: Mix of confirmed, cancelled, completed
4. **Services**: At least 5 different services

## Browser Requirements

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

---

# Test Execution Checklist

- ⬜ Set up test environment
- ⬜ Create test accounts
- ⬜ Seed test data
- ⬜ Execute Critical tests first
- ⬜ Execute High priority tests
- ⬜ Execute Medium priority tests
- ⬜ Execute Low priority tests
- ⬜ Document all failures
- ⬜ Retest failed cases
- ⬜ Generate test report

---

**Document Version:** 1.0  
**Last Updated:** October 30, 2025  
**Created By:** AI Testing Framework  
**Status:** Ready for Execution

