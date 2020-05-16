<?php header('Content-type: text/html; charset=utf-8');

/*
 * Отладочная функция
 *
 *  @param mixed $var
  * @return $string html
 */
function dump($var) {
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
}

/**
 * Данные базы.
 *
 */

$INFO['sql_host'] = "localhost";
$INFO['sql_user'] = "root";
$INFO['sql_pass'] = "pass";
$INFO['sql_database'] = "counter";

$connect = mysqli_connect($INFO['sql_host'], $INFO['sql_user'] , $INFO['sql_pass'], $INFO['sql_database']);
if(!$connect)  die('Ошибка подключения к серверу баз данных.');

/**
 * Создание структуры
 *
 */
$products = "CREATE TABLE `products` (
  `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$customers = "CREATE TABLE `customers` (
  `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$orders = "CREATE TABLE `orders` (
  `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `customers_id` int(11) UNSIGNED DEFAULT NULL,
  `products_id` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$keyOrders = "ALTER TABLE `orders`
  ADD KEY `index_foreignkey_orders_customers` (`customers_id`),
  ADD KEY `index_foreignkey_orders_products` (`products_id`)";

$foreignkey = "ALTER TABLE `orders`
  ADD CONSTRAINT `c_fk_orders_customers_id` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `c_fk_orders_products_id` FOREIGN KEY (`products_id`) REFERENCES `products` (`id`) ON DELETE SET NULL ON UPDATE SET NULL";


$query1 = "INSERT INTO `customers` (`id`, `name`) VALUES
(1, 'Белавин Максимилиан'),
(2, 'Мономахов Панфил'),
(3, 'Булгарин Авенир'),
(4, 'Репнинский Феликс'),
(5, 'Домашнев Парамон'),
(6, 'Довголевский Станислав'),
(7, 'Большев Максим'),
(8, 'Моисеев Изосим'),
(9, 'Болотов Иван'),
(10, 'Рублёв Сергей')";

$query2 = "INSERT INTO `products` (`id`, `name`) VALUES
(1, 'Черный воздушный шарик'),
(2, 'Красный воздушный шарик'),
(3, 'Зеленый воздушный шарик'),
(4, 'Розовый воздушный шарик'),
(5, 'Белый воздушный шарик'),
(6, 'Синий воздушный шарик'),
(7, 'Прозрачный воздушный шарик'),
(8, 'Разноцветный воздушный шарик'),
(9, 'Большой воздушный шарик'),
(10, 'Детский воздушный шарик'),
(11, 'Необычный воздушный шарик'),
(12, 'Золотистый воздушный шарик'),
(13, 'Серый воздушный шарик'),
(14, 'Коричневый воздушный шарик'),
(15, 'Голубой воздушный шарик')";

$query3 = "INSERT INTO `orders` (`id`, `customers_id`, `products_id`) VALUES
(1, 4, 6),
(2, 6, 15),
(3, 8, 14),
(4, 5, 10),
(5, 4, 2),
(6, 3, 5),
(7, 6, 5),
(8, 10, 5),
(9, 6, 6),
(10, 9, 15),
(11, 9, 4),
(12, 3, 13),
(13, 5, 8),
(14, 1, 11),
(15, 8, 12),
(16, 1, 5),
(17, 9, 15),
(18, 6, 13),
(19, 5, 4),
(20, 7, 12),
(21, 8, 3),
(22, 8, 15),
(23, 2, 4),
(24, 1, 8),
(25, 9, 7),
(26, 10, 9),
(27, 2, 9),
(28, 4, 1),
(29, 4, 11),
(30, 4, 9)";


$query = mysqli_query($connect,$products);
$query = mysqli_query($connect,$customers);
$query = mysqli_query($connect,$orders);
$query = mysqli_query($connect,$keyOrders);
$query = mysqli_query($connect,$foreignkey);
$query = mysqli_query($connect,$query1);
$query = mysqli_query($connect,$query2);
$query = mysqli_query($connect,$query3);

// Требуемыый запрос
$target = "SELECT customers.name FROM customers
WHERE (SELECT count(orders.id) FROM orders WHERE orders.customers_id = customers.id) >= 3";

// тоже самое, только расширенный вариант, с указанием количества купленных товаров
$targetExt = "SELECT customers.name as 'Покупатель',COUNT(orders.id) as 'Количество заказанных товаров' FROM customers
LEFT JOIN orders on orders.customers_id = customers.id
WHERE (SELECT count(orders.id) FROM orders WHERE orders.customers_id = customers.id) >= 3
GROUP BY customers.name";

mysqli_close($connect);

