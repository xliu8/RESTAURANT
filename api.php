<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>菜单展示</title>
</head>
<body>
    <h1>菜单</h1>
    <?php
    $json = file_get_contents('food.json'); // 替换为你的JSON文件路径
    $menu = json_decode($json, true);

    foreach ($menu as $category) {
        echo "<h2>" . htmlspecialchars($category['category']) . "</h2>";
        foreach ($category['items'] as $item) {
            echo "<div>";
            echo "<h3>" . htmlspecialchars($item['name']) . "</h3>";
            echo "<p>" . htmlspecialchars($item['description']) . " - ";
    
            // 检查价格是否为数组
            if (is_array($item['price'])) {
                foreach ($item['price'] as $key => $value) {
                    echo htmlspecialchars($key) . ": $" . htmlspecialchars($value) . "; ";
                }
            } else {
                echo "$" . htmlspecialchars($item['price']);
            }
            echo "</p>";
            echo "</div>";
        }
    }
    
    
    ?>
</body>
</html>
