<?php
define('ROOT', dirname(__FILE__));
@require_once(ROOT . '\init.php');
if ($_SESSION['isLogined'] != true) {
    showError(1, "Not loggined");
}
if ($_METHOD == 'GET') {
    $accountID = $_SESSION['accountID'];
    try {
        $PDO = new PDO($dsn, $dbacc, $dbpwd);
        $PDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        if ($sth = $PDO->prepare("SELECT * FROM `schedule` WHERE `accountID` = :accountID AND `isDel` = false")) {
            $sth->bindValue(':accountID', $accountID, PDO::PARAM_STR);
            if ($sth->execute()) {
                $result = $sth->fetchAll(PDO::FETCH_ASSOC);
                $schedule = array();
                foreach ($result as $row) {
                    //$schedule[] = array(
                    //    "rowID" => $row['rowID'],
                    //    "title" => $row['title'],
                    //    "createTime" => $row['createTime'],
                    //    "arriveTime" => $row['arriveTime']
                    //    );
                    $schedule[] = array(
                        "userId" => $accountID,
                        "id" => $row['rowID'],
                        "thing" => $row['title'],
                        "time" => $row['createTime'],
                        "deadline" => substr($row['arriveTime'], 0, -3)
                        );
                }
                returnJSON($schedule);
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
}
if ($_METHOD == 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if (isset($data['accountID']) && isset($data['title']) && isset($data['createTime']) && isset($data['arriveTime']) ) {
        if ($data['accountID'] != $_SESSION['accountID']) {
            showError(666, "Trying to slam some data, Huh?");
        }
        try {
            $PDO = new PDO($dsn, $dbacc, $dbpwd);
            $PDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            if ($sth = $PDO->prepare("INSERT INTO `schedule` (`accountID`, `title`, `createTime`, `arriveTime`, `isDel`) VALUE (:accountID, :title, :createTime, :arriveTime, false)")) {
                $sth->bindValue(':accountID', $data['accountID'], PDO::PARAM_INT);
                $sth->bindValue(':title', $data['title'], PDO::PARAM_STR);
                $sth->bindValue(':createTime',  $data['createTime'], PDO::PARAM_STR);
                $sth->bindValue(':arriveTime',  $data['arriveTime'], PDO::PARAM_STR);
                if ($sth->execute()) {
                    if ($sth->rowCount() == 1) {
                        $schedule = array(
                        "userId" => $accountID,
                        "id" => $PDO->lastInsertId(),
                        "thing" => $data['title'],
                        "time" => $data['createTime'],
                        "deadline" => $data['arriveTime']
                        );
                        returnJSON($schedule);
                    } else {
                        showError(-6, "Please contact admin, count=" . $sth->rowCount());
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
if ($_METHOD == 'DELETE') {
    if (isset($_GET['scheduleID'])) {
        $data['scheduleID'] = $_GET['scheduleID'];
        try {
            $PDO = new PDO($dsn, $dbacc, $dbpwd);
            $PDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            if ($sth = $PDO->prepare("UPDATE `schedule` SET `isDel` = true WHERE `rowID` = :rowID AND `accountID` = :accountID")) {
                $sth->bindValue(':accountID', $_SESSION['accountID'], PDO::PARAM_INT);
                $sth->bindValue(':rowID', $data['scheduleID'], PDO::PARAM_INT);
                if ($sth->execute()) {
                    if ($sth->rowCount() == 1) {
                        returnJSON("Deleted Okay.");
                    } else {
                        showError(-6, "Please contact admin, count=" . $sth->rowCount());
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