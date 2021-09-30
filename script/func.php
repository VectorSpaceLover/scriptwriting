<?php

class DbConnection
{
	private $connection;
	private $servername = "localhost";
	private $username = "root";
	private $password = "";
	private $dbname = 'screenwriter';

	function createConnection()
	{
		$this->connection = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

		if ($this->connection->connect_error) {
		  die("Connection failed: " . $conn->connect_error);
		}
	}
	public function fetch()
	{
		$this->createConnection();

		$sql = "SELECT * FROM scripts";
		$result = $this->connection->query($sql);
		$records = [];
		if ($result->num_rows > 0) {
		  // output data of each row
		  while($row = $result->fetch_assoc()) {
		    array_push($records, $row);
		  }
		} else {

		}
		$this->connection->close();
		return $records;
	}
	public function create($title, $lines)
	{
		$this->createConnection();

		$sql = "INSERT INTO `screenwriter`.`scripts` (`title`, `lines`) VALUES ('" . $title . "', '". $lines . "'); ";
		
		$this->connection->query($sql);

		$this->connection->close();
	}
}
