<?php
define('ROOT', dirname(__FILE__));
@require_once(ROOT . '\init.php');
if ($_SESSION['isLogined']) {
    echo "<p>You've logged in, {$_SESSION['account']}.</p>";
    echo '<p1>Redirect in 5 sec. or click <a href="./member.php">this</a>';
    header("refresh:5; url=member.php");
    exit(0);
}
if ($_METHOD == 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if (isset($data['account']) && isset($data['password'])) {
        $account = $data['account'];
        $password = $data['password'];
        $mail = isset($data['mail']) ? $data['mail'] : "NULL";

        $pattern = '/[\w]{1,25}/'; // 比對字母數字及底線 1~25個字元
        preg_match($pattern, $account, $result);
        if ( strlen($account) != strlen($result[0])  || strlen($result[0]) == 0) {
            showError(-9, "Account must have at most 25 chars includes A-Z a-z 0-9 or \"_\"");
        }
        $pattern = '/[\S ]{8,100}/'; // 比對 8~100個字元
        preg_match($pattern, $password, $result);
        if ( strlen($password) != strlen($result[0])  || strlen($result[0]) == 0 ) {
            showError(-9, "Password must have 8~100 chars");
        }
        $password = password_hash($password, PASSWORD_BCRYPT);
        try {
            $PDO = new PDO($dsn, $dbacc, $dbpwd);
            $PDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            if ($sth = $PDO->prepare("SELECT * FROM `account` WHERE `account` = :account LIMIT 1")) {
                $sth->bindValue(':account', $account, PDO::PARAM_STR);
                if ($sth->execute()) {
                    if ($sth->rowCount() == 1) {
                        showError(-5, "Account exists.");
                    }
                } else {
                    showError(-100, "SQL Execute ERROR.");
                }
            } else {
                showError(-100, "SQL Statment ERROR.");
            }



            if ($sth = $PDO->prepare("INSERT INTO `account` (`account`, `password`, `mail`, `regDate`) VALUE (:account, :password, :mail, NOW())")) {
                $sth->bindValue(':account', $account, PDO::PARAM_STR);
                $sth->bindValue(':password', $password, PDO::PARAM_STR);
                $sth->bindValue(':mail', $mail, PDO::PARAM_STR);
                if ($sth->execute()) {
                    if ($sth->rowCount() == 1) {
                        $_SESSION['isLogined'] = true;
                        $_SESSION['accountID'] = $PDO->lastInsertId('accountID');
                        $_SESSION['account'] = $account;
                        returnJSON("Account created.");
                    } else {
                        showError(-40, "Contact admin.");
                    }
                } else {
                    showError(-100, "SQL Execute ERROR.");
                }
            } else {
                showError(-100, "SQL Statment ERROR.");
            }
        }
        catch (Exception $ex) {
            showError(-999, "Exception Occured.");
        }
    } else {
        showError(100, "Wrong Data");
    }
}
?>