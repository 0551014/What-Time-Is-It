<?php
define('ROOT', dirname(__FILE__));
@require_once(ROOT . '\init.php');
if ($_SESSION['isLogined']) {
    echo 'owowowo';
} else {
    echo '<p>Please login to use this page.</p>';
    echo '<p1>Redirect in 5 sec. or click <a href="./index.php">this</a>';
    header("refresh:5; url=index.php");
    exit(0);
}

?>