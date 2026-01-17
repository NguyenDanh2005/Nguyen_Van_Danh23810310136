<?php
session_start();

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Danh sách sản phẩm (hardcode)
$products = [
    1 => ['id' => 1, 'name' => 'iPhone 15 Pro', 'price' => 25000000, 'image' => '📱'],
    2 => ['id' => 2, 'name' => 'MacBook Air M2', 'price' => 32000000, 'image' => '💻'],
    3 => ['id' => 3, 'name' => 'Apple Watch Series 9', 'price' => 12000000, 'image' => '⌚'],
    4 => ['id' => 4, 'name' => 'AirPods Pro 2', 'price' => 6500000, 'image' => '🎧'],
    5 => ['id' => 5, 'name' => 'iPad Pro 12.9', 'price' => 28000000, 'image' => '📱'],
    6 => ['id' => 6, 'name' => 'Magic Keyboard', 'price' => 4500000, 'image' => '⌨️'],
];

// Xử lý thêm vào giỏ hàng
if (isset($_GET['add_to_cart'])) {
    $product_id = (int)$_GET['add_to_cart'];
    
    if (isset($products[$product_id])) {
        // Thêm sản phẩm vào giỏ hàng
        $_SESSION['cart'][] = $product_id;
        
        // Chuyển hướng để tránh F5 gửi lại request
        header('Location: cart.php?added=' . $product_id);
        exit();
    }
}

// Xử lý xóa giỏ hàng
if (isset($_GET['clear_cart'])) {
    $_SESSION['cart'] = [];
    header('Location: cart.php');
    exit();
}

// Xử lý xóa 1 sản phẩm
if (isset($_GET['remove_item'])) {
    $item_id = (int)$_GET['remove_item'];
    $key = array_search($item_id, $_SESSION['cart']);
    
    if ($key !== false) {
        unset($_SESSION['cart'][$key]);
        // Sắp xếp lại chỉ mục mảng
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    
    header('Location: cart.php');
    exit();
}

// Tính tổng tiền
$total = 0;
$cart_details = [];
foreach ($_SESSION['cart'] as $product_id) {
    if (isset($products[$product_id])) {
        $product = $products[$product_id];
        $cart_details[] = $product;
        $total += $product['price'];
    }
}

// Định dạng tiền tệ
function formatCurrency($amount) {
    return number_format($amount, 0, ',', '.') . '₫';
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng của tôi</title>
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
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            text-align: center;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 20px;
        }
        
        .cart-stats {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            text-align: center;
            min-width: 150px;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
        }
        
        .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .product-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-icon {
            font-size: 50px;
            margin-bottom: 15px;
        }
        
        .product-name {
            color: #333;
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .product-price {
            color: #667eea;
            font-weight: bold;
            font-size: 20px;
            margin-bottom: 15px;
        }
        
        .btn-add {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
            transition: opacity 0.3s;
        }
        
        .btn-add:hover {
            opacity: 0.9;
        }
        
        .cart-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        
        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .btn-clear {
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.3s;
        }
        
        .cart-item:hover {
            background: #f9f9f9;
        }
        
        .item-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .item-icon {
            font-size: 30px;
        }
        
        .item-details h4 {
            color: #333;
            margin-bottom: 5px;
        }
        
        .item-price {
            color: #667eea;
            font-weight: bold;
        }
        
        .btn-remove {
            background: #ff4444;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .cart-total {
            text-align: right;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }
        
        .total-amount {
            font-size: 24px;
            color: #333;
            font-weight: bold;
        }
        
        .empty-cart {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .links {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        
        .links a {
            color: #667eea;
            text-decoration: none;
            margin: 0 10px;
        }
        
        .links a:hover {
            text-decoration: underline;
        }
        
        .notification {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 10px 0;
            text-align: center;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🛒 Giỏ hàng của tôi</h1>
            <p class="subtitle">Bài tập Lab 3 - Session Array</p>
            
            <div class="cart-stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($cart_details); ?></div>
                    <div class="stat-label">Sản phẩm</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo formatCurrency($total); ?></div>
                    <div class="stat-label">Tổng tiền</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($products); ?></div>
                    <div class="stat-label">Có sẵn</div>
                </div>
            </div>
        </header>
        
        <h2 style="margin-bottom: 20px; color: #333;">📦 Sản phẩm</h2>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-icon"><?php echo $product['image']; ?></div>
                <h3 class="product-name"><?php echo $product['name']; ?></h3>
                <p class="product-price"><?php echo formatCurrency($product['price']); ?></p>
                <a href="?add_to_cart=<?php echo $product['id']; ?>" class="btn-add">
                    ➕ Thêm vào giỏ
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="cart-container">
            <div class="cart-header">
                <h2 style="margin: 0; color: #333;">🛍️ Giỏ hàng của bạn</h2>
                <?php if (!empty($cart_details)): ?>
                <a href="?clear_cart=1" class="btn-clear" 
                   onclick="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')">
                    🗑️ Xóa giỏ hàng
                </a>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($cart_details)): ?>
                <?php foreach ($cart_details as $item): ?>
                <div class="cart-item">
                    <div class="item-info">
                        <div class="item-icon"><?php echo $item['image']; ?></div>
                        <div class="item-details">
                            <h4><?php echo $item['name']; ?></h4>
                            <p class="item-price"><?php echo formatCurrency($item['price']); ?></p>
                        </div>
                    </div>
                    <a href="?remove_item=<?php echo $item['id']; ?>" 
                       class="btn-remove"
                       onclick="return confirm('Xóa <?php echo $item['name']; ?>?')">
                        Xóa
                    </a>
                </div>
                <?php endforeach; ?>
                
                <div class="cart-total">
                    <h3>Tổng cộng:</h3>
                    <p class="total-amount"><?php echo formatCurrency($total); ?></p>
                </div>
                
            <?php else: ?>
                <div class="empty-cart">
                    <h3>🛒 Giỏ hàng trống</h3>
                    <p>Hãy thêm sản phẩm từ danh sách bên trên!</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="links">
            <a href="dashboard.php">← Quay lại Dashboard</a>
            |
            <a href="login.php">Đăng nhập</a>
            |
            <a href="register.php">Đăng ký</a>
        </div>
        
        <!-- Debug info (có thể ẩn đi sau khi test xong) -->
        <div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-radius: 5px; font-size: 12px;">
            <h4>🔧 Debug Session Info:</h4>
            <p><strong>Session ID:</strong> <?php echo session_id(); ?></p>
            <p><strong>Giỏ hàng (raw):</strong> <?php echo json_encode($_SESSION['cart']); ?></p>
            <p><strong>Số sản phẩm:</strong> <?php echo count($_SESSION['cart']); ?></p>
            <p><a href="?clear_cart=1" style="color: #ff4444;">[Test: Clear Cart]</a> | 
               <a href="?add_to_cart=1">[Test: Add iPhone]</a> | 
               <a href="?add_to_cart=2">[Test: Add MacBook]</a></p>
        </div>
    </div>

    <script>
    // Hiển thị thông báo khi thêm sản phẩm thành công
    if (window.location.search.includes('added=')) {
        const urlParams = new URLSearchParams(window.location.search);
        const productId = urlParams.get('added');
        
        // Hiển thị alert
        alert(`✅ Đã thêm sản phẩm #${productId} vào giỏ hàng!`);
        
        // Xóa param để tránh alert lại khi F5
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    
    // Xác nhận xóa
    function confirmAction(message) {
        return confirm(message);
    }
    
    // Auto-test: Thêm 2 sản phẩm mẫu nếu giỏ hàng trống (chỉ lần đầu)
    window.addEventListener('load', function() {
        const cart = <?php echo json_encode($_SESSION['cart']); ?>;
        if (cart.length === 0 && !localStorage.getItem('autoTestDone')) {
            if (confirm('Bạn muốn thêm sản phẩm mẫu để test không?')) {
                localStorage.setItem('autoTestDone', 'true');
                // Thêm sản phẩm 1 và 3
                setTimeout(() => {
                    window.location.href = '?add_to_cart=1';
                }, 100);
                setTimeout(() => {
                    window.location.href = '?add_to_cart=3';
                }, 500);
            }
        }
    });
    </script>
</body>
</html>