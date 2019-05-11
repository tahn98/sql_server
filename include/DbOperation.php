<?php
	/**
	 * 
	 */
	class Book
	{
		function Book($id,$name,$description,$author_name,$cover,$rating){
			$this->id 			= $id;
			$this->name			= $name;
			$this->description 	= $description;
			$this->author_name	= $author_name;
			$this->cover 		= $cover;
			$this->rating 		= $rating;
		}
	}

	class Chapter
	// Detail version of Chapter with data
	{
		function Chapter($data){
			// $this->chapter_id 		= $chapter_id;
			// $this->book_id			= $book_id;
			// $this->name 			= $name;
			$this->data				= $data;
			// $this->number_of_read 	= $number_of_read;
		}
	}

	class Chapter_simple
	// simple version of Chapter without data
	{
		function Chapter_simple($chapter_id,$book_id,$name,$number_of_read,$upload_date){
			$this->chapter_id 		= $chapter_id;
			$this->book_id			= $book_id;
			$this->name 			= $name;
			$this->number_of_read 	= $number_of_read;
			$this->upload_date		= $upload_date;
		}
	}

	class Comment
	// simple version of Chapter without data
	{
		function Comment($comment_text,$comment_date,$user_id,$name){
			$this->comment_text 		= $comment_text;
			$this->comment_date			= $comment_date;
			$this->user_id 				= $user_id;
			$this->name 				= $name;

		}
	}


	class DbOperation
	{
		private $con;
		
		function __construct()
		{
			require_once dirname(__FILE__).'/DbConnect.php';
			
			$db = new DbConnect();
			$this->con = $db->connect();
		}
		//chưa sửa
		public function createUser($username,$pass,$email){
			if($this->isUserExit($username,$email)){
				return 0;
			}
			else{
				$password = md5($pass);
				$stmt = $this->con->prepare("INSERT INTO `user` (`user_id`, `name`, `password`, `email`) VALUES (NULL,?,?,? );");
				$stmt->bind_param("sss",$username,$password,$email);
				if($stmt->execute()){
					return 1;
				}
				else{
					return 2;
				}
			}		
		}
		//chưa sửa
		public function userLogIn($username,$password){
			$pass = md5($password);
			$stmt = $this->con->prepare("SELECT user_id FROM user WHERE name = ? AND password = ?");
			$stmt->bind_param("ss",$username,$pass);
			$stmt->execute();
			$stmt->store_result();
			return $stmt->num_rows > 0;

		}
		//chưa sửa
		public function getUserByUserName($username){
			$stmt = $this->con->prepare("SELECT * FROM user WHERE name = ?");
			$stmt->bind_param("s",$username);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();	

		}
		//chưa sửa
		private function isUserExit($username,$email){
			$stmt = $this->con->prepare("SELECT user_id FROM user WHERE name = ? OR email = ?");
			$stmt->bind_param("ss",$username,$email);
			$stmt->execute();
			$stmt->store_result();
			return $stmt->num_rows > 0; 
		}

		public function getAllBookInfor(){
			//$stmt = $this->con->prepare("SELECT * FROM book");

			$stmt = $this->con->prepare("SELECT E.book_id,E.name,E.description,E.author_name,E.cover,F.rating FROM book E ,(SELECT book_id,AVG(rate) as rating FROM rating GROUP BY book_id ORDER BY book_id) F WHERE E.book_id = F.book_id");
			$stmt->execute();

			$array_book = array();
			$data = $stmt->get_result();
			if($data){
					while ($row = $data->fetch_array(MYSQLI_NUM))
					{

							// $row[0] = iconv(mb_detect_encoding($row[0], mb_detect_order(), true), "UTF-8", $row[0]);
		    	// 			$row[1] = iconv(mb_detect_encoding($row[1], mb_detect_order(), true), "UTF-8", $row[1]);
		    	// 			$row[2] = iconv(mb_detect_encoding($row[2], mb_detect_order(), true), "UTF-8", $row[2]);
		    	// 			$row[3] = iconv(mb_detect_encoding($row[3], mb_detect_order(), true), "UTF-8", $row[3]);
		    	// 			$row[4] = iconv(mb_detect_encoding($row[4], mb_detect_order(), true), "UTF-8", $row[4]);

							$row[0] = utf8_encode($row[0]);
		    				$row[1] = utf8_encode($row[1]);
		    				$row[2] = utf8_encode($row[2]);
		    				$row[3] = utf8_encode($row[3]);
		    				$row[4] = utf8_encode($row[4]);
		    				$row[5] = utf8_encode($row[5]);

		    				array_push($array_book,new Book($row[0],$row[1],
														 $row[2],
														 $row[3],
														 $row[4],
		    											 $row[5]));
					}
					return $array_book;

				}

			else{
				echo "NULL";
			}

		}

		public function getAllChapter($book_id){
			$stmt = $this->con->prepare("SELECT * FROM chapter WHERE book_id = ?");
			$stmt->bind_param("s",$book_id);
			$stmt->execute();
			$array_book = array();
			$data = $stmt->get_result();
			if($data){
					while ($row = $data->fetch_array(MYSQLI_NUM))
					{

							$row[0] = utf8_encode($row[0]);
		    				$row[1] = utf8_encode($row[1]);
		    				$row[2] = utf8_encode($row[2]);
		    				$row[3] = utf8_encode($row[4]);
		    				$row[4] = utf8_encode($row[5]);

		    				array_push($array_book,new Chapter_simple($row[0],$row[1],
														 $row[2],
														 $row[4],
														 $row[5]));
					}
					return $array_book;
				}

			else{
				echo "NULL";
			}

		}
		public function getchapterInfor($book_id,$chaper_id){
			$stmt = $this->con->prepare("SELECT * FROM chapter WHERE book_id = ? AND chapter_id = ?");
			$stmt->bind_param("ss",$book_id,$chaper_id);
			$stmt->execute();
			$array_book = array();
			$data = $stmt->get_result();
			if($data){
					while ($row = $data->fetch_array(MYSQLI_NUM))
					{
							// $row[0] = utf8_encode($row[0]);
		    	// 			$row[1] = utf8_encode($row[1]);
		    	// 			$row[2] = utf8_encode($row[2]);
		    				
		    				$row[3] = utf8_encode($row[3]);
		    				// $row[4] = utf8_encode($row[4]);

		    				array_push($array_book,new Chapter($row[3]));
					}
					return $array_book;
			}

		}

		// Insert Book mark of user
		public function InsertBookMark($user_id,$book_id){
			$stmt = $this->con->prepare("INSERT INTO bookmark VALUES (null,?,?);");
			$stmt->bind_param("ss",$user_id,$book_id);
			$stmt->execute();
			$stmt->store_result();
			//echo $stmt->get_result();
			return $stmt->num_rows > 0; 
		}


		public function getAllComment($book_id){
			$stmt = $this->con->prepare("SELECT E.comment_text,E.comment_date,E.user_id,F.name FROM (SELECT * FROM comment WHERE book_id = ? ORDER BY comment_date) E ,(SELECT user_id,name FROM user) F WHERE E.user_id = F.user_id");
			$stmt->bind_param("s",$book_id);
			$stmt->execute();
			$array_book = array();
			$data = $stmt->get_result();
			if($data){
					while ($row = $data->fetch_array(MYSQLI_NUM))
					{
							$row[0] = utf8_encode($row[0]);
		    				$row[1] = utf8_encode($row[1]);
		    				$row[2] = utf8_encode($row[2]);
		    				$row[3] = utf8_encode($row[3]);
		    				// $row[4] = utf8_encode($row[4]);

		    				array_push($array_book,new Comment($row[0],$row[1],$row[2],$row[3]));
					}
					return $array_book;
			}

		}

		public function InsertComment($user_id,$book_id,$comment_text,$comment_date){

			$stmt = $this->con->prepare("INSERT INTO `comment` VALUES (null,?,?,?,?);");
			$stmt->bind_param("ssss",$comment_text,$book_id,$user_id,$comment_date);
			if($stmt->execute()){
				return 1;
			}
			else{
				return 2;
			}	
		}
	}