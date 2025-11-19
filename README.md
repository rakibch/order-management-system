📘 Order Management System – REST API (Laravel 12)

A scalable, modular API backend for product, inventory, and order management.
Implements a clean architecture using Services, Repositories, Events, Jobs, and API Versioning.

🚀 Features
🔐 Authentication & Authorization

JWT authentication (login, register, refresh, logout)

Role-based access:

Admin → Full access

Vendor → Manage own products

Customer → Place orders

Auth-protected routes using Bearer {token}

📦 Product & Inventory Management

Product CRUD

Product Variants

Real-time inventory deduction and restore

Low stock detection

Low stock queue job

CSV bulk import (queued)

Repository pattern for products

Inventory service for stock operations

🛒 Order Processing

Create orders with multiple items

Status workflow:

pending → processing → shipped → delivered → cancelled


Stock deduction when order is created

Stock restore on cancellation

Events fired:

OrderCreated

OrderCancelled

Email notifications (queued)

Invoice generation (future-ready)

🏛 Clean Architecture
Controllers → Services → Repositories → Models


Repository Pattern

Service Layer

Events & Listeners

Queue Jobs

API Versioning /api/v1

Database Transactions for all critical operations

📁 Project Structure
app/
 ├── Http/Controllers/Api/V1
 ├── Services/
 │    ├── ProductService.php
 │    ├── OrderService.php
 │    └── InventoryService.php
 ├── Repositories/
 │    ├── Contracts/
 │    └── Eloquent/
 ├── Jobs/
 ├── Events/
 ├── Listeners/
 ├── Models/
routes/
 └── api.php
docs/
 ├── api.yaml (Swagger)
 └── postman_collection.json

🔧 Installation
git clone https://github.com/your/repository.git
cd project-folder
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan jwt:secret


Run server:

php artisan serve


Run queue worker:

php artisan queue:work

🔐 Authentication Guide
Register
POST /api/v1/register

Login
POST /api/v1/login


Response contains token:

{
  "token": "your_jwt_token"
}


Use it in all protected requests:

Authorization: Bearer YOUR_TOKEN

Refresh Token
POST /api/v1/refresh

Logout
POST /api/v1/logout

📘 API Documentation
📄 Swagger (OpenAPI)

File location:

docs/api.yaml


To view:

Open https://editor.swagger.io

Import api.yaml

UI will render automatically

Export to Postman

In Swagger → Generate Collection → Postman

📤 Postman Collection

Included:

docs/postman_collection.json


You can import it directly in Postman.

📥 Bulk Product Import (CSV)
Endpoint:
POST /api/v1/products/import

Body:

file: products.csv

CSV Example:
name,sku,price,description,vendor_id
Mobile,MB123,500,Smartphone,1
Laptop,LP456,900,Gaming Laptop,2


Uploaded CSV → queued for processing → each product created with variants.

🔔 Low Stock Alerts

Triggered automatically when:

stock <= low_stock_threshold


Flow:

InventoryService dispatches event

Job queued

Email notification sent

🛒 Order Workflow
Create Order
POST /api/v1/orders

Cancel Order
POST /api/v1/orders/{id}/cancel

Stock Handling

Deduct stock on create

Restore stock on cancel

🧪 Testing
Feature Tests
php artisan test

Authentication Required

POST /products

PUT /products/{id}

DELETE /products/{id}

POST /orders

POST /orders/{id}/cancel

/me, /refresh, /logout

Public Endpoints

GET /products

GET /products/{id}

🔑 Environment Variables

Copy from .env.example. Important:

JWT_SECRET=
QUEUE_CONNECTION=database
MAIL_MAILER=smtp

🛠 Technologies

Laravel 12

PHP 8.2+

MySQL

JWT Auth

Queue Jobs

Events & Listeners

Repository Pattern

Swagger / Postman

👨‍💻 Author

Your Name
Email: rakib9204@gmail.com

GitHub: https://github.com/rakibch/
