<?php
define('ROOT', dirname(__FILE__));
@require_once(ROOT . '\init.php');
if ($_SESSION['isLogined']) {
    echo "<p>You've logged in, {$_SESSION['account']}.</p>";
    echo '<p1>Redirect in 5 sec. or click <a href="./member.php">this</a>';
    header("refresh:5; url=member.php");
    exit(0);
}
if (isset($_POST['acc']) && isset($_POST['pwd'])) {
    $account  = $_POST['acc'];
    $password = $_POST['pwd'];
    try {
        $PDO = new PDO($dsn, $dbacc, $dbpwd);
        $PDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        if ($sth = $PDO->prepare("SELECT * FROM `account` WHERE `account` = :account LIMIT 1")) {
            $sth->bindValue(':account', $account, PDO::PARAM_STR);
            if ($sth->execute()) {
                $result = $sth->fetchAll(PDO::FETCH_ASSOC);
                if ( password_verify($password, $result[0]['password']) ) {
                    $_SESSION['isLogined'] = true;
                    $_SESSION['account'] = $account;
                    echo '<p>Hi!' . $account . ', welcome back.</p>';
                    echo '<p1>Redirect in 5 sec. or click <a href="./member.php">this</a>';
                    header("refresh:5; url=member.php");
                } else {
                    echo '<p>Incorrect account and password combination.</p>';
                    echo '<p1>Redirect in 5 sec. or click <a href="./login.php">this</a>';
                    header("refresh:5; url=login.php");
                }
            } else {
                exit('SQL Execute ERROR.');
            }
        } else {
            exit('SQL Statment ERROR.');
        }
    }
    catch (Exception $ex) {
        echo 'Exception Occured.';
        var_dump($ex);
        exit('We are sorry about this error.');
    }


} else {
    header("Location: login.php");
}













?>