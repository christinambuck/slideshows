<? 
require_once 'config.inc.php';
global $config;
session_start();
setcookie("PHPSESSID","",mktime(12,0,0,1, 1, 1970), '/',".".$config['http_db_server']);
setcookie("PHPSESSID","",mktime(12,0,0,1, 1, 1970), '/',"www.".$config['http_db_server']);
setcookie("PHPSESSID","",mktime(12,0,0,1, 1, 1970), '/',$config['http_db_server']);
setcookie("PHPSESSID","",mktime(12,0,0,1, 1, 1970), '/');
setcookie("userName","",mktime(12,0,0,1, 1, 1970), '/',".".$config['http_db_server']);
setcookie("userName","",mktime(12,0,0,1, 1, 1970), '/',"www.".$config['http_db_server']);
setcookie("userName","",mktime(12,0,0,1, 1, 1970), '/',$config['http_db_server']);
setcookie("userName","",mktime(12,0,0,1, 1, 1970), '/');
session_destroy();
?>
<meta http-equiv="Refresh" content="0; url=http://<? echo $config['http_db_server'].$config['root'].'/index.php'; ?>">
