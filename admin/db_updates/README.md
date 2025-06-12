# Release Approval and Rejection System

This update adds functionality to approve or reject releases with detailed feedback.

## Features Added

1. **Approve Release**: Allows admins to approve releases and assign UPC/EAN codes and ISRCs
2. **Reject Release**: Allows admins to reject releases with a detailed reason
3. **View Rejection Reason**: Displays the rejection reason on the release details page

## Database Update Required

Before using the new rejection functionality, you need to update the database schema to add the `rejection_reason` column to the `album` table.

### How to Update the Database

1. Log in as an administrator
2. Navigate to `/admin/db_updates/update_database.php`
3. Click the "Update Database" button
4. You should see a success message if the update was applied correctly

### Manual Database Update (if needed)

If the automatic update doesn't work, you can manually execute the following SQL:

```sql
ALTER TABLE album ADD COLUMN rejection_reason TEXT NULL COMMENT "Reason for rejection when status is 2";
```

## How to Use

### Approving a Release

1. Navigate to the release details page
2. If the release is in "Checking" or "Draft" status, you'll see an "Approve Release" button
3. Click the button to go to the approval page
4. Enter the UPC/EAN code and ISRCs for tracks (if needed)
5. Click "Approve Release" to complete the process

### Rejecting a Release

1. Navigate to the release details page
2. If the release is in "Checking" or "Draft" status, you'll see a "Reject Release" button
3. Click the button to go to the rejection page
4. Enter a detailed reason for the rejection
5. Click "Reject Release" to complete the process

The rejection reason will be visible on the release details page when viewing rejected releases.