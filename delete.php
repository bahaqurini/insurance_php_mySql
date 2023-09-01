<?php
header('Content-Type: application/json; charset=utf-8');
//header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, X-Api-Key, X-Requested-With, Content-Type, Accept, Authorization');


require_once 'inc/config.php';
require_once 'inc/inc.php';
require_once 'inc/login_jwt.php';
$login = new Login_jwt();
if ($login->res == 1) {
    if (isset($_GET['do']))
    {
        $do = $_GET['do'];
        $postData = json_decode(file_get_contents('php://input'));
        switch ($do) {
            case 'car':
                if (isset($postData->id)) {
                    $id = $postData->id;
                    $deleteSql="DELETE FROM `cars` WHERE `id`='$id';";
                    //$querySql = "SELECT `cars`.`id`,`cars`.`number`,`costumers`.`name`,`car_models`.`manufacture`,`car_models`.`modle`, `cars`.`note` FROM `cars`,`costumers`,`car_models` WHERE `costumers`.`id`=`cars`.`owner_id` and `car_models`.`id`=`cars`.`model_id`";
                    sqldelete($deleteSql);
                }
                
            break;
            case 'insurance':
                if (isset($postData->id)) {
                    $id = $postData->id;
                    $deleteSql="DELETE FROM `insurances` WHERE `id`='$id';";
                    //$querySql = "SELECT `cars`.`id`,`cars`.`number`,`costumers`.`name`,`car_models`.`manufacture`,`car_models`.`modle`, `cars`.`note` FROM `cars`,`costumers`,`car_models` WHERE `costumers`.`id`=`cars`.`owner_id` and `car_models`.`id`=`cars`.`model_id`";
                    sqldelete($deleteSql);
                }
                
            break;
            default:
            // wrong get request
            break;
            case 'receiptVoucher':
                if (isset($postData->id)) {
                    $id = $postData->id;
                    $deleteSql="DELETE FROM `Receipts` WHERE `id`='$id';";
                    //$querySql = "SELECT `cars`.`id`,`cars`.`number`,`costumers`.`name`,`car_models`.`manufacture`,`car_models`.`modle`, `cars`.`note` FROM `cars`,`costumers`,`car_models` WHERE `costumers`.`id`=`cars`.`owner_id` and `car_models`.`id`=`cars`.`model_id`";
                    sqldelete($deleteSql);
                }
                
            break;
    }
}

}
function sqldelete($deleteSql)
{

    $dbh = conect();
    $add=$dbh->prepare($deleteSql);
    //echo "after";
    if($add->execute())
    {
        resultJSON(1, '""', "success");
    }
    else
    {
        resultJSON(-1, '""', "not success");
    }
}
