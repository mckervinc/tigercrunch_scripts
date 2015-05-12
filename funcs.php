<?php
/***********************************************************************
* Database class: here you will find the equations needed to interact
*				  with the database 
************************************************************************/
class HungerBase
{
	//define connection as static variable
	protected static $connection;
	//establish connection to database. returns database object
	public function connect()
	{
		//connect to database if not already connected
		if (!isset(self::$connection))
		{
			//define connection variables and connect
			$user = 'root';
			$pass = 'hunger';
			$host = 'localhost';
			$dbase = 'hunger_1994ampz';
			self::$connection = new mysqli($host, $user, $pass, $dbase);
		}
		//handle connection error
		if (self::$connection->connect_errno)
		{
			echo self::$connection->connect_error; //print error
			return FALSE;
		}
		else
		{
		  // echo "succesful connection to MySQL database. \n";
			return self::$connection;
		}
	}
	/*id and post_data automatically filled. add building, room info, food, 
	  description, portion, and image to database*/
	public function addFood($building, $room_info, $food, $description, $portions, $expiration)
	{
		$connection = $this->connect();
		$query = "INSERT INTO FreeFood (building, room_info, food, description, claim, expiration) VALUES (?,?,?,?,?,?)";
		$stmt = $connection->prepare($query);
		$stmt->bind_param('ssssii', $building, $room_info, $food, $description,$portions, $expiration);
		$result = $stmt->execute();
		if ($result == FALSE)
		{
			$stmt->close();
			return FALSE;
		}
		else
		{
			$stmt->close();
			return TRUE;
		}

	}

	/*get id number. this is used in addFood.php to name image files i.e. id#.jpg.
	  we store links in the database, as opposed to blobs/actually putting file in db*/
	public function getId()
	{
		$connection = $this->connect();
		$result = $connection->query("SELECT id FROM FreeFood ORDER BY id DESC LIMIT 1");
		if ($result == FALSE)
		{
			$result->close();
			return FALSE;
		}
		$id = $result->fetch_object()->id;
		if(isset($id))
		{
			$result->close();
			return intval($id) + 1;
		}	
		else 
		{
			$result->close();
			return 1;
		}
	}

	/* Return a list of objects in the table. */
	public function getFood()
	{
	  echo 'playa';
	}
	
	/* claim food */
	public function claimFood($id)
	{
	  $connection = $this->connect();
	  $query = "SELECT claim FROM FreeFood WHERE id=" . $id;
	  $claims = $connection->query($query)->fetch_object()->claim;
	  if (intval($claims) < 2)
	    {
	      $query = "DELETE FROM FreeFood WHERE id=" . $id;
	      $connection->query($query);
	    }
	  else
	    {
	      $query = "UPDATE FreeFood SET claim = claim - 1 WHERE id=?";
	      $stmt = $connection->prepare($query);
	      $stmt->bind_param('i',$id);
	      $result = $stmt->execute();
	    }	
	}

	/* Remove food from database */
	public function removeFood($id)
	{
	  $connection = $this->connect();
	  $query = "DELETE FROM FreeFood WHERE id=?";
	  $stmt = $connection->prepare($query);
	  $stmt->bind_param('i',$id);
	  $result = $stmt->execute();
	  if ($result == FALSE)
	    {
	      $stmt->close();
	      return FALSE;
	    }
	  else
	    {
	      $stmt->close();
	      return TRUE;
	    }
	}
	/* Search database */
	public function searchBase($filter)
	{
	  $connection = $this->connect();
	  $search = '%' . $filter . '%';
	  $query = "SELECT * FROM FreeFood WHERE building LIKE ? OR food LIKE ? or description LIKE ? ORDER BY post_date DESC";
	  $stmt = $connection->prepare($query);
	  $stmt->bind_param('sss', $search, $search, $search);
	  $stmt->execute();
	  $stmt->bind_result($col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8, $col9);
	  while ($stmt->fetch())
	    {
	      $data[] = array('id'=>strval($col1), 'building'=>$col2, 'room_info'=>$col3, 'food'=>$col4,
			      'description'=>$col5, 'image'=>$col6, 'post_date'=>$col7, 'claim'=>strval($col8),
			      'expiration'=>strval($col9));
	    }
	  echo json_encode($data);
	  $stmt->close;
	}
}
?>