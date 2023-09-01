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
  

class CarsTable
{
    
     static $carsTableSql = "SELECT `cars`.`id`,`cars`.`number`,`costumers`.`name`,`car_models`.`manufacture`,`car_models`.`modle` , `cars`.`note` ,`insurances`.`number` as `insurances_namber` , `insurances`.`id` as `inserance_id`
     FROM `cars`
     LEFT  JOIN `costumers`
     ON  `cars`.`owner_id` = `costumers`.`id`
     LEFT  JOIN `car_models`
     ON `car_models`.`id`=`cars`.`model_id` 
     LEFT JOIN  `insurances`
     ON `insurances`.`car_id` = `cars`.`id`";

}
class InsuranceTable
{
    static $insuranceTableSql = "SELECT 
    `insurances`.`id`, 
    `insurances`.`number`, 
    `insurances`.`insurances_owner_id`,
    `costumers`.`name` as `insurance_owner_name`,
    `insurances`.`insurances_date`,
    `car_models`.`manufacture`,
    `car_models`.`modle`,
    `insurances`.`insurances_end_date`,
    `insurances`.`next_batch_date`,
    `insurances`.`agreed_value`,
    `insurances`.`actual_value`,
    `insurances`.`note` ,
    `costumers`.`mobile` as `insurance_owner_mobile`,
    `cars`.`number` as `car_number`,
    `cars`.`id` as `car_id`,
    (SELECT `costumers`.`name` FROM `costumers` WHERE `costumers`.`id`=`cars`.`owner_id` LIMIT 1) as `car_owner`,
    (SELECT IFNULL(SUM(`Receipts`.`amont`),0) FROM `Receipts` WHERE `Receipts`.`insurance_id` = `insurances`.`id`) as `amount_paid`
        FROM `insurances`
        LEFT JOIN `costumers`
        ON `insurances`.`insurances_owner_id`=`costumers`.`id`
        LEFT JOIN `cars`
        ON `cars`.`id`=`insurances`.`car_id`
        LEFT JOIN `car_models`
        ON `cars`.`model_id`= `car_models`.`id`
        LEFT JOIN `Receipts`
        ON `insurances`.`id`= `Receipts`.`insurance_id`
        
    ";
    
}
class ReceiptVouchersTable
{
    static $receiptVouchersSql="SELECT `Receipts`.`id`,`insurances`.`number`,\n"

    . "`insurances`.`insurances_owner_id`,\n"
    . "`Receipts`.`insurance_id` ,\n"
    . "`Receipts`.`costumers_id` ,\n"
    . "getCostumName(`insurances`.`insurances_owner_id`) as owner_name,\n"

    . "getCostumName(`Receipts`.`costumers_id`) as who_pay, `Receipts`.`date`, `Receipts`.`amont`,  `Receipts`.`that_for`, `Receipts`.`note` \n"

    . "FROM `Receipts`,`insurances` \n"

    . "WHERE `Receipts`.`insurance_id` = `insurances`.`id`";
}

    //session_start();
   
    
$login = new Login_jwt();


            //   $debugfile = fopen("debug.log", "a") or die("Unable to open file!");
            //   fwrite($debugfile, "file insurence_n.php ");
            //   fwrite($debugfile, "token=$token\n");
            //   fclose($debugfile);



//if ($login->getRes() == 1) {
if ($login->res == 1) {
    if (isset($_GET['do'])) {
        $do = $_GET['do'];
        
        if ($do == 'insurance') {

            //$sql = "SELECT `insurances`.`id`,`cars`.`number` as `car_number`,`insurances`.`number`,`costumers`.`name` as `insurance_owner_name`,`costumers`.`mobile` as `insurance_owner_mobile`, `insurances`.`agreed_value`,`insurances`.`actual_value` ,`car_models`.`manufacture`,`car_models`.`modle`, (SELECT `costumers`.`name` FROM `costumers` where `cars`.`owner_id`=`costumers`.`id`) as `car_owner`, `insurances`.`insurances_date`, `insurances`.`insurances_end_date` , `insurances`.`note` FROM `insurances` ,`cars`,`car_models` ,`costumers` WHERE `insurances`.`car_id` =`cars`.`id` and `cars`.`model_id`=`car_models`.`id` and `insurances`.`insurances_owner_id` = `costumers`.`id`";
            sqlResult(InsuranceTable::$insuranceTableSql."GROUP BY `insurances`.`id`");

            
            

        } else if ($do == 'cars') {
            //$sql = "SELECT `cars`.`id`, `cars`.`name` as `car_name`, `cars`.`model_id` as `car_model`, `cars`.`year` as `car_year`, `cars`.`manufacturer` as `car_manufacturer`, `cars`.`;
            //$sql = "SELECT `cars`.`id`,`cars`.`number`,`costumers`.`name`,`car_models`.`manufacture`,`car_models`.`modle`, `cars`.`note` FROM `cars`,`costumers`,`car_models` WHERE `costumers`.`id`=`cars`.`owner_id` and `car_models`.`id`=`cars`.`model_id`;";
            sqlResult(CarsTable::$carsTableSql);
        }
        else if ($do == 'cars_numbers')
        {
            $adder= (isset($_GET['car_id']))?"OR `cars`.`id`='".$_GET['car_id']."' ":'';

            $sql = "SELECT `cars`.`id`,`cars`.`number` from `cars` where (`cars`.`id` not in (SELECT `insurances`.`car_id` FROM `insurances` WHERE `insurances`.`is_active`='1')) $adder;";
            //$sql2= "SELECT `id`, `name` FROM `costumers` WHERE 1;";
            sqlResult($sql);
        }
        else if ($do == 'models' )
        {
            $sql = "SELECT `id`, CONCAT(`manufacture`,' ',`modle`) as model FROM `car_models` WHERE 1;";
            //$sql2= "SELECT `id`, `name` FROM `costumers` WHERE 1;";
            sqlResult($sql);
        }
        else if ($do == 'owners')
        {
            $sql = "SELECT `id`, `name` FROM `costumers` WHERE 1;";
            //$sql2="SELECT LAST_INSERT_ID() as id;";
            sqlResult($sql);
        }
        else if ($do == 'one_car') {
            $postData = json_decode(file_get_contents('php://input'));
            if (isset($postData->id))
            {
                $id=$postData->id;
                $sql = "SELECT `id`, `number`, `model_id`, `owner_id`, `note` FROM `cars` WHERE `id`='$id'";
                sqlResult($sql);
            }
            else
            {
                resultJSON(-1, '""', "can't find id");
            }
        }
        else if ($do == 'one_insurance') {
            $postData = json_decode(file_get_contents('php://input'));
            if (isset($postData->id))
            {
                $id=$postData->id;
                //$sql = "SELECT `id`, `number`, `insurances_owner_id`, `car_id`, `insurances_date`, `insurances_end_date`, `next_batch_date`, `agreed_value`, `actual_value`, `note` FROM `insurances` WHERE `id`='$id'";
                sqlResult(InsuranceTable::$insuranceTableSql." WHERE `insurances`.`id`='$id'");
            }
            else
            {
                resultJSON(-1, '""', "can't find id");
            }
        }
        else if ($do == 'insurance_owners' )
        {
            $sql = "SELECT `id`, `number` FROM `insurances` WHERE 1;";
            //$sql2= "SELECT `id`, `name` FROM `costumers` WHERE 1;";
            sqlResult($sql);
        }
        else if ($do == 'ReceiptVouchers' )
        {

            sqlResult(ReceiptVouchersTable::$receiptVouchersSql);
        }
        else if ($do == 'insurance_not_linked' )
            sqlResult("SELECT `id`,`number` FROM `insurances` WHERE 1;");
        
        else if ($do == 'one_receiptVoucher') {
            $postData = json_decode(file_get_contents('php://input'));
            if (isset($postData->id))
            {
                $id=$postData->id;
                //$sql = "SELECT `id`, `number`, `insurances_owner_id`, `car_id`, `insurances_date`, `insurances_end_date`, `next_batch_date`, `agreed_value`, `actual_value`, `note` FROM `insurances` WHERE `id`='$id'";
                sqlResult(ReceiptVouchersTable::$receiptVouchersSql." && `Receipts`.`id`='$id'");
            }
            else
            {
                resultJSON(-1, '""', "can't find id");
            }
        }
        
        else
        {
            resultJSON(-1, '""', "can't find $do");
        }
        
    } 
    //one_receiptVoucher
    
    else {
        resultJSON(-1, '""', "nothing to do");
    }





}
else
{
    //echo "{not logged..}{$login->getRes()} : {$login->getMsg()} ";
    resultJSON(-1, '""', "not logged..");
}
?>


