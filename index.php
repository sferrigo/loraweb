<?php
 
//Make sure that it is a POST request.
if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0){
    throw new Exception('Request method must be POST!');
}
 
//Make sure that the content type of the POST request has been set to application/json
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if(strcasecmp($contentType, 'application/json') != 0){
    throw new Exception('Content type must be: application/json');
}
 
//Receive the RAW post data.
$content = trim(file_get_contents("php://input"));
 
//Attempt to decode the incoming RAW post data from JSON.
//$decoded = json_decode($content, true);
$decoded = json_decode($content, false);
 
//If json_decode failed, the JSON is invalid.
if(!is_array($decoded)){
    //throw new Exception('Received content contained invalid JSON!');
}
 
//Process the JSON.
//throw new Exception($decoded);
var_dump($decoded);
//echo $decoded;

if (file_put_contents("dados.json", $content))
	echo "OK!";
else
	throw new Exception('Erro ao gravar arquivo');

//MySQL

$link = mysqli_connect("127.0.0.1", "phpmyadmin", "lora", "lora");

if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;

$app_id = $decoded->app_id;
$dev_id = $decoded->dev_id;
$hardware_serial = $decoded->hardware_serial;
$port = $decoded->port;
$counter = $decoded->counter;
$payload_raw = base64_decode($decoded->payload_raw); 

//$app_id = $decoded["app_id"];
//$dev_id = $decoded["dev_id"];
//$hardware_serial = $decoded["hardware_serial"];
//$port = $decoded["port"];
//$counter = $decoded["counter"];
//$payload_raw = base64_decode($decoded["payload_raw"]); 

$sql_aplicacao = "INSERT INTO aplicacao 
	(app_id, 
	dev_id, 
	hardware_serial, 
	port, 
	counter, 
	payload_raw) 
	    VALUES 
	('" . $app_id . 
	"','" . $dev_id . 
	"'," . $hardware_serial . 
	", " . $port . 
	", " . $counter . 
	", '" . $payload_raw  . "')";	

if(mysqli_query($link, $sql_aplicacao)){
    error_log("Aplicacao Records added successfully.");
} else{
    echo "ERROR: Could not able to execute $sql_aplicacao. " . mysqli_error($link);
}


//Retorna último ID do pacote
   $query = "SELECT id FROM aplicacao ORDER BY id DESC LIMIT 1";
   $result = mysqli_query($link, $query) or die("Erro");
   //while($fetch = mysql_fetch_row($result)){
   //    $aplicacao_id = $fetch[0];
   while($dados=mysqli_fetch_assoc($result)){
       $aplicacao_id = $dados['id'];
   }

//Verifica dados metadata

    $time = $decoded->metadata->time;
    $frequency = $decoded->metadata->frequency;
    $modulation = $decoded->metadata->modulation;
    $data_rate = $decoded->metadata->data_rate;
    $coding_rate = $decoded->metadata->coding_rate;

   $sql_metadata = "INSERT INTO metadata 
	(time, 
	frequency, 
	modulation, 
	data_rate, 
	coding_rate, 
	aplicacao_id) 
	    VALUES 
	('" . $time . 
	"'," . $frequency . 
	",'" . $modulation . 
	"', '" . $data_rate . 
	"', '" . $coding_rate . 
	"', " . $aplicacao_id  . ")";	
	
	
    if(mysqli_query($link, $sql_metadata)){
	error_log("Metadata Records added successfully.");
    } else{
	echo "ERROR: Could not able to execute $sql_metadata. " . mysqli_error($link);
    }

//Cadastr dados GW

$query = "SELECT id FROM metadata ORDER BY id DESC LIMIT 1";
   $result = mysqli_query($link, $query) or die("Erro");
   //while($fetch = mysql_fetch_row($result)){
   //    $aplicacao_id = $fetch[0];
   while($dados=mysqli_fetch_assoc($result)){
       $metadata_id = $dados['id'];
   }

foreach ($decoded->metadata->gateways as $gateways) {
    $gtw_id = $gateways->gtw_id;
    $timestamp = $gateways->timestamp;
    $time = $gateways->time;
    $channel = $gateways->channel;
    $rssi = $gateways->rssi;
    $snr = $gateways->snr;
    $rf_chain = $gateways->rf_chain;
    
    $sql_gateway = "INSERT INTO gateway_record 
	(gtw_id, 
	timestamp, 
	
	channel, 
	rssi, 
	snr,
	rf_chain,
	metadata_id) 
	    VALUES 
	('" . $gtw_id . 
	"'," . $timestamp . 
	//"," . $time . 
	", " . $channel . 
	", " . $rssi . 
	", " . $snr . 
	", " . $rf_chain . 
	", " . $metadata_id  . ")";	
	
	error_log("$sql_gateway");
	
    if(mysqli_query($link, $sql_gateway)){
	error_log("Gateway Records added successfully.");
    } else{
	error_log("ERROR: Could not able to execute $sql_gateway. " . mysqli_error($link));
    }

}

mysqli_close($link);



?>
