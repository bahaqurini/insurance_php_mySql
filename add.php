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
        $postDataArray = json_decode(file_get_contents('php://input'), true);

        switch ($do) {
            case 'car':

                $carNumber= (isset($postData->car_number))? $postData->car_number:'';
                $carOwner = (isset($postData->owner)     )? $postData->owner :'';
                $carModel = (isset($postData->car_model) )? $postData->car_model :'';
                $carNote  = (isset($postData->note)      )? $postData->note :'';

                $err=array();
                if ($carNumber=='') array_push($err,"carNumber not set");
                if ($carOwner =='') array_push($err,"carOwner not set" );
                if ($carModel =='') array_push($err,"carModel not set" );


                if (count($err)>0)
                {
                    resultJSON(-1,  json_encode($err), "empty feld");
                }
                else
                {
                    $addSql = "INSERT INTO `cars`( `number`, `model_id`, `owner_id`, `note`) VALUES ('$carNumber',$carModel,$carOwner,'$carNote');";
                    //$querySql = "SELECT `cars`.`id`,`cars`.`number`,`costumers`.`name`,`car_models`.`manufacture`,`car_models`.`modle`, `cars`.`note` FROM `cars`,`costumers`,`car_models` WHERE `costumers`.`id`=`cars`.`owner_id` and `car_models`.`id`=`cars`.`model_id`";
                    add($addSql);
                }

                
                
                break;

            case 'model':
                if (isset($postData->model) && isset($postData->manufacture)) {
                    $model = $postData->model;
                    $manufacture=$postData->manufacture;
                    $addSql = "INSERT INTO `car_models` ( `manufacture`, `modle`) VALUES ( '$manufacture', '$model');";
                    $querySql="SELECT  `id`, CONCAT(`manufacture`,' ',`modle`) as model FROM `car_models` WHERE 1;";
                    add($addSql,$querySql);
                }
  
                break;
            case 'owner':
                if (isset($postData->name) && isset($postData->mobile) && isset($postData->date))
                {
                    $name=$postData->name;
                    $mobile=$postData->mobile;
                    $date=$postData->date;
                    $addSql="INSERT INTO `costumers`(`name`, `mobile`, `license_end_date`) VALUES ('$name','$mobile','$date');";
                    $querySql="SELECT `id`, `name` FROM `costumers` WHERE 1;";
                    add($addSql,$querySql);
                }
                else 
                {
                    echo "not found";
                }
                break;
            case 'insurances':

                
                $number=(isset($postData->number))? $postData->number:'';
                $owner =(isset($postData->owner)) ? $postData->owner:'';
                $carNumber=(isset($postData->car_number))?$postData->car_number:'';
                $startDate=(isset($postData->start_date))?$postData->start_date:'';
                $endData=(isset($postData->end_date) )?$postData->end_date:'';
                $nextDate=(isset($postData->next_date))?$postData->next_date:'';
                $value=(isset($postData->value))?$postData->value:'';
                $realValue=(isset($postData->real_value))?$postData->real_value:'';
                $note =(isset($postData->note))?$postData->note:'';
                $err=array();
                if ($number=='') array_push($err,"number");
                if ($owner=='') array_push($err,"owner");
                if ($carNumber=='') array_push($err,"carNumber");
                if ($startDate=='') array_push($err,"startDate");
                if ($endData=='') array_push($err,"endData");
                if ($nextDate=='') array_push($err,"nextDate");
                if ($value=='') array_push($err,"value");
                if ($realValue=='') array_push($err,"realValue");


                //$errName=array("number","owner","carNumber","startDate","endData","nextDate","value","realValue");
                //$valueNames=getDataList($errName);
                //$err=checkPosts($valueNames,$errName);
                
                if (count($err)>0)
                {
                    resultJSON(-1, json_encode($err), "empty feld");
                }
                else
                {
                    //list($number,$owner,$carNumber,$that_for,$who_pay)=$valueNames;
                    // $addSql="INSERT INTO `insurances`( `number`, `insurances_owner_id`, `insurances_date`, `insurances_end_date`, `next_batch_date`, `agreed_value`, `actual_value`, `note`) 
                    // VALUES ('$number','$owner','$startDate','$endData','$nextDate','$value','$realValue','$note');
                    // UPDATE `cars` SET `cars`.`insurance_id`=LAST_INSERT_ID() WHERE `cars`.`id`='$carNumber';";
                    $addSql="INSERT INTO `insurances`( `number`, `insurances_owner_id`, `car_id`, `insurances_date`, `insurances_end_date`, `next_batch_date`, `agreed_value`, `actual_value`, `note`) 
                    VALUES ('$number','$owner','$carNumber','$startDate','$endData','$nextDate','$value','$realValue','$note');";
                    //$querySql = "SELECT `insurances`.`id`, `insurances`.`number`,`costumers`.`name` as `insurance_owner_name`,`costumers`.`mobile` as `insurance_owner_mobile`, `insurances`.`agreed_value`,`insurances`.`actual_value` ,`car_models`.`manufacture`,`car_models`.`modle`, (SELECT `costumers`.`name` FROM `costumers` where `cars`.`owner_id`=`costumers`.`id`) as `car_owner`, `insurances`.`insurances_date`, `insurances`.`insurances_end_date` , `insurances`.`note` FROM `insurances` ,`cars`,`car_models` ,`costumers` WHERE `insurances`.`car_id` =`cars`.`id` and `cars`.`model_id`=`car_models`.`id` and `insurances`.`insurances_owner_id` = `costumers`.`id`";
                    
                    //echo $addSql; // add for testing
                    add($addSql);
                }

                break;
            case 'receipt_voucher':

                
                // insurance_number:'',
                // voucher_date:date,
                // amount:0,
                // that_for:'1',
                // who_pay:'',
                // note:''
                // $insurance_number=(isset($postData->insurance_number))? $postData->insurance_number:'';
                // $voucher_date=(isset($postData->voucher_date))? $postData->voucher_date:'';
                // $amount=(isset($postDataArray['amount']))? $postDataArray['amount']:'';
                // $that_for=(isset($postData->that_for))? $postData->that_for:'';
                // $who_pay=(isset($postData->who_pay))? $postData->who_pay:'';
                // $note=(isset($postData->note))? $postData->note:'';
                //$valueNames=array($insurance_number,$voucher_date,$amount,$that_for,$who_pay);
                //$errName=array("insurance_number","voucher_date","amount","that_for","who_pay");

                $errName=array("insurance_number","voucher_date","amount","that_for","who_pay");
                $allFeilds=array_merge($errName,array("note"));
                $valueNames=getDataList($allFeilds);
                
                $err=checkPosts($valueNames,$errName);
                if (count($err)>0)
                {
                    resultJSON(-1, json_encode($err), "empty feld");
                }
                else
                { 
                    list($insurance_number,$voucher_date,$amount,$that_for,$who_pay,$note)=$valueNames;
                    $addSql="INSERT INTO `Receipts`( `date`, `amont`, `insurance_id`, `costumers_id`, `that_for`, `note`) 
                    VALUES ('$voucher_date','$amount','$insurance_number','$who_pay','$that_for','$note');";
                    add($addSql);
                }
                break;

            default:
                // wrong get request
                echo "wrong get request";
                break;
        }
    }
    
}


function add($addSql,$querySql=null)
{

    $dbh = conect();
    $add=$dbh->prepare($addSql);
    //echo "after";
    if($add->execute())
    {
        if ($querySql!=null)
            sqlResult($querySql);
        else
        resultJSON(1, '"success"', "success");
    }
    else
    {
        //$err=implode(",",$add->errorInfo());
        resultJSON(-1, '"'.$addSql.'"', $add->errorCode());
    }
}

?>