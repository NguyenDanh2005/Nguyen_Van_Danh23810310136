<?php
session_start();
require_once 'db_connect.php';

$message = '';
$message_type = '';

// Nếu đã đăng nhập thì chuyển hướng về dashboard
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate dữ liệu
    if (empty($email) || empty($password)) {
        $message = 'Vui lòng điền đầy đủ thông tin!';
        $message_type = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Email không hợp lệ!';
        $message_type = 'error';
    } elseif ($password !== $confirm_password) {
        $message = 'Mật khẩu xác nhận không khớp!';
        $message_type = 'error';
    } elseif (strlen($password) < 6) {
        $message = 'Mật khẩu phải có ít nhất 6 ký tự!';
        $message_type = 'error';
    } else {
        try {
            // Kiểm tra email đã tồn tại chưa
            $check_sql = "SELECT id FROM users WHERE email = :email";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bindParam(':email', $email);
            $check_stmt->execute();
            
            if ($check_stmt->rowCount() > 0) {
                $message = 'Email đã được sử dụng!';
                $message_type = 'error';
            } else {
                // Mã hóa mật khẩu
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                
                // Thêm user mới vào database
                $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password_hash);
                
                if ($stmt->execute()) {
                    $message = 'Đăng ký thành công! Bạn có thể đăng nhập ngay.';
                    $message_type = 'success';
                    $email = ''; // Reset form
                }
            }
        } catch (PDOException $e) {
            $message = 'Lỗi hệ thống: ' . $e->getMessage();
            $message_type = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }
        
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
        }
        
        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: 500;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .links {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .links a:hover {
            text-decoration: underline;
        }
        
        .password-hint {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Đăng ký tài khoản</h1>
        
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">📧 Email:</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo htmlspecialchars($email ?? ''); ?>" 
                       required
                       placeholder="example@gmail.com">
            </div>
            
            <div class="form-group">
                <label for="password">🔑 Mật khẩu:</label>
                <input type="password" id="password" name="password" 
                       required
                       placeholder="Ít nhất 6 ký tự">
                <div class="password-hint">Mật khẩu sẽ được mã hóa an toàn trong database</div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">✅ Xác nhận mật khẩu:</label>
                <input type="password" id="confirm_password" name="confirm_password" 
                       required
                       placeholder="Nhập lại mật khẩu">
            </div>
            
            <button type="submit">📝 Đăng ký ngay</button>
        </form>
        
        <div class="links">
            <p>Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
            <p><a href="index.php">← Về trang chủ</a></p>
        </div>
    </div>
</body>
</html>