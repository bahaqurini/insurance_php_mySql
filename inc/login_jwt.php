
<?php 
//require_once '../inc/config.php';
//require_once '../inc/inc.php';
require_once "../../vendor/autoload.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


// $payload = [
//     'iss' => 'http://example.org',
//     'aud' => 'http://example.com',
//     'iat' => 1356999524,
//     'nbf' => 1357000000
// ];

// $login=new Login_jwt();
// echo $login->msg;
// print_r($login->userData);
// $login->login();
// echo $login->msg;
// print_r($login->userData);
// echo $login->auth;


class Login_jwt
    {
            public $res=0;
            public $userData=null;
            public $msg="not login";
            public $auth=null;
            
            private $key = 'zdfg224tx@46';
            
            
            
            
            
            
            public function __construct() 
            {
                
                $headers = apache_request_headers();
                if(isset($headers['Authorization'])) {
                    $jwt=$headers['Authorization'];
                    try {
                        $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));
                        if ($decoded != null && $decoded !="") { 
                            $this->auth=$jwt;
                            $this->userData=$decoded;
                            $this->msg='login from authorized'; 
                            $this->res=1;
                        }
                        else {
                            $this->msg="authorized not valid";
                            $this->auth=null;
                            $this->userData=null;
                        }
                    }
                    catch(Exception $e)
                    {
                        $this->msg=$e->getMessage();
                        $this->auth=null;
                        $this->userData=null;
                    }
                    
                }
            }
            // public function login()
            // {

            //     $postData = json_decode(file_get_contents('php://input'));
            //     if (isset($postData->check) && $postData->check=='4534' )
            //     {
                    
            //         if (!isset($postData->user) || $postData->user=='' )
            //         {
            //             $this->msg='ادخل اسم الدخول';
            //             $this->auth=null;
            //             $this->userData=null;
                        
            //         }
            //         else
            //         {
            //             if (!isset($postData->pass) || $postData->pass=='' )
            //             {
            //                 $this->msg='no pass';
            //                 $this->auth=null;
            //                 $this->userData=null;
                        
                            
            //             }
            //             else
            //             {
            //                 $this->msg= "username and pass word found";
            //                 $this->loginByUserAndPass($postData->user,$postData->pass) ;
    
            //             }
            //         }
                    
                
            //     }
            // }
                
            
            public function login($username,$password)
            {
                $dbh=conect();
                $sql="SELECT `id`,`fullname`,`MobileNo`,`admin` FROM `WorkTeams` WHERE `username`='$username' AND `password`='$password'";
                $sel=$dbh->query($sql, PDO::FETCH_ASSOC);
                $this->msg="اسم الدخول او كلمه السر غير صحيحه";
                $this->auth=null;
                $this->userData=null;
                foreach ($sel as $row)
                {
                    $this->userData=$row;
                    $this->msg="login by username and password";
                    $this->auth= $jwt = JWT::encode($row, $this->key, 'HS256');
                    $this->res=1;

                }
            }

    
    
            
    }

?>