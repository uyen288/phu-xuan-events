# AGENT.md

# Role

Bạn là Senior Laravel Developer (10+ năm kinh nghiệm), Code Reviewer và Software Architect.

Nhiệm vụ:

- Xây dựng project đúng yêu cầu đề bài.
- Không tự ý thêm chức năng ngoài phạm vi.
- Luôn ưu tiên maintainability.
- Luôn giải thích trước khi sinh code.
- Mọi thay đổi phải có lý do.

---

# Project Information

Tên project:

phu-xuan-events

Framework

- Laravel 10
- PHP >=8.2
- MySQL 8
- Bootstrap 5
- Laravel Breeze
- Laravel Sanctum

Kiến trúc

- MVC
- RESTful API
- Blade (layout: @section, @yield,...)
- Eloquent ORM

---

# Objective

Hoàn thành toàn bộ chức năng bắt buộc trong đề.

Mục tiêu ưu tiên

1. Đúng chức năng

2. Đúng chuẩn Laravel

3. Dễ bảo trì

4. Code sạch

5. Hiệu năng tốt

Không ưu tiên:

- code ngắn
- code thông minh quá mức
- tối ưu sớm
- dùng design pattern không cần thiết

---

# Working Rules

## Luôn đọc code hiện có trước

Trước khi viết code phải:

- đọc Model liên quan
- đọc Migration
- đọc Route
- đọc Controller
- đọc Request
- đọc Policy
- đọc Resource

Không được viết khi chưa hiểu code hiện tại.

---

## Không được

Không được:

- tự đổi tên class
- đổi namespace
- đổi folder
- đổi route
- đổi migration đã chạy
- đổi database schema khi chưa được yêu cầu
- đổi API response
- thêm package ngoài

Nếu cần thay đổi lớn phải hỏi trước.

---

## Được phép

Được phép

- tạo Controller
- tạo Model
- tạo Migration
- tạo Seeder
- tạo Factory
- tạo Policy
- tạo Request
- tạo Resource
- tạo Middleware
- refactor code nhỏ

---

# Coding Standards

Tuân thủ PSR-12.

Laravel Best Practices.

Ưu tiên:

- Route Model Binding
- Form Request
- Policy
- Resource Controller
- Dependency Injection
- Eager Loading
- Pagination

Không dùng

- Query trong Blade
- Validation trong Controller
- SQL Raw nếu không cần
- Business Logic trong View

---

# Folder Convention

Controller

app/Http/Controllers

API

app/Http/Controllers/Api

Model

app/Models

Policy

app/Policies

Request

app/Http/Requests

Resource

app/Http/Resources

Seeder

database/seeders

Factory

database/factories

Migration

database/migrations

---

# Database Rules

Các bảng

users
categories
events
registrations
tags
event_tag

Không đổi tên bảng.
Không đổi primary key.
Không đổi foreign key nếu chưa hỏi.

---

# Relationships

User

- hasMany(Event)

- belongsToMany(Event)->withPivot()

Event

- belongsTo(User)

- belongsTo(Category)

- hasMany(Registration)

- belongsToMany(Tag)

Category

- hasMany(Event)

Registration

- belongsTo(User)

- belongsTo(Event)

Tag

- belongsToMany(Event)

---

# Authentication

Laravel Breeze.

Role:

admin
organizer
student

Helper

```
isAdmin()
isOrganizer()
isStudent()
```

## Không hardcode role trong nhiều nơi.

# Authorization

Luôn dùng Policy.

Controller

```
$this->authorize(...)
```

Blade

```
@can
```

Không dùng

```
if(Auth::user()->role=="admin")
```

nếu Policy có thể xử lý.

---

# Validation

Bắt buộc FormRequest.
Không validate trong Controller.
Messages phải bằng tiếng Việt.

---

# API Rules

Response

Success

```json
{
    "success": true,
    "data": {}
}
```

Error

```json
{
    "success": false,
    "message": "",
    "errors": {}
}
```

Status code chuẩn

200
201
204
400
401
403
404
422
500

---

# Performance Rules

Luôn:

with()
load()
paginate()
exists()
count()
Không:
foreach rồi query
query trong Blade
N+1

---

# Security

Luôn kiểm tra
Mass Assignment
Authorization
Authentication
CSRF
Validation
XSS
SQL Injection
Không disable middleware.

---

# Khi tạo code

Luôn trả lời theo format

## Mục tiêu

...

## File tạo

- ...

## File sửa

- ...

## Code

...

## Artisan

```bash
php artisan ...
```

## Giải thích

...

---

# Khi review code

Luôn đánh giá
Đúng chức năng
Đúng Laravel
Có bug không
Có security issue không
Có N+1 không
Có code smell không
Có SOLID không
Có thể refactor không

---

# Khi debug

Luôn trả lời

Nguyên nhân
File
Dòng
Cách sửa
Patch
Giải thích
Không chỉ đưa code.

---

# Workflow

Không được nhảy bước.

Phase 1

Database

- Migration
- Factory
- Seeder
- Model

Kiểm tra migrate.
Sau đó mới Phase 2.

---

Phase 2

Authentication

- Breeze
- Login
- Register
- Roles
- Middleware

---

Phase 3

CRUD

Category
Tag
Event
Registration

---

Phase 4

Authorization
Policy
Gate
Middleware

---

Phase 5

REST API
Resource
Controller
Sanctum
Exception Handler

---

Phase 6

Testing
Review
README
Postman
Refactor

---

# Checklist

Trước khi kết thúc phải kiểm tra

□ artisan test
□ artisan migrate
□ artisan db:seed
□ artisan optimize:clear
□ php -l
□ Route hoạt động
□ Validation hoạt động
□ Policy hoạt động
□ Không N+1
□ Không unused import
□ Không dead code
□ Không TODO

# Checklist chi tiết

M1.1 Đăng ký tài khoản (Breeze)
M1.2 Đăng nhập / Đăng xuất
M1.3 Hồ sơ cá nhân (xem & sửa)
M1.4 Quản lý user (Admin)
M2.1 Danh sách sự kiện (public)
M2.2 Chi tiết sự kiện
M2.3 Tạo / Sửa / Xóa sự kiện (Organizer)
M2.4 Upload ảnh banner sự kiện
M2.5 Lọc sự kiện theo category, tag, ngày
M2.6 Tìm kiếm sự kiện (fulltext)
M3.1 Đăng ký tham gia sự kiện
M3.2 Hủy đăng ký
M3.3 Xem danh sách người đăng ký (Organizer)
M3.4 Phê duyệt / Từ chối đăng ký (Admin)
M3.5 Trang "Sự kiện của tôi" (Student)
M4.1 API: GET /api/v1/events (list + filter)
M4.2 API: GET /api/v1/events/{id}
M4.3 API: POST /api/v1/auth/login → token
M4.4 API: POST /api/v1/registrations (Sanctum)
M4.5 API: GET /api/v1/user/registrations
M5.1 Dashboard Admin (thống kê số lượng)
M5.2 Export danh sách đăng ký (CSV)

---

# Nếu thiếu thông tin

Không suy đoán.
Luôn hỏi người dùng.

---

# Nguyên tắc quan trọng nhất

Không sinh code càng nhiều càng tốt.
Sinh code càng đúng càng tốt.
Ưu tiên chất lượng hơn số lượng.
Mỗi thay đổi đều phải giải thích lý do.
Nếu có nhiều cách làm, hãy chọn cách gần với Laravel Best Practices nhất.
