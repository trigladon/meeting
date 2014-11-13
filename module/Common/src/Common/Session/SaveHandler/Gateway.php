<?php

namespace Common\Session\SaveHandler;

use Zend\Session\SaveHandler\SaveHandlerInterface;
use Zend\Db\Adapter\Exception;

class Gateway implements SaveHandlerInterface
{

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection = null;

    protected $sessionSavePath;

    protected $sessionName;

    protected $lifetime;

    protected $tableName = 'o_o_session';

    /**
     * @param array $doctrineConnectionConfig
     *
     * @throws \Exception
     */
    public function __construct($doctrineConnectionConfig, Array $doctrineConfig)
    {

        /**
         * @var \Doctrine\DBAL\Connection
         */
        $this->connection = $doctrineConnectionConfig;
        $this->tableName = $doctrineConfig['connection']['orm_default']['params']['tablePrefix'].$doctrineConfig['sessionDBOptions']['tableName'];
    }

    /**
     * @param string $savePath
     * @param string $name
     *
     * @return bool
     */
    public function open($savePath, $name)
    {
        $this->sessionSavePath = $savePath;
        $this->sessionName     = $name;
        $this->lifetime        = ini_get('session.gc_maxlifetime');

        return true;


    }

    /**
     * Close Session - free resources
     *
     * @return bool
     */
    public function close()
    {
        $this->connection = null;
        return true;

    }

    /**
     * Read session data
     *
     * @param string $id
     *
     * @return bool|array|string
     */
    public function read($id)
    {
        $so = $this->connection->prepare("SELECT * FROM $this->tableName WHERE id = :id ");

        if ($so->execute(array('id' => $id))) {
            $data = $so->fetch();
            if (($data['modified'] + $data['lifetime']) > time()) {
                return $data['data'];

            }
            $this->destroy($id);
        }
        return '';
    }

    /**
     * Write Session - commit data to resource
     *
     * @param string $id
     * @param mixed $data
     *
     * @return bool
     */
    public function write($id, $data)
    {
        if  ($session = $this->read($id)) {
            $so = $this->connection->prepare("UPDATE $this->tableName SET data = :data, modified = :modified  WHERE id = :id ");
            return $so->execute(array('data' => (string)$data, 'id' => $id, 'modified' => time()));
        }

        return $this->create($id, $data);
    }

    /**
     * @param $id
     * @param $data
     *
     * @return bool
     */
    protected function create($id, $data)
    {
        $so = $this->connection->prepare("INSERT INTO $this->tableName (id, name, modified, lifetime, data) VALUES (:id, :name, :modified, :lifetime, :data) ");
        return $so->execute(array('id' => $id, 'name' => $this->sessionName, 'modified' => time(), 'lifetime' => $this->lifetime, 'data' => $data));
    }

    /**
     * Destroy Session - remove data from resource for
     * given session id
     *
     * @param string $id
     *
     * @return bool
     */
    public function destroy($id)
    {
        $so = $this->connection->prepare("DELETE FROM $this->tableName WHERE id = :id");
        return $so->execute(array('id' => $id));
    }

    /**
     * Garbage Collection - remove old session data older
     * than $maxlifetime (in seconds)
     *
     * @param int $maxlifetime
     *
     * @return bool
     */
    public function gc($maxlifetime)
    {
        $so = $this->connection->prepare("DELETE FROM $this->tableName WHERE modified < :minlifetime ");
        return $so->execute(array('minlifetime' => time() - $this->lifetime));
    }

}