# Yeh Mera India - Tour Management System 🌍

Yeh Mera India is a PHP-based web application for managing tour bookings across India. This system allows users to book tours online, pay for them securely via Razorpay (in test mode), and receive booking confirmation via email. The admin panel helps administrators manage and approve tour requests efficiently.

## Features 🚀

### User Authentication & Email Verification:
- Users are required to verify their email before making any tour bookings to ensure account security.

### Tour Booking with Razorpay Payment Gateway:
- Integrated Razorpay (Test Mode) to handle payments during tour booking, allowing users to securely pay for their trips.

### Admin Panel:
- Admins can view and manage all tour requests, approve or reject bookings, and track tour statuses easily.

### Responsive Design:
- The web app is mobile-friendly and responsive, providing an optimized experience across all devices.

## Technologies Used 🛠️

- **PHP**: Server-side scripting for handling bookings, user management, and email notifications.
- **MySQL**: Database management system to store user data, tour details, and booking information.
- **Razorpay (Test Mode)**: Payment gateway for secure transactions during the tour booking process.
- **Email Integration**: Sends email notifications to users during signup, email verification, and after successful bookings.

## Installation & Setup 🖥️

### Clone the repository:
```bash
git clone https://github.com/your-username/YehMeraIndia.git
```

### Navigate to the project directory: 
```bash
cd YehMeraIndia
```

## Import the MySQL database:
- Import the `tour_management.sql` file in your MySQL database.

## Update your configuration:
- In the `config.php` file, set your database credentials and email SMTP settings.

## Run the application on your local server:
- Use local development environments like XAMPP, MAMP, or WAMP.

## To test Razorpay payments:
- Set up your test credentials in the Razorpay configuration.

## Screenshots 📸
- Example of the tour booking page with payment integration.

## How It Works ⚙️

### User Sign Up & SignIn:
- Users sign up for an account, and must signin proceeding.

### Tour Booking:
- Users can browse available tours and book a trip, entering their details and completing the payment via Razorpay (test mode).

### Admin Dashboard:
- Admins log in to view and manage all tour requests, with the ability to approve or reject bookings.

### Email Notifications:
- Automated emails are sent to users for email verification and successful bookings.

## Future Enhancements 🔮
- Switch to live mode for Razorpay payments.
- Add user reviews and ratings for each tour.
- Implement dynamic pricing based on demand and availability.

## License 📄
This project is licensed under the MIT License.

## Contact 📬
For any queries or suggestions, feel free to contact me at [yashodipbeldar@gmail.com].

