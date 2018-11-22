<?php
/**
 * Defines use of SMS Gateway
 *
 * If set to TRUE then emails will be Sent through gmail otherwise use NIC Relay Server
 * for Production UseSMTP set to false, for development value will be true
 */
define("UseSMTP", true);

/**
 * Defines Gmail credentials
 */
define("GMail_UserID", "user@gmail.com");
define("GMail_Pass", "password");
define("UserName", "Paschim Medinipur");
require_once __DIR__ . '/class.phpmailer.php';
//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Asia/Kolkata');
?>
