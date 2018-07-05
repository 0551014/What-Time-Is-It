<?php
//define('ROOT', dirname(__FILE__));
@require_once(ROOT . '\init.php');
showError(-90, "comming very soooon"); // unCompleate function
if ($_SESSION['isLogined']) {
    echo "<p>You've logged in, {$_SESSION['account']}.</p>";
    echo '<p1>Redirect in 5 sec. or click <a href="./member.php">this</a>';
    header("refresh:5; url=member.php");
    exit(0);
}
if (isset($_POST['mail'])) {
    $mail = $_POST['mail'];
    try {
        $PDO = new PDO($dsn, $dbacc, $dbpwd);
        $PDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        if ($sth = $PDO->prepare("SELECT * FROM `account` WHERE `mail` = :mail LIMIT 1")) {
            $sth->bindValue(':mail', $mail, PDO::PARAM_STR);
            if ($sth->execute()) {
                $result = $sth->fetchAll(PDO::FETCH_ASSOC);
                if ($sth->rowCount() == 1) {
                    mail($result[0]['mail'], 'Reset password for What-Time-Is-It', 'message....');
                }
                echo '<p>A mail has been sent to your mail if the address is vaild.</p>';
                echo '<p1><a href="./index.php">Back to index</a></p1>';

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


    exit(0);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="description" value="Forget Password page @ What time is it" />
    <title>Forget Password</title>
</head>
<body>
    <form method="POST" action="forgetpassword.php">
        Mail:
        <input type="text" name="mail" value="" autocomplete="off" />
        <input type="submit" value="Submit" />
    </form>
</body>
</html>