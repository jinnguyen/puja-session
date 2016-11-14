<?php
namespace Puja\Session\SaveHandler\Db;
use Puja\Session\SaveHandler\SaveHandlerInterface;
use Puja\Db\Table;

class SaveHandler implements SaveHandlerInterface
{
    protected $adapter;
    protected $tableName;
    protected $lifeTime;
    protected $name;

    public function __construct(Configure $configure)
    {
        $this->lifeTime = $configure->getTtl();
        $this->adapter = Table::getAdapter($configure->getAdapterName());
        $this->tableName = $configure->getAdapterName();

        if ($configure->getCreateTable()) {
            $this->adapter->execute('
                CREATE TABLE IF NOT EXISTS ' . $this->tableName . '(
                    `id` char(32),
                    `modified` int,
                    `lifetime` int,
                    `data` text,
                PRIMARY KEY (`id`))
             ');
        }
    }

    public function open($savePath, $name)
    {
        $this->name = $name;
    }

    public function close()
    {
        return true;
    }

    public function read($id)
    {
        $query = $this->adapter->select()->from($this->tableName)->where(array('id' => $id));
        $row = $this->adapter->query($query);
        if (empty($row)) {
            return '';
        }

        if ($row['modified'] + $row['lifetime'] > time()) {
            return $row['data'];
        }
        $this->destroy($id);
    }

    public function write($id, $data)
    {
        $data = array(
            'modified' => time(),
            'data'     => (string) $data,
            'id' => $id,
            'lifetime' => $this->lifeTime,
        );
        $this->adapter->replace($this->tableName, $data);
    }

    public function destroy($id)
    {
        $this->adapter->delete($this->tableName, array('id' => $id));
    }
    
    public function gc($maxlifetime)
    {
        $this->adapter->delete(sprintf('%s < %d', 'modified', time() - $this->lifeTime));
    }
}