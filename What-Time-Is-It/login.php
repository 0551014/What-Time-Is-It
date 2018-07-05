<?php
define('ROOT', dirname(__FILE__));
@require_once(ROOT . '\init.php');

if ($_METHOD == 'GET') {
    if ($_SESSION['isLogined'] == true) {
        $account = array (
            "accountID" => $_SESSION['accountID'],
            "account" => $_SESSION['account']
            );
        returnJSON($account);
    } else {
        showError(1, "Not loggined");
    }
}
if ($_METHOD == 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if (isset($data['account']) && isset($data['password'])) {
        $account  = $data['account'];
        $password = $data['password'];
        try {
            $PDO = new PDO($dsn, $dbacc, $dbpwd);
            $PDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            if ($sth = $PDO->prepare("SELECT * FROM `account` WHERE `account` = :account LIMIT 1")) {
                $sth->bindValue(':account', $account, PDO::PARAM_STR);
                if ($sth->execute()) {
                    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
                    if ( password_verify($password, $result[0]['password']) ) {
                        $_SESSION['isLogined'] = true;
                        $_SESSION['accountID'] = $result[0]['rowID'];
                        $_SESSION['account'] = $result[0]['account'];
                        // Login successful
                        $account = array (
                            "accountID" => $_SESSION['accountID'],
                            "account" => $_SESSION['account']
                        );
                        returnJSON($account);
                    } else {
                        showError(-88, "Incorrect account and password combination");
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