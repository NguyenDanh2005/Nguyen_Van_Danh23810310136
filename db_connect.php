<?php
// Thông tin kết nối database
$host = 'localhost';
$dbname = 'buoi2_php';
$username = 'root';
// Cố tình nhập sai mật khẩu để test lỗi
$password = ''; // Đổi thành '' nếu dùng XAMPP/WAMP mặc định

try {
    // Kết nối bằng PDO với UTF-8
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    
    // Không echo gì cả khi thành công
    
} catch (PDOException $e) {
    // Hiển thị thông báo thân thiện cho người dùng
    echo "<div style='color: red; padding: 20px; background: #ffe6e6; border: 1px solid red; margin: 10px;'>";
    echo "<h3>Hệ thống đang bảo trì, vui lòng quay lại sau!</h3>";
    echo "</div>";
    
    // Ghi log lỗi ra file (chỉ trên môi trường dev)
    if (getenv('APP_ENV') === 'development') {
        error_log("Database connection failed: " . $e->getMessage());
    }
    
    // Dừng script
    die();
}
?>