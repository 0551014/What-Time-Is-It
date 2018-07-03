<?php
define('ROOT', dirname(__FILE__));
@require_once(ROOT . '\init.php');
if ($_SESSION['isLogined']) {
    echo 'owowowo';
} else {
    echo 'Please login to use this page.';
}

?>