<?php
	/**
	 * 
	 */
	class DbConnect
	{
		private $con;
		function __construct()
		{
			# code...
		}
		function connect(){
			include_once dirname(__FILE__).'/constance.php';
			$this->con = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
			if(mysqli_connect_errno()){
				echo "Fail to connect".mysqli_connect_err();
			}
			return $this->con;
		}
	}