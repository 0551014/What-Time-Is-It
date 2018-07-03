<?php
define('ROOT', dirname(__FILE__));
@require_once(ROOT . '\init.php');
if ($_SESSION['isLogined']) {
    session_destroy();
    echo "<p>You've logged out, {$_SESSION['account']}.</p>";
    echo '<p1>Redirect in 5 sec. or click <a href="./index.php">this</a>';
    header("refresh:5; url=index.php");
    exit(0);
} else {
    session_destroy();
    echo "<p>You haven't logged in.</p>";
    echo '<p1>Redirect in 5 sec. or click <a href="./index.php">this</a>';
    header("refresh:5; url=index.php");
    exit(0);
}
?>