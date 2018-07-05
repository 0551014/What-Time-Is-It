<?php
define('ROOT', dirname(__FILE__));
@require_once(ROOT . '\init.php');
if ($_SESSION['isLogined']) {
    session_destroy();
    returnJSON("You've logged out.");
} else {
    session_destroy();
    showError(-3, "You haven't logged in.");
}
?>