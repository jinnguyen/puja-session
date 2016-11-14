<?php
namespace Puja\Session\SaveHandler;

class Adapter
{
    const DEFAULT_SAVEHANDLER = 'File';
    public function __construct($configure)
    {
        $cfgStandard = new ConfigureStandard($configure);
        if (false === $cfgStandard->getEnabled())
        {
            return;
        }

        if ($cfgStandard->getTtl() > -1) {
            $configure['options']['gc_maxlifetime'] = $cfgStandard->getTtl();
        } else {
            $configure['ttl'] = ini_get('session.gc_maxlifetime');
        }

        if (!empty($configure['options']['save_handler'])) {
            unset($configure['options']['save_handler']);
        }

        if (!empty($configure['options'])) {
            foreach ($configure['options'] as $optKey => $optVal) {
                ini_set('session.' . $optKey, $optVal);
            }
        }

        if ($cfgStandard->getSaveHandler() === self::DEFAULT_SAVEHANDLER) {
            if (!empty($configure['savePath'])) {
                ini_set('session.save_path', $configure['savePath']);
            }
            return;
        }


        $configureCls = $cfgStandard->getSaveHandlerDir() . $cfgStandard->getSaveHandler() . '\\Configure';
        $saveHandlerCls = $cfgStandard->getSaveHandlerDir() . $cfgStandard->getSaveHandler() . '\\SaveHandler';

        if (!class_exists($configureCls) || !class_exists($saveHandlerCls)) {
            throw new Exception(sprintf('A SaveHandler must have 2 classes: %s and %s', $configureCls, $saveHandlerCls));
        }

        $handler = new $saveHandlerCls(new $configureCls($configure));
        session_set_save_handler(
            array($handler, 'open'),
            array($handler, 'close'),
            array($handler, 'read'),
            array($handler, 'write'),
            array($handler, 'destroy'),
            array($handler, 'gc')
        );
    }
}