<?php
// Create the client instance
$server='www.cryrc.it';
$url='http://'.$server.'/test/getTest.php?wsdl';
//$url='http://'.$server.'/test/callprocedure.php?wsdl';
$trace = array('trace' => 1);
$client = new nusoap_client($url, true);

// Check for an error
$err = $client->getError();
if ($err) {
   // Display the error
   echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
   // At this point, you know the call that follows will fail
}
?>