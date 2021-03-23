# test
 
La creazione della tabella #(tabella) può essere divisa in due tabelle, esportando le valute in una altra tabella che appunto contiene tutte le valute
(stringa e simbolo).

La seconda tabella cambio riporta una ipotetica tabella che contenga i cambi della giornata (in alternativa può essere usata l'interrogazione API
con i contenuti online (logicamente anche in questo caso ho la stessa tabella con i valori per simulare l'interrogazione dei valori online).
 
Le lunghezze dei campi etc. devono essere concordate, ad esempio un campo valuta 3,2 si limita ai 999,99).

Il database è contenuto in un server WAMP (Wampserver64)

mysql database test
	user root
	passwd ''
	
Io ho sempre usato prodotti opensource pertanto anche in questo caso ho installato un server SOAP (NuSOAP) in un server esterno (già fatto a suo tempo)
per l'utilizzo della (semplice) applicazione.

Ho messo nella cartella il file getTest.php che contiene la procedura del server SOAP.

Ho provato ad utilizzarlolocalmente ma NON sono riuscito a completare il test in quanto mi trovo fuori location pertanto non ho tutti gli strumenti per
testare la richiesta SOAP.


