<?php
@include_once('ErrorHandler.php');


class DBHelper
{
	private $db_host = '127.0.0.1';
	private $db_name = 'currency';
	private $db_user = 'root';
	private $db_passw = 'root';

	private $db_connection = null;
	private $db_statement = null;

	public function __construct($name = 'currency', $user = 'root', $password = 'root')
	{
		$this->db_name = $name;
		$this->db_user = $user;
		$this->db_passw = $password;

		try {
			$this->db_connection = new PDO("mysql:host={$this->db_host};dbname={$this->db_name}", $this->db_user, $this->db_passw);
		} catch(PDOException $e) {
			ErrorHandler::error($e, true);
		}
	}

	public function query($sql, $args = [])
	{
		if($this->db_connection == null) {
			return false;
		}

		try {
			$this->db_statement = $this->db_connection->prepare($sql);
			$this->db_statement->execute($args);

		} catch(PDOException $e) {
			ErrorHandler::error($e, true);
		}

		return true;
	}

	public function getRow()
	{
		if($this->db_connection == null || $this->db_statement == null) {
			return false;
		}

		return $this->db_statement->fetch(PDO::FETCH_ASSOC);
	}

	public function getRows()
	{
		if($this->db_connection == null || $this->db_statement == null) {
			return false;
		}

		return $this->db_statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function rowCount()
	{
		if($this->db_connection == null || $this->db_statement == null) {
			return false;
		}

		return $this->db_statement->rowCount();
	}
}