<?php
//phpinfo();

function dbpediaSPARQL()
{
  $query = "SELECT * {?s ?p ?o.} LIMIT 100";
  $searchUrl = 'http://174.138.23.208:8890/sparql?'. 'query='.urlencode($query). '&format=json';

  if (!function_exists('curl_init'))
  {
      die('CURL is not installed!');
  }
  $ch= curl_init();
  curl_setopt($ch, CURLOPT_URL, $searchUrl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  curl_close($ch);
  print ($response);

}


dbpediaSPARQL();

?>
