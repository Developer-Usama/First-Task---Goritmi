# Technical Interview Task: Web Development

## Objective
This project implements a basic authentication system with clean, organized code and functionality using:
- **Bootstrap** for responsive design.
- **JavaScript** for client-side validation.
- **PHP** for backend processing.
- **MySQL** as the database backend.

---

## Features

### 1. User Registration
- A registration form with fields:
  - Name
  - Email
  - Phone
  - Gender
  - Password
  - Confirm Password
- **Client-Side Validation:**
  - Ensures all fields are filled out.
  - Validates email format.
  - Ensures password matches the "Confirm Password" field.
  - Displays error messages on validation failure.
- **Backend Processing:**
  - Securely stores user data with hashed passwords.
  - Sends a welcome email upon successful registration.

### 2. Login System
- Login form accepts:
  - Name or Email
  - Password
- Validates credentials against the database.
- Redirects users to the Dashboard upon successful login.
- Displays error messages for invalid credentials.

### 3. Dashboard
- Displays the logged-in user’s profile:
  - Name
  - Email
  - Phone
  - Gender

### 4. Password Reset
- "Forgot Password" functionality:
  - Prompts the user to enter their registered email.
  - Sends a password reset link to the provided email if it exists.
  - Allows users to set a new password via the link.
  - Updates the password in the database after validation.
- **Note:** The reset functionality is currently configured to work on my localhost setup in the `login.php` file on line 82.
-         $resetLink = "http://localhost/php%20work/Task%20(Goritmi)/pages/reset_password.php?token=$token";
  
  **You must update the reset password link path to match your live server or localhost setup.**

### 5. Logout
- A "Logout" button on the Dashboard:
  - Destroys the session.
  - Redirects users to the Login page.

---

## Technical Requirements
### Validation
- **Client-Side:** JavaScript ensures input correctness before submission.
- **Server-Side:** PHP performs additional validation.

### Email Sending
- Uses **PHPMailer** for:
  - Welcome emails.
  - Password reset instructions.
- **Note:** My email address has been used in the email configuration settings.  
  **You need to replace it with your own email credentials.**

### Responsive Design
- All pages are mobile-friendly using Bootstrap.

### Database Configuration
- A single file (`config/db_conn.php`) manages database connectivity.

---

## Setup Instructions

### 1. Clone the Repository
```bash
git clone https://github.com/Developer-Usama/Project---Goritmi.git

