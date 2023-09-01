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
                // adding a car
                
                if (   isset($postData->car_number) 
                    && isset($postData->owner) 
                    && isset($postData->car_model) 
                    && isset($postData->id) )
                {
                    $carNumber = $postData->car_number;
                    $carOwner=$postData->owner;
                    $carModel = $postData->car_model;
                    $carNote = $postData->note;
                    $id=$postData->id;
                }
                
                if ($carNumber !="" && $carOwner>0 && $carModel>0 && $id>0)
                {
                    $sql = "UPDATE `cars` SET `number`='$carNumber',`model_id`='$carModel',`owner_id`='$carOwner',`note`='$carNote' WHERE `id`='$id';";
                    //$querySql = "SELECT `cars`.`id`,`cars`.`number`,`costumers`.`name`,`car_models`.`manufacture`,`car_models`.`modle`, `cars`.`note` FROM `cars`,`costumers`,`car_models` WHERE `costumers`.`id`=`cars`.`owner_id` and `car_models`.`id`=`cars`.`model_id`";
                    edit($sql);
                }
                else
                {
                    resultJSON(-1, "\"$carNumber>0 && $carOwner>0 && $carModel>0 && $id>0 \"", "empty feld");
                }
                break;
            case 'insurance':
                {
                    if (isset($postData->id) && isset($postData->number) &&isset($postData->car_number) && isset($postData->start_date) && isset($postData->end_date) && isset($postData->value) && isset($postData->real_value)){
                        $id=$postData->id;
                        $number=$postData->number;
                        $owner=$postData->owner;
                        $carNumber=$postData->car_number;
                        $startDate=$postData->start_date;
                        $endData=$postData->end_date;
                        $nextDate=$postData->next_date;
                        $value=$postData->value;
                        $realValue=$postData->real_value;
                        $note =$postData->note;
                        $sql="UPDATE `insurances` SET `number`='$number',`insurances_owner_id`='$owner',`insurances_date`='$startDate',
                        `insurances_end_date`='$endData',`next_batch_date`='$nextDate',`agreed_value`='$value',`actual_value`='$realValue',`note`='$note' WHERE `id`='$id'";
                        //,`car_id`='$carNumber'
                        edit($sql);
                    }
                    else
                    {
                        resultJSON(-1, "\"carNumber=$carNumber  carOwner=$carOwner carModel=$carModel id=$id \"", "empty feld");
                    }
                }
                break;
                //receiptVouche
                case 'receipt_voucher':


    
                    $errName=array("id","insurance_number","voucher_date","amount","that_for","who_pay");
                    $allFeilds=array_merge($errName,array("note"));
                    $valueNames=getDataList($allFeilds);
                    
                    $err=checkPosts($valueNames,$errName);
                    if (count($err)>0)
                    {
                        resultJSON(-1, json_encode($err), "empty feld");
                    }
                    else
                    { 
                        list($id,$insurance_number,$voucher_date,$amount,$that_for,$who_pay,$note)=$valueNames;
                        $sql="UPDATE `Receipts` 
                        SET `date`='$voucher_date',`amont`='$amount',`insurance_id`='$insurance_number',`costumers_id`='$who_pay',`that_for`='$that_for',`note`='$note' WHERE `id`='$id';";
                        
                        edit($sql);
                        
                    }
                    break;
            default:
                // wrong get request
                break;
        }
    }
    
}
function edit($sql)
{

    $dbh = conect();
    $add=$dbh->prepare($sql);
    //echo "after";
    if($add->execute())
    {
        //sqlResult($querySql);
        resultJSON(1, '"rest ok"', "ok");
    }
    else
    {
        //$err=implode(",",$add->errorInfo());
        resultJSON(-1, '"Ssql"' , $add->errorCode());
        //echo $sql;
    }
}
