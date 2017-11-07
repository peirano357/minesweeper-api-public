<?php
/**
 * @author José María Peirano <peirano357@gmail.com>
 */

date_default_timezone_set('America/Argentina/Buenos_Aires');
//Defines
define('ENVIRONMENT', "development");
define('SERVER_SUB_FOLDER', '');

define('BASEPATH', $_SERVER['DOCUMENT_ROOT'].SERVER_SUB_FOLDER);

define('VENDOR_ROOT', BASEPATH . '/vendor');
define('SLIM_ROOT', BASEPATH . '/api');
define("CRYPT_SALT", "Pasasasasas9-999999999999999999999");
define('DB_DRIVER', 'mysql');
define('LANG_ID', '1');

//Requires
require_once(VENDOR_ROOT . '/autoload.php');
error_reporting(0);
require_once(BASEPATH . '/exceptions/ValidationException.php');

// phiscal folder for storing assets
define('STORE_FOLDER','assets/imagenes/');

//////////// MYSQL CONFIG DATA ///////////////////
$db_host = 'localhost';
$db_user = 'root';
$db_pwd = '';
$db_name = 'minesweeper';
$self_url = 'http://localhost/';
$include_url = $_SERVER['DOCUMENT_ROOT']."/";
define('SELF_URL', $self_url);