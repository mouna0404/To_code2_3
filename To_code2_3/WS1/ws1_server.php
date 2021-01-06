<?php

 /** 
  @Description: Book Information Server Side Web Service:
  This Script creates a web service using NuSOAP php library. 
  fetchBookData function accepts ISBN and sends back book information.
 */
 require_once('dbconn.php');
 require_once('lib/nusoap.php'); 
 $server = new nusoap_server();


 /* Method to insert a new book */
function insertPays($name, $langue){
  global $dbconn;
  $sql_insert = "insert into pays (name,  langue) values ( :name, :langue)";
  $stmt = $dbconn->prepare($sql_insert);
  // insert a row
  $result = $stmt->execute(array(':name'=>$name, ':langue'=>$langue));
  if($result) {
    return json_encode(array('status'=> 200, 'msg'=> 'success'));
  }
  else {
    return json_encode(array('status'=> 400, 'msg'=> 'fail'));
  }
  
  $dbconn = null;
  }
/* Fetch 1 book data */
function fetchPaystData($name){
	global $dbconn;
	$sql = "SELECT id, name, langue FROM pays 
	        where name = :name";
  // prepare sql and bind parameters
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':name', $name);
    // insert a row
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    return json_encode($data);
    $dbconn = null;
}
//http://www.oorsprong.org/websamples.countryinfo
$server->configureWSDL('paysserver', 'urn:pays');
$server->register('fetchPaystData',
			array('name' => 'xsd:string'),  //parameter
			array('data' => 'xsd:string'),  //output
			'urn:pays',   //namespace
			'urn:pays#fetchPaysData' //soapaction
      );  
      $server->register('insertPays',
			array('name' => 'xsd:string', 'langue' => 'xsd:string'),  //parameter
			array('data' => 'xsd:string'),  //output
			'urn:pays',   //namespace
			'urn:pays#insertPays' //soapaction
            );  

$server->service(file_get_contents("php://input"));

?>