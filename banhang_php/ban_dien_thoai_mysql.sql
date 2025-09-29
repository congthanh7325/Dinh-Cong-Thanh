-- Database bán điện thoại đơn giản (MySQL)
CREATE DATABASE IF NOT EXISTS QLBanDienThoai;
USE QLBanDienThoai;

-- Bảng Điện thoại
CREATE TABLE DienThoai (
    MaDT VARCHAR(10) PRIMARY KEY,
    TenDT VARCHAR(100),
    ThuongHieu VARCHAR(50),
    CauHinh VARCHAR(255),
    GiaBan DECIMAL(18,2)
);

-- Bảng Khách hàng
CREATE TABLE KhachHang (
    MaKH VARCHAR(10) PRIMARY KEY,
    TenKH VARCHAR(100),
    DiaChi VARCHAR(200),
    SoDienThoai VARCHAR(15),
    Email VARCHAR(100)
);

-- Bảng Hóa đơn (đơn hàng)
CREATE TABLE HoaDon (
    MaHD VARCHAR(10) PRIMARY KEY,
    NgayLap DATE,
    MaKH VARCHAR(10),
    TongTien DECIMAL(18,2),
    FOREIGN KEY (MaKH) REFERENCES KhachHang(MaKH)
);

-- Bảng Chi tiết hóa đơn
CREATE TABLE ChiTietHoaDon (
    MaHD VARCHAR(10),
    MaDT VARCHAR(10),
    SoLuong INT,
    DonGia DECIMAL(18,2),
    PRIMARY KEY (MaHD, MaDT),
    FOREIGN KEY (MaHD) REFERENCES HoaDon(MaHD),
    FOREIGN KEY (MaDT) REFERENCES DienThoai(MaDT)
);

-- Dữ liệu mẫu
INSERT INTO DienThoai (MaDT, TenDT, ThuongHieu, CauHinh, GiaBan) VALUES
('DT01', 'iPhone 14 Pro', 'Apple', 'A16 Bionic, 128GB', 27000000),
('DT02', 'Samsung Galaxy S23', 'Samsung', 'Snapdragon 8 Gen 2, 128GB', 19000000),
('DT03', 'Xiaomi 13', 'Xiaomi', 'SD8 Gen 2, 256GB', 15000000),
('DT04', 'OPPO Find X5', 'OPPO', 'SD888, 256GB', 13500000),
('DT05', 'Vivo X90', 'Vivo', 'Dimensity 9200, 256GB', 14500000);

INSERT INTO KhachHang (MaKH, TenKH, DiaChi, SoDienThoai, Email) VALUES
('KH01', 'Nguyen Van A', 'Ha Noi', '0901234567', 'vana@gmail.com'),
('KH02', 'Tran Thi B', 'Ho Chi Minh', '0902345678', 'thib@gmail.com');

INSERT INTO HoaDon (MaHD, NgayLap, MaKH, TongTien) VALUES
('HD01', '2024-05-10', 'KH01', 46000000),
('HD02', '2024-05-11', 'KH02', 15000000);

INSERT INTO ChiTietHoaDon (MaHD, MaDT, SoLuong, DonGia) VALUES
('HD01', 'DT01', 1, 27000000),
('HD01', 'DT02', 1, 19000000),
('HD02', 'DT03', 1, 15000000);
