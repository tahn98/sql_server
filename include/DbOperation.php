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



	class FavoriteBook
	// simple version of Chapter without data
	{
		function FavoriteBook($book_id,$name,$cover){
			$this->book_id 		= $book_id;
			$this->name			= $name;
			$this->cover 		= $cover;

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

		public function getAllBookInfor($option){
			//$stmt = $this->con->prepare("SELECT * FROM book");

			// $stmt = $this->con->prepare("SELECT E.book_id,E.name,E.description,E.author_name,E.cover,F.rating FROM book E ,(SELECT book_id,AVG(rate) as rating FROM rating GROUP BY book_id ORDER BY book_id) F WHERE E.book_id = F.book_id");

			$query = "";
			if($option == 1){
				// lấy 5 truyện hot rating
				$query = "SELECT E.book_id,E.name,E.description,E.author_name,E.cover,F.rating FROM book E ,(SELECT book_id,AVG(rate) as rating FROM rating GROUP BY book_id ) F WHERE E.book_id = F.book_id ORDER BY F.rating DESC LIMIT 5 ";
			}
			elseif ($option == 2){
				// lấy 5 truyện hot update
				$query = "SELECT A.book_id,A.name,A.description,A.author_name,A.cover,B.rating FROM (SELECT E.book_id,E.name,E.description,E.author_name,E.cover from book E,(SELECT book_id FROM `chapter` GROUP BY book_id ORDER BY upload_date DESC) F WHERE E.book_id = F.book_id LIMIT 5) A,(SELECT book_id,AVG(rate) as rating FROM rating GROUP BY book_id ) B WHERE A.book_id = B.book_id LIMIT 5";
			}
			elseif($option == 3){
				// lấy tất cả danh sách truyện
				$query = "SELECT E.book_id,E.name,E.description,E.author_name,E.cover,F.rating FROM book E ,(SELECT book_id,AVG(rate) as rating FROM rating GROUP BY book_id ) F WHERE E.book_id = F.book_id ORDER BY F.rating DESC";
			}

			$stmt = $this->con->prepare($query);

			////
			////SELECT E.book_id,E.name,E.description,E.author_name,E.cover from book E,(SELECT book_id FROM `chapter` GROUP BY book_id ORDER BY upload_date DESC) F WHERE E.book_id = F.book_id
			////

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

		public function InsertComment($user_name,$book_id,$comment_text,$comment_date){
			
			$stmt = $this->con->prepare("SELECT user_id FROM user WHERE name = ? ;");
			$stmt->bind_param("s",$user_name);
			$stmt->execute();
			$user_id = $stmt->get_result()->fetch_assoc()['user_id'];

			$stmt = $this->con->prepare("INSERT INTO `comment` VALUES (null,?,?,?,?);");
			$stmt->bind_param("ssss",$comment_text,$book_id,$user_id,$comment_date);
			if($stmt->execute()){
				return 1;
			}
			else{
				return 2;
			}	
		}
		public function GetAllFavoriteBook($user_name){

			$stmt = $this->con->prepare("SELECT user_id FROM user WHERE name = ? ;");
			$stmt->bind_param("s",$user_name);
			$stmt->execute();
			$user_id = $stmt->get_result()->fetch_assoc()['user_id'];


			$stmt = $this->con->prepare("SELECT B.book_id,B.name,B.description,B.author_name,B.cover,B.rating FROM (SELECT user_id,book_id FROM bookmark WHERE user_id = ?) A,(SELECT E.book_id,E.name,E.description,E.author_name,E.cover,F.rating FROM book E ,(SELECT book_id,AVG(rate) as rating FROM rating GROUP BY book_id ORDER BY book_id) F WHERE E.book_id = F.book_id) B WHERE A.book_id = B.book_id");
			$stmt->bind_param("s",$user_id);
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
		    				$row[4] = utf8_encode($row[4]);
		    				$row[5] = utf8_encode($row[5]);
		    				
		    				array_push($array_book,new Book($row[0],$row[1],
														 $row[2],
														 $row[3],
														 $row[4],
		    											 $row[5]));
					}
					return $array_book;
					// return $array_book;
			}
		}

		public function ChangeFavorite($user_name,$book_id){
			
			/*	if yes -> no
				if no -> yes
			*/


			$stmt = $this->con->prepare("SELECT user_id FROM user WHERE name = ? ;");
			$stmt->bind_param("s",$user_name);
			$stmt->execute();
			$user_id = $stmt->get_result()->fetch_assoc()['user_id'];

			if ($this->IsFavorite($user_name,$book_id))
			{//yes -> no
			 	// remove something
				//
				$stmt = $this->con->prepare("DELETE FROM bookmark WHERE user_id  = ? AND book_id = ?;");
				$stmt->bind_param("ss",$user_id,$book_id);
				if($stmt->execute()){
					return 1;
				}
				else{
					return 2;
				}	

			}
			else{
				//no - > yes : insert something
				$stmt = $this->con->prepare("INSERT INTO bookmark VALUES (null,?,?);");
				$stmt->bind_param("ss",$user_id,$book_id);
				if($stmt->execute()){
					return 1;
				}
				else{
					return 2;
				}	
			}

		}

		public function IsFavorite($user_name,$book_id){
			
			$stmt = $this->con->prepare("SELECT user_id FROM user WHERE name = ? ;");
			$stmt->bind_param("s",$user_name);
			$stmt->execute();
			$user_id = $stmt->get_result()->fetch_assoc()['user_id'];

			$stmt = $this->con->prepare("SELECT * FROM bookmark WHERE user_id = ? AND book_id = ? ;");
			$stmt->bind_param("ss",$user_id,$book_id);
			$stmt->execute();
			$stmt->store_result();
			return $stmt->num_rows > 0;
		}

		public function InsertRating($user_name,$book_id,$rate){
			
			$stmt = $this->con->prepare("SELECT user_id FROM user WHERE name = ? ;");
			$stmt->bind_param("s",$user_name);
			$stmt->execute();
			$user_id = $stmt->get_result()->fetch_assoc()['user_id'];

			/// if user has been rated for this book - > remove it
			if ($this->ViewRating($user_name,$book_id)['isRating'])
			{
				if ($this->ViewRating($user_name,$book_id)['rate'] == $rate){
					return 1;
					// return (do nothing) 
				}
				$stmt = $this->con->prepare("DELETE FROM rating WHERE user_id  = ? AND book_id = ?;");
				$stmt->bind_param("ss",$user_id,$book_id);
				if($stmt->execute()==false) return 2;

			}
			// And then, Insert new Rating
			$stmt = $this->con->prepare("INSERT INTO rating VALUES (null,?,?,?);");
			$stmt->bind_param("sss",$rate,$user_id,$book_id);
			if($stmt->execute()){
				return 1;
			}
			else{
				return 2;
			}	
		}

		public function ViewRating($user_name,$book_id){
			
			$stmt = $this->con->prepare("SELECT user_id FROM user WHERE name = ? ;");
			$stmt->bind_param("s",$user_name);
			$stmt->execute();
			$user_id = $stmt->get_result()->fetch_assoc()['user_id'];

			$stmt = $this->con->prepare("SELECT rate FROM rating WHERE user_id = ? AND book_id = ? ;");
			$stmt->bind_param("ss",$user_id,$book_id);
			$stmt->execute();
			$stmt->store_result();
			
			$response = array();
			
			$response['isRating'] = false;
			$response['rate'] = "NONE";

			if( $stmt->num_rows > 0){

				$stmt->execute();
				$row = $stmt->get_result()->fetch_assoc()['rate'];

				$response['isRating'] = true;
				$response['rate'] = $row;
			}
			return $response;
		}

	}



//SELECT F.book_id,F.name,F.cover FROM (SELECT user_id,book_id FROM bookmark WHERE user_id = 2) E,book F WHERE E.book_id = F.book_id



/*
SELECT B.book_id,B.name,B.description,B.author_name,B.cover,B.rating FROM (SELECT user_id,book_id FROM bookmark WHERE user_id = 1) A,(SELECT E.book_id,E.name,E.description,E.author_name,E.cover,F.rating FROM book E ,(SELECT book_id,AVG(rate) as rating FROM rating GROUP BY book_id ORDER BY book_id) F WHERE E.book_id = F.book_id) B WHERE A.book_id = B.book_id



SELECT B.book_id,B.name,B.description,B.author_name,B.cover FROM (SELECT user_id,book_id FROM bookmark WHERE user_id = 1) A,book B WHERE A.book_id = B.book_id
*/	


							// $row[0] = iconv(mb_detect_encoding($row[0], mb_detect_order(), true), "UTF-8", $row[0]);
		    	// 			$row[1] = iconv(mb_detect_encoding($row[1], mb_detect_order(), true), "UTF-8", $row[1]);
		    	// 			$row[2] = iconv(mb_detect_encoding($row[2], mb_detect_order(), true), "UTF-8", $row[2]);
		    	// 			$row[3] = iconv(mb_detect_encoding($row[3], mb_detect_order(), true), "UTF-8", $row[3]);
		    	// 			$row[4] = iconv(mb_detect_encoding($row[4], mb_detect_order(), true), "UTF-8", $row[4]);