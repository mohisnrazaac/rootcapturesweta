<?php 
	
class ChatRooms
{
	private $chat_id;
	private $user_id;
	private $receiver_id;
	private $group_id;
	private $message;
	private $created_on;
	protected $connect;

	public function setChatId($chat_id)
	{
		$this->chat_id = $chat_id;
	}

	function getChatId()
	{
		return $this->chat_id;
	}

	function setUserId($user_id)
	{
		$this->user_id = $user_id;
	}

	function setReceiverId($user_id)
	{
		$this->receiver_id = $user_id;
	}

	function setGroupId($group_id)
	{
		$this->group_id = $group_id;
	}

	function getUserId()
	{
		return $this->user_id;
	}

	function setMessage($message)
	{
		$this->message = $message;
	}

	function getMessage()
	{
		return $this->message;
	}

	function setCreatedOn($created_on)
	{
		$this->created_on = $created_on;
	}

	function getCreatedOn()
	{
		return $this->created_on;
	}

	public function __construct()
	{
		require_once("Database_connection.php");

		$database_object = new Database_connection;

		$this->connect = $database_object->connect();
	}

	function save_chat()
	{
		
		if($this->group_id>0){
			$query = "
			INSERT INTO chat_group_msg 
				(group_id,sender_id, msg) 
				VALUES (:group_id,:sender_id,:msg)
			";

			$statement = $this->connect->prepare($query);

			$statement->bindParam(':sender_id', $this->user_id);

			$statement->bindParam(':group_id', $this->group_id);

			$statement->bindParam(':msg', $this->message);

		}else{
			$query = "
			INSERT INTO chat 
				(sender_userid,reciever_userid, message, timestamp) 
				VALUES (:userid, :receiver_id,:msg,:timeon)
			";

			$statement = $this->connect->prepare($query);

			$statement->bindParam(':userid', $this->user_id);

			$statement->bindParam(':receiver_id', $this->receiver_id);

			$statement->bindParam(':msg', $this->message);

			$statement->bindParam(':timeon', time());
		}

		$statement->execute();
	}

	function get_all_chat_data()
	{
		$query = "
		SELECT * FROM chatrooms 
			INNER JOIN chat_user_table 
			ON chat_user_table.user_id = chatrooms.userid 
			ORDER BY chatrooms.id ASC
		";

		$statement = $this->connect->prepare($query);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
}
	
?>