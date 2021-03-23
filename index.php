<!doctype html>
<html lang="it">
  <head>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />

	<!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

	<title>Cambiovaluta</title>
	
	<script type="text/javascript">
		function Inviacode() {
			conf=Convalidaonline();
			if (conf==true) {
				document.FormRich.submit ();			
			}
			return false;
		}
		
		function Convalidaonline() {
<!--		Verifica online -->
			$check = document.FormRich.Online.value;
			if ($check == "") {
				alert ("Attenzione, bisogna inserire Si / No !");
				document.FormRich.Online.focus()
				return false ;
			} else {
				if ($check == "Si" | $check == "No") {
					return true;
				}
				alert ("Attenzione, scelta errata !");
				document.FormRich.Online.focus()
				return false ;
			}
<!--		Deve essere aggiunta la verifica del formato data -->
			$check = document.FormRich.data.value;
		}
	</script>	
	
  </head>

<body>

<form method='post' action='index.php?modo=0' name="FormRich" method="post">
  <div class="form-row align-items-center pt-3 pl-5">
    <div class="col-auto">      
      <input type="text" class="form-control mb-2" id="inlineFormInput" name='Online' placeholder="Online Si / No">
    </div>
  </div>

  <div class="form-row align-items-center pt-1 pl-5">
    <div class="col-auto">      
      <input type="text" class="form-control mb-2" id="inlineFormInput" name='Cliente' placeholder="Codice Cliente">
    </div>
  </div>

  <div class="form-row align-items-center pt-1 pl-5">
    <div class="col-auto">      
      <input type="text" class="form-control mb-2" id="inlineFormInput" name='Data' placeholder="Data nel formato yyyy-mm-gg">
    </div>
   </div>
   
 <div class="form-row align-items-center pt-1 pl-5">
    <div class="col-auto">
      <button type="submit" class="btn btn-primary mb-2" onclick="Inviacode();return false;">Ricerca</button>
    </div>
  </div>
</form>

<?php

	header("X-Accel-Buffering: no");
	
	// Pull in the NuSOAP code
	
	//Libreria del prodotto NuSOAP (REST/API)
	require_once('lib/nusoap.php');
	
	// Parametri di connessione API
 	require_once('include/clientP.php');	
	
		
	IF(isset($_GET['modo'])){

		$modo=$_GET['modo'];

//Quì volendo possono essere diversificate le tipologie di "modo", per ora ho fattu una sola modalità.....

		//----------------------------------------- MODO = 0 --------------------------------------
		if($modo==0){

			$Online=$_POST['Online'];
			$Cliente=$_POST['Cliente'];
			$Data=$_POST['Data'];
			
			if ($Online=="No") {
				//devo leggere dal database
				//Primo step connetto il database locale
				require_once('include/db_connect.php');

				if ($Cliente == null) {
					$cliente = " AND customer > 0";
				} else {
					$cliente = " AND customer = $Cliente";
				}
				if ($Data == null) {
					$data = " AND date > '0001-01-01'";
				} else {
					$data = " AND date = '$Data'";
				}

				$sql =" SELECT a.customer, a.date AS data, a.valuta, a.value,
								'€' AS a_valuta,
								(a.value*b.cambio) AS controvalore
							FROM tabella a, cambio b WHERE a.Id > 0
								$cliente
								$data
								AND a.strvaluta=b.da_valuta
								AND a.date=b.data
							ORDER BY a.customer, a.date";
				$result = $conn->query($sql);
				if ($result) {
					while($row = $result->fetch_assoc()) {
						$stringa= utf8_encode("Cliente: " . $row["customer"]. " - data: " . $row["data"] .
							" - valuta: " . $row["valuta"]. " - valore: " . $row["value"] .
							" - a_valuta: ");
						$stringa=$stringa . $row["a_valuta"];
						$stringa=$stringa . utf8_encode(" - controvalore: ". $row["controvalore"]) . "<br />";
						echo $stringa;
					}
				}
				$conn->close();
			} else {
				//Leggo da API

				//----------------------------------------- Preparazione chiamata server API  --------------------------------------

 				$request_array = array(
 					'cliente' => $Cliente,
 					'data' => $Data
 					);
			
				$result = $client->call($request_array);
				
			
				// Check for a fault
				if ($client->fault) {
			
					echo '<h2>Fault</h2><pre>';
					print_r($result);
					echo '</pre>';
			
				} else {
				
					// Check for errors
					$err = $client->getError();
					if ($err) {
						// Display the error
						echo '<h2>Errore</h2><pre>' . $err . '</pre>';
					} else {		
					
						$json=json_decode($result, true);			
									
						echo "<br>_____________________________________________________________________________<br>";
						
						//echo '<h2>Result</h2><pre>';
						//print_r($json);
						//echo '</pre>';
						
						// Dati estrapolati
						
						for ($i = 0; $i <= count($json); $i++) {
							$customer=$json[$i]['customer'];
							$data=$json[$i]['data'];
							$valuta=$json[$i]['valuta'];
							$valore=$json[$i]['valore'];
							$a_valuta=$json[$i]['a_valuta'];
							$controvalore=$json[$i]['controvalore'];
							
							$stringa= utf8_encode("Cliente: " . $customer . " - data: " . $data .
								" - valuta: " . $valuta . " - valore: " . $value .
								" - a_valuta: ");
							$stringa=$stringa . $a_valuta;
							$stringa=$stringa . utf8_encode(" - controvalore: ". $controvalore) . "<br />";
							echo $stringa;
						}
					}
				}
			}
		}			
	}
?>
</body>
</html>