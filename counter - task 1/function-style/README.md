# Задание 1 - Решение в function-style
Напишите скрипт, который будет считать количество уникальных посетителей сайта за день и записывать их в базу данных.

## Решение sql
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
## PHP основа:
```php
/**
 * Реализация
 */
deleteOldIp($connect);
// deleteOldDay ($connect); // если не хранить данные прошлых дней

if ( !checkIp($ip, $connect) ) {
	addIp ($ip, $connect);
	if ( !checkDay($connect) ) {
		addNewDay($connect); // если нужно хранить данные предудущих дней.
	}
	updateCount($connect);
}
```
