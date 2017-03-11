<?php
$dsn = 'mysql:host=localhost;dbname=decarte';
$pdo = new PDO($dsn, 'piotrala', 'ewm100', [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
]);

$stmt = $pdo->query('SELECT id FROM decarte_products');
$products = $stmt->fetchAll();

define('IMAGES_DIR', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'zaproszenia' . DIRECTORY_SEPARATOR . 'produkty' . DIRECTORY_SEPARATOR);
define('BIG_DIR', IMAGES_DIR . 'duze' . DIRECTORY_SEPARATOR);
define('SMALL_DIR', IMAGES_DIR . 'male' . DIRECTORY_SEPARATOR);

$pdo->exec('DELETE FROM decarte_product_images');

$stmt = $pdo->prepare('INSERT INTO decarte_product_images (product_id, sort, big_name, small_name) VALUES (:product_id, :sort, :big_name, :small_name)');

$count = 0;

foreach ($products as $product) {
    $id = $product['id'];

    for ($sort = 1; $sort <= 2; $sort++) {
        $bigName = $id . '_' . $sort . '.jpg';
        $smallName = $id . '_' . $sort . 'm.jpg';

        $bigPath = IMAGES_DIR . $bigName;
        $smallPath = IMAGES_DIR . $smallName;

        if (file_exists($bigPath) && file_exists($smallPath)) {
            rename($bigPath, BIG_DIR . $bigName);
            rename($smallPath, SMALL_DIR . $smallName);

            $stmt->bindValue(':product_id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':sort', $sort - 1, PDO::PARAM_INT);
            $stmt->bindValue(':big_name', $bigName, PDO::PARAM_STR);
            $stmt->bindValue(':small_name', $smallName, PDO::PARAM_STR);
            $stmt->execute();
            $count++;
        }
    }
}

echo 'Przeniesiono ' . $count . ' plików' . PHP_EOL;
