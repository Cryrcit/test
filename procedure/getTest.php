<?php
// Pull in the NuSOAP code
include_once(__DIR__."/../lib/nusoap.php");

// Create the server instance
$server = new soap_server();

// Initialize WSDL support
$server->configureWSDL('mathwsdl', 'urn:mathwsdl');

// Register the getTest method to expose its
// method name
$server->register('getTest',
// input parameters
	array(	'cliente' => 'xsd:string',
			'data' => 'xsd:string'),
// output parameters
	array('getTest' => 'xsd:string'),
// namespace
	'urn:mathwsdl',
// soapaction
	'urn:getTestwsdl#getTest',
	'rpc',       // style
	'encoded',   // use
// documentation
	'Returning Change'
);


// PHP function

function getTest($cliente, $data) {
// Define the getTest method as a

	//parametri di connessione aldatabase
	include_once(__DIR__."/../include/db_connect.php");
	
	$url='http://localhost/test/procedure/getTest.php?wsdl';

	// Create the client instance
	$trace = array('trace' => 1);
	$client = new nusoap_client(
	/*endpoint*/ $url,
    /*wsdl*/ true,
    /*proxyhost*/ false,
    /*proxyport*/ false,
    /*proxyusername*/ false,
    /*proxypassword*/ false,
    /*timeout*/ 500, //here you can define timeout
    /*response_timeout*/ 500, //here is what you want to define
    /*portName*/ '');

	// Check for an error
	$err = $client->getError();
	if ($err) {
	   // Display the error
	   echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
	   // At this point, you know the call that follows will fail
	}

	if ($cliente == null) {
		$cliente = " AND customer > 0";
	} else {
		$cliente = " AND customer = $cliente";
	}
	if ($data == null) {
		$data = " AND date > '0001-01-01'";
	} else {
		$data = " AND date = '$data'";
	}

	$query =" SELECT a.customer, a.date AS data, a.valuta, a.value,
								'€' AS a_valuta,
								(a.value*b.cambio) AS controvalore
							FROM tabella a, cambio b WHERE a.Id > 0
								$cliente
								$data
								AND a.strvaluta=b.da_valuta
								AND a.date=b.data
							ORDER BY a.customer, a.date";

    $res = mysqli_query($conn, $query) or die("database error:". mysqli_error($conn));

	$products = array();
	if(mysqli_num_rows($res)) {
		$i=1;
		while($product = mysqli_fetch_assoc($res)) {
			$products[] = array_map('utf8_encode',(array("customer" => $product['customer'],
														"data" => $product['data'],
														"valuta" => $product['valuta'],
														"valore" => $product['value'],
														"a_valuta" => $product['a_valuta'],
														"controvalore" => $product['controvalore']
														)));
			$i++;
		}
	}

	$response=json_encode($products, JSON_PRETTY_PRINT);
	return $response;
}

$server->service(file_get_contents("php://input"));
?>