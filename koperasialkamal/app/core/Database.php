<?php
class Database
{
	public
		$host 		= DB_HOST,
		$user 		= DB_USER,
		$pass 		= DB_PASS,
		$dbh,
		$stmt;
	// public $db_name		= DB_NAME;

	public function __construct($db_name = DB_NAME)
	{
		$this->db_name = $db_name;
		$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;

		$option = [
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		];

		try {
			$this->dbh = new PDO($dsn, $this->user, $this->pass, $option);
		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}

	// ========== QUERY ========== //
	public function query($query)
	{
		$this->stmt = $this->dbh->prepare($query);
	}

	// ========== BIND ========== //
	public function bind($param, $value, $type = null)
	{
		if (is_null($type)) {
			switch (true) {
				case is_int($value):
					$type = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
				default:
					$type = PDO::PARAM_STR;
			}
		}

		$this->stmt->bindValue($param, $value, $type);
	}

	// ========== EXECUTION ========== //
	public function execute()
	{
		$this->stmt->execute();
	}

	// ========== TAMPIL SEMUA ========== //
	public function resultSet()
	{
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_OBJ);
	}

	// ========== TAMPIL SATU ========== //
	public function single()
	{
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_OBJ);
	}

	// ========== TAMPIL JUMLAH AKSI ========== //
	public function rowCount()
	{
		return $this->stmt->rowCount();
	}

	// ========== TAMPIL JUMLAH ROW ========== //
	public function numRows()
	{
		$this->execute();
		return $this->stmt->fetchColumn();
	}
}
