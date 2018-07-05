<?php
if (!defined('ROOT')) {
    exit('Access Denied');
}
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");

#region SQL配置
$dbhost = 'localhost';
$dbacc  = 'student';
$dbpwd  = '12345678';
$dbname = 'student_FinalReport';
$dsn = 'mysql:host=' . $dbhost . ';dbname='. $dbname . ';charset=UTF8MB4';
#endregion

#region 網頁配置


#endregion

/**
 * Show error as JSON and exit.
 * @param int $errorCode errorCode
 * @param string $errorMsg String to display
 * @return null
 */
function showError($errorCode, $errorMsg, $errorData = NULL) {
    echo json_encode( array (
            "RC" => $errorCode,
            "Msg" => $errorMsg,
            "result" => $errorData ? $errorData : "Nah"
        ));
    exit(-99);
}
function returnJSON($resultData) {
    echo json_encode( array (
            "RC" => 0,
            "Msg" => "Successtul",
            "result" => $resultData
        ));
    exit(0);
}
// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}



$_METHOD = $_SERVER['REQUEST_METHOD'];
$_URL = $_SERVER['REQUEST_URI'];

session_start();



if ( isset($_GET['schedules']) ) {
    @require_once(ROOT . '\schedules.php');
    exit();
}
if ( isset($_GET['login']) ) {
    @require_once(ROOT . '\login.php');
    exit();
}
if ( isset($_GET['logout']) ) {
    @require_once(ROOT . '\logout.php');
    exit();
}
if ( isset($_GET['register']) ) {
    @require_once(ROOT . '\register.php');
    exit();
}
?>