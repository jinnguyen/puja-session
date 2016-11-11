# puja-session
Handle session save data, supported save to file, database, MemCache, Redis

Install
<pre>composer require jinnguyen/puja-session</pre>

Usage:
<pre>
include '/path/to/vendor/autoload.php';
use Puja\Session\Session;
$session = new Session($configure);
$session->start();
</pre>

<strong>Configuration</strong><br />

1. Basic configure: <br />
<pre>$configure = array(
     'saveHandler' => File|Db, // default is File, you also can write saveHandler by your self
     'enabled' => false, // enabled Puja handle session system, if not the default session system will be used
     'ttl' => 0, // the number seconds session will be expired
     'options' => array(), // a list of session.* in php.ini, visit http://php.net/manual/en/session.configuration.php for full list
     'saveHandlerDir' => null, // the namespace to your SaveHandler folder, default: \Puja\Session\SaveHandler\
);</pre>
2. Base on each Save Handler will have some addition configures;<br />
Ex: for SaveHandler: <strong>Db</strong> we have more options:<br />
  - session_table: the table name that will be stored the session data<br />
  - create_table: true/false, if true the system will check and create table when Session start. Recommend: enable for the first you launch application after that disable forever.<br />
<pre>$configure = array(
      'saveHandler' => Db,
      'enabled' => true,
      'ttl' => 1440,
      'options' => array(),
      'saveHandlerDir' => null,
      'session_table' => 'puja_session_table',
      'create_table' => true,
 );</pre>

<strong>Access Session</strong>
<pre>
$session = Session::getInstance('user');
$session->set('name', 'Jin'); // same with $_SESSION['user']['name'] = 'Jin';
$session->get('name'); // same with $_SESSION['user']['name'];
$session->getId(); // same with session_id()
$session->destroy(); // same with session_destroy();
$session->regenerateId($deleteOldSession); // same  with session_regenerate_id($deleteOldSession)
$session->getName($name); // same with session_name($name);
</pre>