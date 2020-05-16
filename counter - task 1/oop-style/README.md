# Задание 1 - Решение в oop стиле
Напишите скрипт, который будет считать количество уникальных посетителей сайта за день и записывать их в базу данных.

```php
require_once('counter.class.php');
$counter = new counter();
$counter->sql_host = "localhost";
$counter->sql_user = "root";
$counter-> sql_pass = "pass";
$counter->sql_database = "counter";

$counter->createDB(); // создает базу данных, если введены данные db

$counter->deleteOldIp(); // удаляем старые ip
// $counter->deleteOldDay (); // если не хранить данные прошлых дней

if (!$counter->checkIp()) // проверяем существование текущего ip
{
	$counter->addIp (); // добавляем ip в базу
	if (!$counter->checkDay()) // проверяем существование строчки счетчика на текущий день
	{
		$counter->addNewDay(); // если нужно хранить данные предудущих дней.
	}

	$counter->updateCount(); // обновляем счетчики
}
$counter->close(); // закрываем соединение

```
База данных 
```sql
CREATE TABLE `counter` (
  `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `host` int(255) NOT NULL DEFAULT 0,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `counter_ip` (
  `ip` int(10) UNSIGNED NOT NULL,
  `time` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `counter_ip`
  ADD UNIQUE KEY `ip` (`ip`);
```
