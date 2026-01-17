<?php
// PHẢI ĐỨNG ĐẦU FILE, KHÔNG CÓ KHOẢNG TRẮNG, KHÔNG CÓ ECHO TRƯỚC
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
        }
        
        .user-details h2 {
            color: #333;
            margin-bottom: 5px;
        }
        
        .user-details p {
            color: #666;
            font-size: 14px;
        }
        
        .btn-logout {
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.2s;
        }
        
        .btn-logout:hover {
            transform: translateY(-2px);
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card h3 {
            color: #333;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card p {
            color: #666;
            line-height: 1.6;
        }
        
        .card-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        
        .stat {
            text-align: center;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }
        
        .stat-label {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            margin-top: 5px;
        }
        
        .alert {
            background: #fff3cd;
            border: 1px solid #ffecb5;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .alert a {
            color: #856404;
            text-decoration: underline;
            font-weight: bold;
        }
        
        .session-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        
        .session-info h4 {
            margin-bottom: 10px;
            color: #333;
        }
        
        .test-link {
            background: #17a2b8;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="user-info">
                <div class="avatar">
                    <?php echo strtoupper(substr($user['email'], 0, 1)); ?>
                </div>
                <div class="user-details">
                    <h2>👋 Xin chào, <?php echo htmlspecialchars($user['email']); ?></h2>
                    <p>Đăng nhập lúc: <?php echo $user['login_time']; ?></p>
                </div>
            </div>
            <a href="logout.php" class="btn-logout">🚪 Đăng xuất</a>
        </header>
        
        <div class="alert">
            <strong>💡 Test Session:</strong> Thử copy link này: <code><?php echo $_SERVER['REQUEST_URI']; ?></code> 
            và paste vào trình duyệt ẩn danh (Incognito). Nếu nó tự nhảy về Login là thành công!
            <br>
            <a href="cart.php" class="test-link">🎯 Test Bài 4 - Giỏ hàng</a>
        </div>
        
        <div class="session-info">
            <h4>📊 Thông tin Session:</h4>
            <pre><?php print_r($_SESSION); ?></pre>
        </div>
        
        <h2 style="margin: 30px 0 20px 0; color: #333;">📋 Dashboard</h2>
        
        <div class="dashboard-grid">
            <div class="card">
                <h3>📈 Thống kê</h3>
                <p>Tổng quan về hệ thống và hoạt động người dùng.</p>
                <div class="card-stats">
                    <div class="stat">
                        <div class="stat-number">1</div>
                        <div class="stat-label">Người dùng</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">0</div>
                        <div class="stat-label">Hoạt động</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Hiệu suất</div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <h3>⚙️ Cài đặt</h3>
                <p>Quản lý cài đặt tài khoản và tùy chỉnh hệ thống.</p>
                <ul style="margin-top: 15px; padding-left: 20px; color: #666;">
                    <li>Thông tin cá nhân</li>
                    <li>Đổi mật khẩu</li>
                    <li>Cài đặt bảo mật</li>
                    <li>Thông báo</li>
                </ul>
            </div>
            
            <div class="card">
                <h3>📝 Bài tập Lab 3</h3>
                <p>Hoàn thành các bài tập:</p>
                <ol style="margin-top: 15px; padding-left: 20px; color: #666;">
                    <li>✅ Đăng ký với password_hash()</li>
                    <li>✅ Đăng nhập với password_verify()</li>
                    <li>✅ Bảo vệ trang với Session</li>
                    <li>🔜 Giỏ hàng với Session Array</li>
                </ol>
                <a href="cart.php" style="display: inline-block; margin-top: 15px; color: #667eea; text-decoration: none;">
                    → Làm Bài 4 ngay
                </a>
            </div>
            
            
        </div>
    </div>
</body>
</html>