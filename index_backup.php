<?php

$servername = "localhost";
$username = "ali";
$password = "r4h4s14";
$dbname = "ali";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
//print_r($_REQUEST);

switch($_REQUEST["mode"])
{
		case "post":
				//echo $_REQUEST["field"]."|".$_REQUEST["value"];
				addOrder($_REQUEST["uid"],$_REQUEST["nama_pesanan"],$_REQUEST["quantity"],$_REQUEST["keterangan"],$_REQUEST["estimasi"],$conn); 
			break;
		case "get":
				$stat = isset($_REQUEST["status"])==true?$_REQUEST["status"]:0;
				takeList($_REQUEST["LIMIT"],$stat,$_REQUEST["asc"],$conn);
			break;
		case "getUsr":
				takeListUser($_REQUEST["LIMIT"],$_REQUEST["uid"],$_REQUEST["asc"],$conn);
			break;
		case "changeStatus":
				changeStatus($_REQUEST["id"],$_REQUEST["status"],$conn);
			break;
		case "auth":
				auth($_REQUEST["user"],$_REQUEST["pass"],$conn);
			break;
		case "getOBContact":
				getOBContact($conn);
			break;
}



function post_value($field, $value, $connection)
{
	
	// Check connection
	if ($connection->connect_error) {

	    die("Connection failed: " . $connection->connect_error);
	} 

	$sql = "INSERT INTO `tbl_order` (`FIELD`, `VALUE`, `TIMESTAMP`) VALUES ('".$field."', '". $value ."',CURRENT_TIMESTAMP);";
	echo $sql; 
	
	if ($connection->query($sql) === TRUE) {
	    echo "New record created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . $connection->error;
	}

	
}

function get_value($field, $connection)
{
	// Check connection
	if ($connection->connect_error) {

	    die("Connection failed: " . $connection->connect_error);
	} 

	$sql = "SELECT * FROM `tbl_order` WHERE FIELD='".$field."' ORDER BY ID DESC LIMIT 1";
	//echo $sql; 
	
	$result = $connection->query($sql);
	
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "  {
						\"field\":\"".$field."\",
						\"value\":\"".$row["VALUE"]."\"
					}";
		}
	} else {
		echo "0 results";
	}

	
}

function get_table($connection)
{
	
	// Check connection
	if ($connection->connect_error) {

	    die("Connection failed: " . $connection->connect_error);
	} 

	$sql = "SELECT * FROM `tbl_order`";
	//echo $sql; 
	
	$result = $connection->query($sql);
	
	if ($result->num_rows > 0) {
		// output data of each row
		$data = array();
		$res = array();
		while($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}
		$res['data'] = $data;
		echo json_encode($res);
	} else {
		echo "0 results";
	}
}


function addOrder($uid,$nama_pesanan, $quantity, $keterangan, $estimasi,$connection)
{
	if ($connection->connect_error) {

	    die("Connection failed: " . $connection->connect_error);
	} 

	$sql = "INSERT INTO `tbl_order` (usr_id,nama_pesanan,quantity,keterangan,estimasi) VALUES('".$uid."','".$nama_pesanan."','".$quantity."','".$keterangan."','".$estimasi."')";
	//echo $sql; 
	if ($connection->query($sql) === TRUE) {
	    echo "New record created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . $connection->error;
	}
}


function takeList($LIMIT,$status,$asc,$connection)
{
	// Check connection
	if ($connection->connect_error) {

	    die("Connection failed: " . $connection->connect_error);
	} 
	$sql = "SELECT * FROM `tbl_order` LEFT JOIN tbl_users ON tbl_users.uid = tbl_order.usr_id WHERE status=".$status." ORDER BY tbl_order.id ".$asc." LIMIT ".$LIMIT ;
	//echo $sql . "    |     ";
	$result = $connection->query($sql);
	
	if ($result->num_rows > 0) {
		$data = array();
		$res = array();
		// output data of each row
		while($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}
		$res['count']=count($data);
		$res['data']=$data;
		echo json_encode($res);
	} else {
		echo "{\"count\":0}";
	}
}

function takeListUser($LIMIT,$userid,$asc,$connection)
{
	// Check connection
	if ($connection->connect_error) {

	    die("Connection failed: " . $connection->connect_error);
	} 
	$sql = "SELECT * FROM `tbl_order` LEFT JOIN tbl_users ON tbl_users.uid = tbl_order.usr_id WHERE tbl_order.usr_id=".$userid." ORDER BY tbl_order.id ".$asc." LIMIT ".$LIMIT ;
	//echo $sql . "    |     ";
	$result = $connection->query($sql);
	
	if ($result->num_rows > 0) {
		$data = array();
		$res = array();
		// output data of each row
		while($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}
		$res['count']=count($data);
		$res['data']=$data;
		echo json_encode($res);
	} else {
		echo "{\"count\":0}";
	}
}


function changeStatus($id,$status,$connection)
{
	if ($connection->connect_error) {

	    die("Connection failed: " . $connection->connect_error);
	} 

	$sql = "UPDATE `tbl_order` SET `status`=".$status." WHERE tbl_order.id = ".$id."";
	//echo $sql; 
	if ($connection->query($sql) === TRUE) {
	    echo "New record created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . $connection->error;
	}
}


function auth($user,$pass,$connection)
{
	// Check connection
	if ($connection->connect_error) {

	    die("Connection failed: " . $connection->connect_error);
	} 

	$sql = "SELECT * FROM `tbl_users` WHERE username = '".$user."' AND pass = '".$pass."'";
	//echo $sql . "    |     ";
	$result = $connection->query($sql);
	
	if ($result->num_rows > 0) {
		$data = array();
		$res = array();
		// output data of each row
		while($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}
		$res['count']=count($data);
		$res['data']=$data;
		echo json_encode($res);
	} else {
		echo "{\"count\":0}";
	}
}

function getOBContact($connection)
{
	// Check connection
	if ($connection->connect_error) {

	    die("Connection failed: " . $connection->connect_error);
	} 

	$sql = "SELECT * FROM `tbl_ob_contact`";
	//echo $sql . "    |     ";
	$result = $connection->query($sql);
	
	if ($result->num_rows > 0) {
		$data = array();
		$res = array();
		// output data of each row
		while($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}
		$res['count']=count($data);
		$res['data']=$data;
		echo json_encode($res);
	} else {
		echo "{\"count\":0}";
	}
}

?>
 

