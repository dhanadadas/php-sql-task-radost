<?php
class Counter{
	public function __construct(){
		$this->sql_host = "localhost";
		$this->sql_user = "root";
		$this-> sql_pass = "pass";
		$this->sql_database = "counter";
		$this->connect = mysqli_connect($this->sql_host, $this->sql_user, $this->sql_pass, $this->sql_database);
		$this->ip = ip2long($_SERVER['SERVER_ADDR']);
	}

	/**
	 * Метод addIp добавляет ip текущего клиента во временную таблицу
	 *
	 * @return boolean true или false
	 */
	function addIp()
	{
		$query = mysqli_query($this->connect, "INSERT INTO `counter_ip` (`ip`, `time`) VALUES ($this->ip,current_date())");
		if ($query === FALSE) {
			return false;
		} else return true;
	}

	/**
	 * Метод addNewDay добавляет новую запись
	 *
	 * Применяется, если требуется хранить данные прошлых дней.
	 * Добавляет строчку с текущей датой и 0 начальным значением
	 * уникальных посетителей
	 *
	 * @return boolean true или false
	 */
	function addNewDay()
	{
		$query = mysqli_query($this->connect, "INSERT INTO `counter` (`id`, `host`, `date`) VALUES (NULL, '0', current_date())");
		if ($query === FALSE) {
			return false;
		} else return true;
	}

	/**
	 * Метод updateCount обновляет счетчик
	 *
	 * @return boolean true или false
	 */
	function updateCount()
	{
		$query = mysqli_query($this->connect, "UPDATE `counter` SET `host` = `host`+1 WHERE `counter`.`date` = current_date()");
		if ($query === FALSE) {
			return false;
		} else return true;
	}

	/**
	 * Метод deleteOldIp удаляет старые ip
	 *
	 * @return boolean true или false
	 */
	function deleteOldIp()
	{
		$query = mysqli_query($this->connect, "DELETE FROM counter_ip WHERE `time` < current_date()");
		if ($query === FALSE) {
			return false;
		} else return true;
	}

	/**
	 * Метод deleteOldIp удаляет старые ip
	 *
	 * @return boolean true или false
	 */
	function deleteOldDay()
	{
		$query = mysqli_query($this->connect, "DELETE FROM counter WHERE `data` < current_date()");
		if ($query === FALSE) {
			return false;
		} else return true;
	}

	/**
	 * Метод checkIp проверяет, есть ли текущий ip в базе
	 *
	 * @return boolean true или false
	 */
	function checkIp()
	{
		$checkIP = mysqli_query($this->connect, "SELECT `ip` FROM `counter_ip` WHERE ( `ip` = $this->ip ) and `time` = current_date()");
		if (mysqli_num_rows($checkIP)) {
			return true;
		} else return false;

	}

	/**
	 * Метод checkDay проверяет, есть запись текущего дня
	 *
	 * @return boolean true или false
	 */
	function checkDay()
	{
		$checkDayCounter = mysqli_query($this->connect, "SELECT * FROM `counter` WHERE `date` = current_date() ");
		if (mysqli_num_rows($checkDayCounter)) {
			return true;
		} else return false;
	}

	/**
	 * Метод createDB создает структуру, если указаны данные DB
	 *
	 * @return boolean true или false
	 */
	function createDB()
	{
		$table_counter = "CREATE TABLE `counter` (
  `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `host` int(255) NOT NULL DEFAULT 0,
  `date` date NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

		$table_counter_ip = "CREATE TABLE `counter_ip` (
  `ip` int(10) UNSIGNED NOT NULL,
  `time` date NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

		$key = "ALTER TABLE `counter_ip`
  ADD UNIQUE KEY `ip` (`ip`)";

		mysqli_query($this->connect, $table_counter);
		mysqli_query($this->connect, $table_counter_ip);
		mysqli_query($this->connect, $key);
	}

	function close (){
		mysqli_close($this->connect);
	}
}
