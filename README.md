# ĐỒ ÁN TỐT NGHIỆP
## Website Đặt Lịch Dịch Vụ Làm Đẹp Tại Nhà - BeautyHome

## Giới thiệu
Hệ thống hỗ trợ khách hàng đặt lịch các dịch vụ làm đẹp tại nhà một cách nhanh chóng và tiện lợi.

Khách hàng có thể:
- Đặt lịch dịch vụ
- Chọn thời gian
- Theo dõi trạng thái lịch hẹn
- Thanh toán trực tuyến
- Đánh giá dịch vụ

Hệ thống hỗ trợ quản lý cho:
- Khách hàng
- Nhân viên
- Quản trị viên

---

## Chức năng chính

### Khách hàng
- Đăng ký / đăng nhập
- Xem danh sách dịch vụ
- Đặt lịch làm đẹp
- Thanh toán
- Theo dõi lịch hẹn
- Đánh giá dịch vụ
- Nhận thông báo

### Nhân viên
- Xem lịch được phân công
- Cập nhật trạng thái thực hiện
- Nhận thông báo từ admin và khách hàng

### Quản trị viên
- Quản lý dịch vụ
- Quản lý khách hàng
- Quản lý nhân viên
- Quản lý lịch đặt
- Phân công nhân viên
- Quản lý thanh toán
- Thống kê doanh thu

---

## Công nghệ sử dụng

- Laravel
- PHP
- MySQL
- Bootstrap 5
- JavaScript
- Chart.js
- Laragon

---

## SInh viên thực hiện

- Nguyễn Thị Trang - 2251162180 - 64HTTT3

---

## Hướng dẫn chạy project

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
