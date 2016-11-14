<?php
namespace Puja\Session\SaveHandler\Db;
use Puja\Session\SaveHandler\ConfigureAbstract;
class Configure extends ConfigureAbstract
{
    protected $cfg = array(
        'session_table' => 'puja_session_table',
        'adapter_name' => null,
        'create_table' => false,
    );

    public function getSessionTable()
    {
        return $this->cfg['session_table'];
    }

    public function getCreateTable()
    {
        return $this->cfg['create_table'];
    }

    public function getAdapterName()
    {
        return $this->cfg['adapter_name'];
    }
}