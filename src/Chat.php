<?php
namespace WSChat;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Chat implements MessageComponentInterface
{
    /** @var ConnectionInterface[] */
    private $clients = [];
    /** @var ChatManager */
    private $cm = null;

    public function __construct(ChatManager $cm)
    {
        $this->cm = $cm;
        $this->cm->refreshChats();
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $rid = $this->getResourceId($conn);
        echo 'Connection is established: '.$rid.PHP_EOL;
        $this->clients[$rid] = $conn;
        $this->cm->addUser($rid);
    }

    /**
     * @param ConnectionInterface $from
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $rid = array_search($from, $this->clients);
        echo 'Message from '.$rid.PHP_EOL;
        $chat = $this->cm->getUserChart($rid);
        foreach ($chat->getUsers() as $user) {
            $conn = $this->clients[$user->getRid()];
            $conn->send($msg);
        }
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        $rid = array_search($conn, $this->clients);
        echo 'Connection is closed '.$rid.PHP_EOL;
        if ($this->cm->getUserByRid($rid)) {
            $this->cm->removeUserFromChat($rid);
        }
        unset($this->clients[$rid]);
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

    /**
     * Get connection resource id
     *
     * @access private
     * @param ConnectionInterface $conn
     * @return string
     */
    private function getResourceId(ConnectionInterface $conn)
    {
        return $conn->resourceId;
    }
}
 