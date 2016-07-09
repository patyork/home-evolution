<?php
include('config.php');

$start = microtime(TRUE); // Start counting
$data['start'] = $start;


$errors = array();  // array to hold validation errors
$data = array();        // array to pass back data
$command = 'invalid';	// incoming command

// validate the variables ========
if (empty($_POST['command']))
  $errors['command'] = 'Command is required.';
else $command = $_POST['command'];


$tmp = microtime(TRUE);
$data['SetupandPost'] = $tmp - $start;


// Database Connection ==========
$conn = new mysqli($SQLhost, $SQLuser, $SQLpass, $SQLdb);
 
// check connection
if ($conn->connect_error) {
  trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
}

$tmp2 = microtime(TRUE);
$data['DBConnect'] = $tmp2 - $tmp;


// preprocess command ============
$data['args'] = $command;
$args = $command;


// presend check ================




// Enpoint Query
$sql="SELECT * FROM device WHERE UserId=2";
 
$rs=$conn->query($sql);
 
if($rs === false) {
  trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);
} else {
  $rows_returned = $rs->num_rows;
}

$rs->data_seek(0);
while($row = $rs->fetch_assoc()){
	$data['sql'] = $row;
}
$conn->close();

$tmp3 = microtime(TRUE);
$data['SqlQuery'] = $tmp3 - $tmp2;


// send command ==================
$url = 'https://api.particle.io/v1/devices/' . $data['sql']['DeviceId'] .'/led?access_token=' . $data['sql']['AccessToken'];
$payload = array('args' => $args);

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($payload)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) {
	/* Handle error */

}

$tmp4 = microtime(TRUE);
$data['SendCommand'] = $tmp4 - $tmp3;

// return a response ==============

// response if there are errors
if ( ! empty($errors)) {

  // if there are items in our errors array, return those errors
  $data['success'] = false;
  $data['errors']  = $errors;
} else {

  // if there are no errors, return a message
  $data['success'] = true;
  $data['message'] = 'Success!';
}

// return all our data to an AJAX call
echo json_encode($data);

?>