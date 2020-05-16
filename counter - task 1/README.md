# Задание 1
Напишите скрипт, который будет считать количество уникальных посетителей сайта за день и записывать их в базу данных.

## Задача решена
Сначало сделал в  [function-style](https://github.com/dhanadadas/php-sql-task-radost/tree/master/counter%20-%20task%201/function-style), потом за полчаса переписал под ООП-стиль

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
