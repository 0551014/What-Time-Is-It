<?php
if (!defined('ROOT')) {
    echo '<p>非法存取</p>';
}

#region SQL配置
$dbhost = 'localhost';
$dbacc  = 'student';
$dbpwd  = '12345678';
$dbname = 'student_FinalReport';
$dsn = 'mysql:host=' . $dbhost . ';dbname='. $dbname . ';charset=utf8';
#endregion

#region 網頁配置


#endregion





session_start();
?>