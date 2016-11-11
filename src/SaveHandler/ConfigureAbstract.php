<?php
namespace Puja\Session\SaveHandler;

abstract class ConfigureAbstract
{
    const DEFAULT_SAVEHANDLER = 'File';
    const DEFAULT_SAVEHANDLER_DIR = '\\Puja\\Session\\SaveHandler\\';
    private $abstractCfg = array(

        'saveHandler' => null,
        'enabled' => false,
        'ttl' => -1,
        'options' => array(),
        'saveHandlerDir' => null,
    );

    protected $cfg = array();

    public function __construct($data)
    {
        if (empty($data['saveHandlerDir'])) {
            $data['saveHandlerDir'] = self::DEFAULT_SAVEHANDLER_DIR;
        }
        $this->cfg = array_merge($this->abstractCfg, $this->cfg, $data);
    }

    public function getSaveHandler()
    {
        return $this->cfg['saveHandler'];
    }

    public function getSaveHandlerDir()
    {
        return $this->cfg['saveHandlerDir'];
    }

    public function getEnabled()
    {
        return !empty($this->cfg['enabled']);
    }

    public function getOptions()
    {
        return $this->cfg['options'];
    }

    public function getTtl()
    {
        return $this->cfg['ttl'];
    }
}