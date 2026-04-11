# 📘 REST API Basics (Native PHP)

## 📌 Overview

This guide introduces the **fundamentals of REST API development using native PHP (no frameworks)**.
By the end, you will be able to create a simple API that can **Create, Read, Update, and Delete (CRUD)** data from a MySQL database.

---

## 🎯 Learning Objectives

Students should be able to:

* Understand what an API is
* Explain how REST works
* Use HTTP methods (GET, POST, PUT, DELETE)
* Build a simple REST API using native PHP
* Connect PHP to a MySQL database
* Test API endpoints using tools like Postman

---

## 🧠 What is an API?

An **API (Application Programming Interface)** allows different software systems to communicate.

Example:

```
Client (App) → API → Database → API → Client
```

---

## 🌐 What is REST API?

A **REST API** is a type of API that:

* Uses HTTP requests
* Works with resources (e.g., products, users)
* Returns data in JSON format

---

## 📡 HTTP Methods

| Method | Description     |
| ------ | --------------- |
| GET    | Retrieve data   |
| POST   | Create new data |
| PUT    | Update data  (POST)   |
| DELETE | Remove data     |

---

## 🔗 Example Endpoints

```
GET    /api/products
GET    /api/products?id=1
POST   /api/products
PUT    /api/products
DELETE /api/products?id=1
```

---

## 🗄️ Database Setup (MySQL)

```sql
CREATE DATABASE exampledb;
USE exampledb;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

---

## 📁 Project Structure

```
/api-project
  ├── index.php
  ├── db.php
  ├── products.php
```

---

## ⚙️ Running the API

### Step 1: Start PHP Server

```
php -S localhost:8000 index.php
```

### Step 2: Open in Browser

```
http://localhost:8000/api/products
```

---

## 🧪 Testing the API

Use:

* Postman (recommended)
* Browser (for GET requests only)

---

## 🔐 Basic Security Practices

* Validate all inputs
* Use prepared statements
* Avoid SQL injection
* Sanitize user data

---

## 🔄 API Flow

```
Request → index.php → products.php → Database → JSON Response
```

---

## 📝 Activities / Exercises

1. Create your own API for:

   * Students
   * Orders
   * Inventory

2. Add fields:

   * Quantity
   * Category

3. Modify endpoints:

   * Add filtering (e.g., ?price=100)

---

## 💡 Tips

* Always return JSON responses
* Keep endpoints simple and clear
* Test frequently using Postman
* Start small, then improve

---

## 🚀 Next Steps

After this lesson, you can explore:

* Authentication (Login API)
* Token-based security (JWT)
* Connecting API to mobile apps (Flutter)

---

## 👨‍💻 Author Note

This module is designed to help students understand **how APIs work internally**, without relying on frameworks like Laravel.

---
