<?php

//Chat.php

namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require dirname(__DIR__) . "/database/ChatRooms.php";

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo 'Server Started 1 ';
    }

    public function onOpen(ConnectionInterface $conn) {

        // Store the new connection to send messages to later
        echo 'Server Started 2';

        $this->clients->attach($conn);

        $querystring = $conn->httpRequest->getUri()->getQuery();

        parse_str($querystring, $queryarray);

        if(isset($queryarray['token']))
        {

            $data['status_type'] = 'Online';

            $data['user_id_status'] = 5;

            // first, you are sending to all existing users message of 'new'
            foreach ($this->clients as $client)
            {
                $client->send(json_encode($data)); //here we are sending a status-message
            }
        }

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $data = json_decode($msg, true);

        $chat_object = new \ChatRooms;

        $chat_object->setUserId($data['userId']);

        $chat_object->setReceiverId($data['ri']);

        $chat_object->setGroupId($data['gi']);

        $chat_object->setMessage($data['msg']);

        $chat_object->setCreatedOn(date("Y-m-d h:i:s"));

        $chat_object->save_chat();

        $user_name = $data['ri'];

        $data['dt'] = date("d-m-Y h:i:s");


        foreach ($this->clients as $client) {
            /*if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }*/

            if($from == $client)
            {
                $data['from'] = 'Me';
            }
            else
            {
                $data['from'] = $user_name;
            }

            $client->send(json_encode($data));
        }
    }

    public function onClose(ConnectionInterface $conn) {

        $querystring = $conn->httpRequest->getUri()->getQuery();

        parse_str($querystring, $queryarray);

        if(isset($queryarray['token']))
        {

            $data['status_type'] = 'Offline';

            $data['user_id_status'] = 5;

            foreach($this->clients as $client)
            {
                $client->send(json_encode($data));
            }
        }
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}

?>