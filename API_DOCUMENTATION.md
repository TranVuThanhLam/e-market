# E-Market API Documentation

## 📋 Tổng quan dự án

Dự án Laravel E-Market API đã được setup hoàn chỉnh với:
- ✅ Laravel 12 (latest version)
- ✅ Laravel Sanctum cho API Authentication
- ✅ RESTful API endpoints
- ✅ Models: User, Category, Product, Order, OrderItem
- ✅ Migrations đã chạy thành công
- ✅ Seeders với dữ liệu mẫu

## 🗂️ Database Schema

### Categories
- id, name, slug, description, image, is_active

### Products
- id, category_id, name, slug, description, price, sale_price, sku, stock, image, images, is_featured, is_active

### Orders
- id, user_id, order_number, subtotal, tax, shipping, total, status, payment_status, payment_method, shipping_address, notes

### Order Items  
- id, order_id, product_id, product_name, quantity, price, total

## 🚀 API Endpoints

### Base URL
```
http://localhost:8080/api
```

### Public Endpoints

#### Health Check
```http
GET /api/health
```
Response:
```json
{
  "status": "ok",
  "message": "E-Market API is running",
  "timestamp": "2025-10-16 07:00:00"
}
```

#### Register
```http
POST /api/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Login
```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

Response:
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {...},
    "access_token": "1|xxxxx",
    "token_type": "Bearer"
  }
}
```

### Protected Endpoints (Require Authentication)

#### Get Current User
```http
GET /api/user
Authorization: Bearer {token}
```

#### Logout
```http
POST /api/auth/logout
Authorization: Bearer {token}
```

#### Categories
```http
GET    /api/categories          - List all categories
POST   /api/categories          - Create category
GET    /api/categories/{id}     - Get category details
PUT    /api/categories/{id}     - Update category
DELETE /api/categories/{id}     - Delete category
```

#### Products
```http
GET    /api/products           - List all products (with pagination & filters)
POST   /api/products           - Create product
GET    /api/products/{id}      - Get product details
PUT    /api/products/{id}      - Update product
DELETE /api/products/{id}      - Delete product
```

Query Parameters for GET /api/products:
- `category_id` - Filter by category
- `search` - Search by name
- `featured` - Filter featured products
- `sort_by` - Sort field (default: created_at)
- `sort_order` - asc or desc (default: desc)
- `per_page` - Items per page (default: 15)

#### Orders
```http
GET    /api/orders            - List user's orders
POST   /api/orders            - Create new order
GET    /api/orders/{id}       - Get order details
PUT    /api/orders/{id}       - Update order (cancel only)
DELETE /api/orders/{id}       - Delete order (cancelled only)
```

Create Order Example:
```json
{
  "items": [
    {
      "product_id": 1,
      "quantity": 2
    }
  ],
  "shipping_address": "123 Main St, City",
  "payment_method": "credit_card",
  "notes": "Please deliver before 5pm"
}
```

## 🔧 Troubleshooting - API Routes không load

**Vấn đề hiện tại**: API routes chưa được load đúng cách trong Laravel 12.

**Giải pháp**:

### Cách 1: Sửa bootstrap/app.php (Đã thử - chưa work)
File hiện tại đang dùng cú pháp `then` closure nhưng routes vẫn chưa load.

### Cách 2: Kiểm tra config/app.php
Laravel 12 có thể cần config khác. Kiểm tra file config/app.php xem có cần thêm route service provider không.

### Cách 3: Downgrade về Laravel 11
Laravel 11 có routing ổn định hơn. Để downgrade:

```bash
docker compose exec app composer require "laravel/framework:^11.0"
docker compose exec app php artisan optimize:clear
```

### Cách 4: Dùng AppServiceProvider để register routes
Thêm vào `app/Providers/AppServiceProvider.php`:

```php
use Illuminate\Support\Facades\Route;

public function boot(): void
{
    Route::prefix('api')
        ->middleware('api')
        ->group(base_path('routes/api.php'));
}
```

## 📊 Dữ liệu mẫu đã có

### Users
- admin@example.com / password
- test@example.com / password

### Categories (5)
- Electronics
- Fashion  
- Home & Garden
- Sports
- Books

### Products (6)
- Laptop Dell XPS 15
- iPhone 15 Pro
- Sony WH-1000XM5 Headphones
- Nike Air Max Sneakers
- Levi's Denim Jacket
- Robot Vacuum Cleaner

## ⚙️ Commands hữu ích

```bash
# Clear all cache
docker compose exec app php artisan optimize:clear

# List all routes
docker compose exec app php artisan route:list

# Run migrations
docker compose exec app php artisan migrate

# Run seeders
docker compose exec app php artisan db:seed

# Refresh database với seeders
docker compose exec app php artisan migrate:fresh --seed

# Test API
curl http://localhost:8080/api/health
```

## 📝 Notes

1. **Controllers đã tạo**:
   - AuthController.php - Authentication
   - CategoryController.php - CRUD categories
   - ProductController.php - CRUD products  
   - OrderController.php - Order management

2. **Models đã cấu hình**:
   - User (with HasApiTokens)
   - Category (with products relationship)
   - Product (with category & orderItems)
   - Order (with user & items, auto order_number)
   - OrderItem (with order & product)

3. **Features đã implement**:
   - API Authentication với Sanctum
   - Product filtering & search
   - Order management với stock control
   - Auto calculate tax & shipping
   - CORS configuration

4. **Cần làm tiếp**:
   - Fix API routes loading issue
   - Test tất cả endpoints
   - Thêm validation rules chi tiết hơn
   - Implement file upload cho images
   - Thêm admin middleware cho protected endpoints
   - Rate limiting
   - API versioning

## 🔐 Security

- Passwords được hash bằng bcrypt
- API tokens quản lý bởi Sanctum
- CORS đã được config cho phép all origins (nên restrict trong production)
- SQL Injection prevention với Eloquent ORM

## 🎯 Next Steps

1. **Fix routing issue** - Ưu tiên cao nhất
2. **Test API với Postman/Insomnia**
3. **Thêm API documentation với Swagger/OpenAPI**
4. **Setup CI/CD**
5. **Add more features**: Cart, Wishlist, Reviews, etc.
