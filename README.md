# Germ Zero – Online Purchasing and Management System

A university group project to build a complete online platform for managing and scheduling sanitation product purchases and service bookings.

---

👥 Team Members

- **Mavindu Wijesekara** – Project Manager / Risk Manager  
- **Nusara Jayakodi** – Start-up Manager / Schedule Manager  
- **Buddini Alwis** – Quality Manager  

---

📌 Project Overview

Germ Zero is a web-based platform that allows customers to purchase sanitation-related items and request services online.

It includes:
- A responsive UI for product browsing and purchasing  
- A secure login system using Flask and bcrypt  
- An admin dashboard with role-based access control  
- MySQL database integration for managing products, orders, and users  
- Cash on Delivery (COD) and slip upload system instead of online payment

---

🛠️ Technologies Used

| Layer       | Tools/Frameworks           |
|-------------|-----------------------------|
| Frontend    | HTML, CSS                   |
| Backend     | Flask (Python)              |
| Database    | MySQL                       |
| Authentication | Flask-Login, Flask-Bcrypt   |
| Version Control | GitHub                      |

---

 🚀 Main Features

- 💻 **User Interface**: Users can browse products and place orders  
- 🔐 **Secure Login System**: Authenticated access for users and admins using Flask  
- 📊 **Admin Dashboard**: Manage products, customer orders, and availability  
- 💵 **Order Payment**: Cash on Delivery (COD) and slip upload system  
- 📱 **Responsive Design**: Fully functional on both desktop and mobile devices  

---

 📂 Project Structure

```
germzero/
├── templates/         # HTML templates
├── static/            # CSS, JS, and image files
├── app/               # Python Flask backend
├── run.py             # App entry point
├── requirements.txt   # Python dependencies
└── README.md          # Project overview
```