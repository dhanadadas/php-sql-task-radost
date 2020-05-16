<?php
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