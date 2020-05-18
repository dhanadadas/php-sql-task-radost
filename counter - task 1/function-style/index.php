<?php
require_once('function.php');
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
//createDB ($connect);

deleteOldIp($connect);
// deleteOldDay ($connect); // если не хранить данные прошлых дней

if ( !checkIp($ip, $connect) ) {
	addIp ($ip, $connect);
	if ( !checkDay($connect) ) {
		addNewDay($connect); // если нужно хранить данные предудущих дней.
	}
	updateCount($connect);
}

mysqli_close($connect);