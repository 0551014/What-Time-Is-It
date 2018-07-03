<?php
define('ROOT', dirname(__FILE__));
@require_once(ROOT . '\init.php');
if ($_SESSION['isLogined']) {
    echo "<p>You've logged in, {$_SESSION['account']}.</p>";
    echo '<p1>Redirect in 5 sec. or click <a href="./member.php">this</a>';
    header("refresh:5; url=member.php");
    exit(0);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="description" value="Login page @ What time is it" />
    <title>登入</title>
</head>
<body>
    <form method="POST" action="loginCheck.php">
        帳號:
        <input type="text" name="acc" value="" autocomplete="off" />
        <br />
        密碼:
        <input type="text" name="pwd" value="" autocomplete="off" />
        <input type="submit" value="送出" />
    </form>
</body>
</html>