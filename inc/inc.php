<?PHP

 function conect()
{	
	$host=dbhost;
	$dbname=dbname;
	$dbh = new PDO("mysql:host=$host;dbname=$dbname", dbuser, dbpass);
	$dbh -> exec("SET CHARACTER SET utf8");
	return $dbh ;
}
function sql($dbh,$sql)
{
	$add=$dbh->prepare($sql);
	$addsucc=$add->execute();
	return $addsucc;
}
  
function resultJSON($res,$data,$msg,$data2="") {
    if ($data2=="") $adder=""; 
    else $adder=", \"data2\": $data2";
    echo "{\"result\": $res,\"data\": $data,\"massage\":\"$msg\" $adder}";
}
function sqlResult($sql,$sql2="") {
    $dbh = conect();
    $sel = $dbh->query($sql, PDO::FETCH_ASSOC);
    $out = json_encode($sel->fetchAll(),JSON_UNESCAPED_UNICODE);
    if ($sql2=="") {
        resultJSON(1, $out, "success");
        //echo $sql;
    }
    else {
        $sel = $dbh->query($sql2, PDO::FETCH_ASSOC);
        $out2 = json_encode($sel->fetchAll(),JSON_UNESCAPED_UNICODE);
        resultJSON(1, $out, "success",$out2);
    }
}
function checkPosts($valueNames,$errName)
{
    $err=array();
    $i=0;
    foreach ($valueNames as $v)
    {
        if ($v =='') array_push($err,$errName[$i]." not set'$v'");
        $i++;
    }
    return $err;
}
function getDataList($valueNames)
{
    $postData = json_decode(file_get_contents('php://input'), true);
    $arr=array();
    foreach ($valueNames as $va)
    {
        $vl=(isset($postData[$va]))? $postData[$va]:'';
        array_push($arr,$vl);
    }
    return $arr;
}
?>