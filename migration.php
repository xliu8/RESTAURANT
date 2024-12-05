<?php
// 数据库配置信息
$host = 'localhost'; // 数据库地址
$dbname = 'restaurant'; // 数据库名
$user = 'serveruser'; // 数据库用户名
$pass = 'gorgonzola7!'; // 数据库密码
$charset = 'utf8mb4'; // 字符集

// 创建PDO实例
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// 读取JSON文件
$jsonData = file_get_contents('food.json');
$foodData = json_decode($jsonData, true);

// 准备插入语句
$insertStmt = $pdo->prepare("INSERT INTO Menu (food_name, food_price, food_description, category) VALUES (:food_name, :food_price, :food_description, :category)");

// 遍历并插入数据
foreach ($foodData as $category) {
    foreach ($category['items'] as $item) {
        // 如果价格是数组，转换为JSON字符串
        $price = is_array($item['price']) ? json_encode($item['price']) : $item['price'];
        $insertStmt->execute([
            ':food_name' => $item['name'],
            ':food_price' => $price,
            ':food_description' => $item['description'],
            ':category' => $category['category'] // 确保这个字段在数据库中是文本类型或相应的ENUM
        ]);
    }
}

echo "数据导入完成。";
?>
