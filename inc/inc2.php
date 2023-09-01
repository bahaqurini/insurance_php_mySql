<?PHP
function sql($dbh,$sql)
{
	$add=$dbh->prepare($sql);
	$addsucc=$add->execute();
	return $addsucc;
}

function conect()
{	
	$host=dbhost;
	$dbname=dbname;
	$dbh = new PDO("mysql:host=$host;dbname=$dbname", dbuser, dbpass);
	$dbh -> exec("SET CHARACTER SET utf8");
	return $dbh ;
}
function addNotification($dbh,$teamId,$buildId,$actionId,$actionNumber,$other)
{
    $sql="INSERT INTO `Notification`(`team_id`, `build_id`, `action_id`, `action_number`, `other`) 
    VALUES ('$teamId','$buildId','$actionId','$actionNumber','$other');";
    //$dbh=conect();
    $add=$dbh->prepare($sql);
	$add->execute();
    //echo $sql;

}

?>