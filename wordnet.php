
<br><br><br><br><br>
<form action="wordnet.php">
  <b>Label of Object :</b>
  <?php
  if( !empty($_GET['key']))   {   echo "<input type='text' name='key' value=". $_GET['key'] . ">";   }
  else   {   echo "<input type='text' name='key'>";   } ?>

<input type="submit" value="Submit">
</form>

This tool relates English terms to concepts from the SUMO ontology by means of mappings to WordNet synsets.

<?php

if( !empty($_GET['key']))
{
		$opts = array('http'=>array('method'=>"GET",'header'=>"Accept-language: en\r\n"."Cookie: foo=bar\r\n"));
		$context = stream_context_create($opts);
		// Accès à un fichier HTTP avec les entêtes HTTP indiqués ci-dessus
		$file = file_get_contents('http://wordnetweb.princeton.edu/perl/webwn?s='.$_GET['key'], false, $context);
		$result = $file ;
		$begin = strpos($file,"<h3>Noun</h3>");
    echo "<h3>Meaning of <font color=blue>".$_GET['key'] ,"</font></h3>" ;
		$result = substr($file,$begin) ;
		$result = str_replace("href","",$result);
		echo $result ;
}



//  http://sigma.ontologyportal.org:8080/sigma/WordNet.jsp?word=bottle&POS=1

?>
