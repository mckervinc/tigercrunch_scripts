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
		  //echo "succesful connection to MySQL database. \n";
			return self::$connection;
		}
	}
	public function help()
	{
	  $connection = $this->connect();
	}
	
	/*id and post_data automatically filled. add building, room info, food,
	  description, portion, and image to database*/
        public function addFood($building, $room_info, $food, $description, $portions, $expiration)
        {
	  echo $portions;
	  $connection = $this->connect();
	  $query = "INSERT INTO FreeFood (building, room_info, food, description, claim, expiration)
VALUES (?,?,?,?,?,?)";
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
	
	public function getFood()
	{
 	  date_default_timezone_set('UTC'); 
	  $connection = $this->connect();                                             
          $data;
	  $result = $connection->query("SELECT * FROM FreeFood ORDER BY post_date DESC");
	  $toDelete = array();   
	  while($row = $result->fetch_assoc())
	    {
	      if(intval($row['claim']) < intval(1))
		{
		  $query = "DELETE FROM FreeFood WHERE id=" . $row['id'];
		  $connection->query($query);
		}
	      $start = new DateTime($row['post_date']);
	      $now = new DateTime(date('Y-m-d H:i:s'));
	      $interval = $start->diff($now);
	      $elapsed = $interval->format('%H:%I:%S');
	      $row['elapsed_time'] = $elapsed;
	      $mod = '+' . $row['expiration'] . ' ' . 'minutes';
	      $start->modify($mod);
	      if($now >= $start)
		{
		  $query = "DELETE FROM FreeFood WHERE id=" . $row['id'];
		  $connection->query($query);
		}
	      else
		{
		  $expRemain = $start->diff($now);
		  $expRemain = $expRemain->format('%H:%I:%S');
		  $row['time_to_expire'] = $expRemain;
		}
	      $data[] = array('id'=>strval($row['id']), 'building'=>$row['building'], 'room_info'=>$row['room_info'],
			      'food'=>$row['food'],'description'=>$row['description'], 'image'=>$row['image'],
                              'post_date'=>$row['post_date'], 'claim'=>strval($row['claim']),
			      'expiration'=>strval($row['expiration']),'elapsed_time'=>$row['elapsed_time'],
			      'time_to_expire'=>$row['time_to_expire']);    
	    
	    }
	  
	  return json_encode($data);
	}

	/* claim food */
        public function claimFood($id)
        {
          $connection = $this->connect();
          $query = "SELECT claim FROM FreeFood WHERE id=" . $id;
          $claims = $connection->query($query)->fetch_object()->claim;
          if (intval($claims) < 1)
            {
              $query = "DELETE FROM FreeFood WHERE id=" . $id;
              $connection->query($query);
            }
          else
            {
              $query = "UPDATE FreeFood SET claim = claim - 1, post_date=post_date WHERE id=?";
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
	  date_default_timezone_set('UTC'); 
	  $connection = $this->connect();
          $search = '%' . $filter . '%';
          $query = "SELECT * FROM FreeFood WHERE building LIKE ? OR food LIKE ? or description LIKE ? ORDER BY post_date DESC";
          $stmt = $connection->prepare($query);
          $stmt->bind_param('sss', $search, $search, $search);
          $stmt->execute();
          $stmt->bind_result($col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8, $col9,$col10,$col11);
	  while($stmt->fetch())
	    {
	      $start = new DateTime($col7);
	      $now = new DateTime(date('Y-m-d H:i:s'));
	      $interval = $start->diff($now);
	      $elapsed = $interval->format('%H:%I:%S');
	      $col10 = $elapsed;
	      $mod = '+' . $col9 . ' ' . 'minutes';
	      $start->modify($mod);
	      if($now >= $start)
		{
		  $query = "DELETE FROM FreeFood WHERE id=" . $col1;
		  $connection->query($query);
		}
	      else
		{
		  $expRemain = $start->diff($now);
		  $expRemain = $expRemain->format('%H:%I:%S');
		  $col11 = $expRemain;
		}
	     $data[] = array('id'=>strval($col1), 'building'=>$col2, 'room_info'=>$col3, 'food'=>$col4,
                              'description'=>$col5, 'image'=>$col6, 'post_date'=>$col7, 'claim'=>strval($col8),
                              'expiration'=>strval($col9),'elapsed_time'=>$col10, 'time_to_expire'=>$col11);
	    }
	    return json_encode($data);
	}
}
?>