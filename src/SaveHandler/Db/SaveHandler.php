<?php
namespace Puja\Session\SaveHandler\Db;
use Puja\Session\SaveHandler\SaveHandlerInterface;
use Puja\Db\Table;

class SaveHandler implements SaveHandlerInterface
{
    protected $table;
    protected $lifeTime;
    protected $name;

    public function __construct(Configure $configure)
    {
        $this->lifeTime = $configure->getTtl();
        $this->table = new Table($configure->getSessionTable());
        if ($configure->getCreateTable()) {
            Table::getWriteAdapter()->execute('
                CREATE TABLE IF NOT EXISTS ' . $configure->getSessionTable() . '(
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
        $row = $this->table->findOneByCriteria(sprintf('id = "%s"', $id));
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
        $this->table->replace($data);
    }

    public function destroy($id)
    {
        $this->table->deleteByCriteria(sprintf('id = "%s"', $id));
    }
    
    public function gc($maxlifetime)
    {
        $this->table->deleteByCriteria(sprintf('%s < %d', 'modified', time() - $this->lifeTime));
    }
}