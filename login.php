<?PHP
header('Access-Control-Allow-Origin: *');//'http://localhost:3000
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, X-Api-Key, X-Requested-With, Content-Type, Accept, Authorization');

require_once 'inc/config.php';
require_once 'inc/inc.php';
require_once 'inc/login_jwt.php';

$postData = json_decode(file_get_contents('php://input'));
if (isset($postData->check) && $postData->check=='4534' )
{
    $login=new Login_jwt();

    if (!isset($postData->user) || $postData->user=='' )
    {
        $login->msg='ادخل اسم الدخول';
        $login->auth=null;
        $login->userData=null;
        
    }
    else
    {
        if (!isset($postData->pass) || $postData->pass=='' )
        {
            $login->msg='no pass';
            $login->auth=null;
            $login->userData=null;
        
            
        }
        else
        {
            $login->msg= "username and pass word found";
            $login->login($postData->user,$postData->pass) ;

        }
    }
    $data=['res'=>$login->res,'auth'=>$login->auth,'msg' =>$login->msg];
    echo json_encode($data);
}

?>
