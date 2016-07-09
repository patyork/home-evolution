<?PHP

include('config.php');

$DeviceId = $_POST['DeviceId'];
$Data = $_POST['Data'];
//$EventName = $_POST['EventName'];
//$PublishedAt = $_POST['PublishedAt'];

if(!empty($DeviceId) && !empty($Data))
{
  
  // Database Connection ==========
  $conn = new mysqli($SQLhost, $SQLuser, $SQLpass, $SQLdb);
   
  // check connection
  if ($conn->connect_error) {
	trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
  }
  
  $sql = "INSERT INTO debug (DeviceId, Message)
  VALUES ('$DeviceId', '$Data')";
  
  if ($conn->query($sql) === TRUE) {
	  //echo "New record created successfully";
  } else {
	  //echo "Error: " . $sql . "<br>" . $conn->error;
  }
  
  $conn->close();
}
else  // TODO: debug stuff
{
	$Data = 'command1:value1;command2:value2';
	
	//switch	
}

?>
