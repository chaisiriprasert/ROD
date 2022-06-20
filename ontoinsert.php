<?php
$robot = $_REQUEST['robot'];
$class =  $_REQUEST['class'];
$object = $_REQUEST['object'];
$label =  $_REQUEST['label'];
$description =  $_REQUEST['description'];
$object = str_replace(" ","",$object);
$grasping = 'Grasping'. $object ;

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
$query =
"INSERT DATA {  GRAPH <http://174.138.23.208/owl/ROD#>
 {
   <http://174.138.23.208/owl/ROD#". $object .">
   <http://www.w3.org/1999/02/22-rdf-syntax-ns#type>
   <http://174.138.23.208/owl/SUMO#". $class .">.

   <http://174.138.23.208/owl/ROD#". $object .">
   <http://www.w3.org/1999/02/22-rdf-syntax-ns#type>
   <http://174.138.23.208/owl/ROD#ObjectDescription>.

   <http://174.138.23.208/owl/ROD#". $object .">
   <http://174.138.23.208/owl/ROD#hasLabel>
   '" . $label . "'^^<http://www.w3.org/2001/XMLSchema#string>.

   <http://174.138.23.208/owl/ROD#". $object .">
   <http://174.138.23.208/owl/ROD#hasDescription>
   '" . $description . "'^^<http://www.w3.org/2001/XMLSchema#string>.

   <http://174.138.23.208/owl/ROD#". $object .">
   <http://174.138.23.208/owl/ROD#hasPCD>
   'http://174.138.23.208/pcd/". $label ."/". $object .".pcd'^^<http://www.w3.org/2001/XMLSchema#anyURI>.

   <http://174.138.23.208/owl/ROD#Grasping". $object .">
   <http://www.w3.org/1999/02/22-rdf-syntax-ns#type>
   <http://174.138.23.208/owl/ROD#GraspingStrategy>.

   <http://174.138.23.208/owl/ROD#". $object .">
   <http://174.138.23.208/owl/ROD#hasGraspingStrategy>
   <http://174.138.23.208/owl/ROD#Grasping". $object .">.

   <http://174.138.23.208/owl/ROD#Grasping". $object .">
   <http://174.138.23.208/owl/ROD#hasURL>
   'http://174.138.23.208/grasping/" . $label . '/'. $object . ".model'^^<http://www.w3.org/2001/XMLSchema#anyURI>.

   <http://174.138.23.208/owl/ROD#". $robot .">
   <http://174.138.23.208/owl/ROD#hasLearnt>
   <http://174.138.23.208/owl/ROD#". $object .">.
 }
}";
dbpediaSPARQL($query);
header( "refresh:5;url=http://174.138.23.208/semantic.php" );
?>
