<?php
class mysql_dbx {
	var $db_connect_id;
	var $query_result;
	var $row = array();
	var $rowset = array();

	function mysql_db($dbhost, $dbuser, $dbpass, $database) {
		echo "<h1>I am in the mysql_db function</h1>";
		$this->server = $dbhost;
		$this->user = $dbuser;
		$this->password = $dbpass;
		$this->dbname = $database;
		$this->db_connect_id = mysqli_connect($this->server, $this->user, $this->password);
		if($this->db_connect_id) {
			if($database != "") {
				$this->dbname = $database;
				$dbselect = mysqli_select_db($this->dbname);
				if(!$dbselect) {
					mysqli_close($this->db_connect_id);
					$this->db_connect_id = $dbselect;
				}
			}
			return $this->db_connect_id;
		} else {
			return false;
		}
	}
}

include_once ("config.php");
$db = new mysql_dbx($dbhost, $dbuser, $dbpass, $database);
?>