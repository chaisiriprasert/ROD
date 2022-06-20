<?php
$object = $_REQUEST['object'];


function dbpediaSPARQL($q)
{


  $searchUrl = 'http://174.138.23.208:8890/sparql?'. 'query='.urlencode($q). '&format=json';

  if (!function_exists('curl_init'))  {      die('CURL is not installed!');      }
  $ch= curl_init();
  curl_setopt($ch, CURLOPT_URL, $searchUrl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  curl_close($ch);
  print ($response);

}

$query = "DELETE WHERE
          {  GRAPH <http://174.138.23.208/owl/ROD#>
             { <http://174.138.23.208/owl/ROD#". $object ."> ?a ?b  }
          }";
dbpediaSPARQL($query);

$query = "DELETE WHERE
          {  GRAPH <http://174.138.23.208/owl/ROD#>
             { <http://174.138.23.208/owl/ROD#Grasping". $object ."> ?a ?b  }
          }";
dbpediaSPARQL($query);

$query = "DELETE WHERE
          {  GRAPH <http://174.138.23.208/owl/ROD#>
             {  ?a ?b <http://174.138.23.208/owl/ROD#". $object .">  }
          }";
dbpediaSPARQL($query);




?>
