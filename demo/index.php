<?php
include '../vendor/autoload.php';
use Puja\Db\Adapter;
$configures = array(
    'write_adapter_name' => 'master',
    'adapters' => array(
        'default' => array(
            'host' => 'localhost',
            'username' => 'root',
            'password' => '123',
            'dbname' => 'fwcms',
            'charset' => 'utf8',
        ),
        'master' => array(
            'host' => 'localhost',
            'username' => 'root',
            'password' => '123',
            'dbname' => 'fwcms',
            'charset' => 'utf8',
        )
    )
);

new Adapter($configures);
use Puja\Session\Session;
$session = new Session(
    array( 'session_table' => 'php_session', 'saveHandler' => 'Db', 'savePath' => __DIR__ . '/', 'enabled' => true, 'options' => array('name' => 'PujaSession'))
);
$session->start();


$user = Session::getInstance('user');

$user->set('a', 'linh');

$car = Session::getInstance('car');
$car->set('a', 'kkkk');
$car->set('a', '222kkkk');
$car->set('b', 'bbb');
?>
<a href="./page2.php" target="_newTab">Page 2</a>
