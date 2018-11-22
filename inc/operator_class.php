<?php
session_start();
ini_set('session.cookie_httponly', 1);
header("X-XSS-Protection: 0");
$params = session_get_cookie_params();
setcookie("PHPSESSID", session_id(), 0, $params["path"], $params["domain"],
    false,  // this is the secure flag you need to set. Default is false.
    true  // this is the httpOnly flag you need to set
);
$now = time();
if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
    // this session has worn out its welcome; kill it and start a brand new one
    session_unset();
    session_destroy();
    session_start();
}

// either new or old, it should live at most for another hour
$_SESSION['discard_after'] = $now + 1200;
class Session
{
    var $SessionName='Default';
    function __constructor($SessionName)
    {
        $this->SessionName=$SessionName;
    }
    function Set($Setting,$Value)
    {
        $_SESSION[$this->SessionName][$Setting]=$Value;
    }
    function Get($Setting,$Default='')
    {
        if(isset($_SESSION[$this->SessionName][$Setting]) && !empty($_SESSION[$this->SessionName][$Setting]))
            return $_SESSION[$this->SessionName][$Setting];
        else
            return $Default;
    }
}
?> 