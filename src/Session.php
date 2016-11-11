<?php
namespace Puja\Session;
use Puja\Session\SaveHandler\Adapter;
class Session
{
    protected static $instances;
    protected static $initAdapter;
    protected $namespace;
    public function __construct($configure = array())
    {
        if (!empty($configure)) {

            if (null !== self::$initAdapter) {
                throw new Exception('The configures are already loaded, no need to reload again');
            }

            self::$initAdapter = true;
            new Adapter($configure);
        }
    }

    /**
     * @param null $namespace
     * @return $this
     */
    public static function getInstance($namespace = null)
    {
        if (null === self::$initAdapter) {
            trigger_error('Your session configure is not set yet, you are using default session system.');
        }

        if (empty($namespace)) {
            $namespace = 'default';
        }

        if (!empty(self::$instances[$namespace])) {
            return self::$instances[$namespace];
        }

        self::$instances[$namespace] = new self();
        self::$instances[$namespace]->namespace = $namespace;


        return self::$instances[$namespace];
    }

    public function set($key, $val)
    {
        $_SESSION[$this->namespace][$key] = $val;
    }

    public function get($key, $defaultVal = null)
    {
        if (empty($_SESSION[$this->namespace])) {
            return $defaultVal;
        }

        if (array_key_exists($key, $_SESSION[$this->namespace])) {
            return $_SESSION[$this->namespace][$key];
        }

        return $defaultVal;
    }

    public function start()
    {
        session_start();
    }

    public function getId($id = null)
    {
        return session_id($id);
    }

    public function destroy()
    {
        session_destroy();
    }

    public function regenerateId($deleteOldSession = null)
    {
        session_regenerate_id($deleteOldSession);
    }

    public function getName($name = null)
    {
        return session_name($name);
    }
    
}