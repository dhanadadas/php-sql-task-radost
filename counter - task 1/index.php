<?php header('Content-type: text/html; charset=utf-8');
/**
 * Получаем ip пользователя
 */
$ip = ip2long($_SERVER['SERVER_ADDR']);

/**
 * Данные базы
 *
 */
$INFO['sql_host'] = "localhost";
$INFO['sql_user'] = "root";
$INFO['sql_pass'] = "pass";
$INFO['sql_database'] = "counter";

$connect = mysqli_connect($INFO['sql_host'], $INFO['sql_user'] , $INFO['sql_pass'], $INFO['sql_database']);
if(!$connect)  die('Ошибка подключения к серверу баз данных.');

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

/**
 * Функции
 */

/**
 * Функция addIp добавляет ip текущего клиента во временную таблицу
 *
 *  @param string $ip текущий ip в числовом формате
 *  @param mysqli $connect текущее соединение
 * @return boolean true или false
 */
function addIp ($ip, $connect){
	$query = mysqli_query($connect,"INSERT INTO `counter_ip` (`ip`, `time`) VALUES ($ip,current_date())");
	if ($query === FALSE) {
		return false;
	} else return true;
}

/**
 * Функция addNewDay добавляет новую запись
 *
 * Применяется, если требуется хранить данные прошлых дней.
 * Добавляет строчку с текущей датой и 0 начальным значением
 * уникальных посетителей
 *
 *  @param mysqli $connect текущее соединение
 * @return boolean true или false
 */
function addNewDay($connect){
	$query = mysqli_query( $connect, "INSERT INTO `counter` (`id`, `host`, `date`) VALUES (NULL, '0', current_date())");
	if ($query === FALSE) {
		return false;
	} else return true;
}

/**
 * Функция updateCount обновляет счетчик
 *
 *  @param mysqli $connect текущее соединение
 * @return boolean true или false
 */
function updateCount($connect){
	$query= mysqli_query( $connect, "UPDATE `counter` SET `host` = `host`+1 WHERE `counter`.`date` = current_date()");
	if ($query === FALSE) {
		return false;
	} else return true;
}

/**
 * Функция deleteOldIp удаляет старые ip
 *
 *  @param mysqli $connect текущее соединение
 * @return boolean true или false
 */
function deleteOldIp($connect){
	$query= mysqli_query( $connect, "DELETE FROM counter_ip WHERE `time` < current_date()");
	if ($query === FALSE) {
		return false;
	} else return true;
}

/**
 * Функция deleteOldIp удаляет старые ip
 *
 *  @param mysqli $connect текущее соединение
 * @return boolean true или false
 */
function deleteOldDay($connect){
	$query= mysqli_query( $connect, "DELETE FROM counter WHERE `data` < current_date()");
	if ($query === FALSE) {
		return false;
	} else return true;
}

/**
 * Функция checkIp проверяет, есть ли текущий ip в базе
 *
 *  @param mysqli $connect текущее соединение
 * @return boolean true или false
 */
function checkIp ($ip, $connect) {
	$checkIP = mysqli_query( $connect, "SELECT `ip` FROM `counter_ip` WHERE ( `ip` = $ip ) and `time` = current_date()" );
	if (mysqli_num_rows( $checkIP ) ) {
		return true;
	} else return false;

}

/**
 * Функция checkDay проверяет, есть запись текущего дня
 *
 *  @param mysqli $connect текущее соединение
 * @return boolean true или false
 */
function checkDay ($connect){
	$checkDayCounter = mysqli_query( $connect, "SELECT * FROM `counter` WHERE `date` = current_date() ");
	if ( mysqli_num_rows( $checkDayCounter ) ) {
		return true;
	} else return false;
}

/**
 * Функция createDB создает структуру, если указаны данные DB
 *
 *  @param mysqli $connect текущее соединение
 * @return boolean true или false
 */
function createDB ($connect){
	$table_counter="CREATE TABLE `counter` (
  `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `host` int(255) NOT NULL DEFAULT 0,
  `date` date NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

	$table_counter_ip="CREATE TABLE `counter_ip` (
  `ip` int(10) UNSIGNED NOT NULL,
  `time` date NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

	$key= "ALTER TABLE `counter_ip`
  ADD UNIQUE KEY `ip` (`ip`)";

	mysqli_query($connect,$table_counter);
	mysqli_query($connect,$table_counter_ip);
	mysqli_query($connect,$key);
};

//createDB ($connect);

mysqli_close($connect);