# 🍴 Restaurant Management System (PHP + MySQL)

A complete **Restaurant Management System** built using **PHP (Procedural)**, **MySQL**, and **Bootstrap**.  
This project manages restaurant operations including **Customer Orders, Admin Reports, Chefs Panel, Authentication, Billing (PDF)** and more.

---

## 🚀 Features

### 🔑 Authentication & Authorization
- Secure login system (Admin, Customer, Chef).
- Role-based access control using `auth_guard.php`.
- Passwords stored in hashed format.
- Session-based authentication.

### 👨‍🍳 Roles & Dashboards
- **Admin Dashboard**
  - Manage all menu items.
  - Manage users (customers/chefs).
  - Track and update orders.
  - Generate **Sales Reports & Analytics**:
    - Daily sales chart 📊
    - Top selling items 🍕
    - Order status distribution chart ✅
- **Customer Dashboard**
  - Browse food menu.
  - Place orders easily.
  - Track order status (Placed → Preparing → Ready → Completed).
  - Download PDF invoice for each order 💳.
- **Chef Dashboard**
  - View orders assigned to kitchen.
  - Update preparation status (preparing → ready → completed).

### 📦 Order Management
- Customers can place multiple items in one order.
- Orders linked with `order_items` table for itemized bill.
- Real-time order tracking with status updates.

### 📝 Billing (PDF Invoice)
- Uses **FPDF** library.
- Generates professional **Restaurant Bill Invoice**.
- Includes:
  - Restaurant name & logo.
  - Order details (ID, date, customer name).
  - Itemized breakdown (Qty × Item × Price).
  - Grand total.
  - Auto-download as PDF.

### 📊 Reports & Analytics
- Admin can view sales reports:
  - Sales per day.
  - Top ordered food items.
  - Order status distribution.
- Integrated with **Chart.js** for interactive graphs.

---

## 🛠️ Tech Stack
- **Frontend**: HTML, CSS, Bootstrap
- **Backend**: PHP (Procedural)
- **Database**: MySQL
- **PDF Generation**: FPDF
- **Charts**: Chart.js
- **Server**: XAMPP / LAMP / WAMP

---

## 📂 Project Structure

