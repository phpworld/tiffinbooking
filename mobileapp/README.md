# Tiffin Delight Mobile App

A mobile application for Tiffin Delight built using HTML, jQuery, and Axios with REST API.

## Overview

This mobile app allows users to:
- Browse available dishes
- Place orders
- Track order status
- Manage their profile
- View order history
- Add reviews for delivered orders
- Manage wallet balance

## Technical Details

- **Frontend**: HTML5, CSS3, Bootstrap 5, jQuery
- **API Communication**: Axios
- **Authentication**: JWT (JSON Web Tokens)
- **Data Storage**: Local Storage for user data and token

## Project Structure

```
mobileapp/
├── css/
│   └── style.css
├── img/
│   └── logo.png
├── js/
│   ├── app.js
│   ├── auth.js
│   └── config.js
├── index.html
└── README.md
```

## API Endpoints

The app communicates with the following API endpoints:

### Authentication
- `POST /api/auth/login` - User login
- `POST /api/auth/register` - User registration

### User Profile
- `GET /api/user/profile` - Get user profile
- `POST /api/user/update-profile` - Update user profile

### Dishes
- `GET /api/dishes` - Get all dishes
- `GET /api/dishes/{id}` - Get dish details

### Cart
- `GET /api/cart` - Get cart contents
- `POST /api/cart/add` - Add item to cart
- `POST /api/cart/update` - Update cart item
- `POST /api/cart/remove` - Remove item from cart
- `POST /api/cart/clear` - Clear cart

### Bookings
- `POST /api/booking/place-order` - Place a new order
- `GET /api/bookings` - Get user bookings
- `GET /api/bookings/{id}` - Get booking details
- `POST /api/bookings/cancel/{id}` - Cancel a booking

### Reviews
- `POST /api/reviews/add` - Add a review
- `GET /api/reviews/dish/{id}` - Get reviews for a dish

### Wallet
- `GET /api/wallet` - Get wallet details
- `POST /api/wallet/recharge` - Recharge wallet

## Converting to APK

To convert this HTML app to an APK, you can use tools like:

1. **Apache Cordova**: An open-source mobile development framework that allows you to use standard web technologies for cross-platform development.

2. **PhoneGap**: A distribution of Apache Cordova that provides additional tools and services.

3. **Capacitor**: A cross-platform app runtime that makes it easy to build web apps that run natively on iOS, Android, and the web.

4. **WebView-based wrappers**: Simple Android apps that use WebView to display the HTML content.

## Steps to Convert to APK

1. Install Node.js and npm
2. Install Cordova: `npm install -g cordova`
3. Create a Cordova project: `cordova create tiffin-app`
4. Replace the www folder content with this project's files
5. Add Android platform: `cordova platform add android`
6. Build the APK: `cordova build android`
7. The APK will be generated in the `platforms/android/app/build/outputs/apk/debug` directory

## Configuration

Before building the APK, make sure to update the API base URL in `js/config.js` to point to your production server.

## Security Considerations

- The app uses JWT for authentication
- Tokens are stored in local storage
- HTTPS should be used for all API communications
- Input validation is performed on both client and server sides
