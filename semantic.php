
<script>
function preview_urdf()
{
 //var location = "http://132.145.136.98/urdf-loaders/urdf.php?urdf=http://132.145.136.98/urdf-loaders/urdf/";
var location = "http://174.138.23.208/urdf-loaders/urdf.php?urdf=http://174.138.23.208/urdf/";
 //  var location = "http://vr.dodeep.co/urdfloaders/urdf.php?urdf=http://vr.dodeep.co/urdfloaders/urdf/";
window.open(location+document.getElementById('urdf').value+".urdf","_blank","resizable=yes,top=400,left=400,width=400,height=400");
}
</script>
<h1>Robotic Object Description (ROD) Ontology </h1>
<form action="semantic.php">
  <h3>Semantic analysis for a common understanding</h3>
  This tool relates English terms to concepts from the SUMO ontology by means of mappings to WordNet synsets. <br><br>
  Word of Object :
  <?php
  if( !empty($_GET['key']))
  {
    echo "<input type='text' name='key' value='". $_GET['key'] . "''> ";
  }
   else
  {
     echo "<input type='text' name='key'> ";
  }
  ?>
<input type="submit" value="Submit">
</form>


<form action="/ontoinsert.php">
<?php

$word = str_replace(" ","+",$_GET['key']);
if( !empty($_GET['key']))
{
		$opts = array('http'=>array('method'=>"GET",'header'=>"Accept-language: en\r\n"."Cookie: foo=bar\r\n"));
		$context = stream_context_create($opts);
		$file = file_get_contents("http://sigma.ontologyportal.org:8080/sigma/WordNet.jsp?word=".$word."&POS=1", false, $context);

    $mapping = strpos($file,"There are no associated");
    $sense  = substr($file,strpos($file,"sense(s)")-2,1);

    if ( $mapping != 0 )
    {
     echo "There are no associated SUMO. <br><br>";
     echo "<label>Learnt Class  :</label>  <input type='text' name='object' value='' style='width: 200px' >";
     echo "<label> Label :</label>  <input type='text' name='label' value='' ><br><br>";
    }
    else
    {
     echo "There are associated SUMO. <br><br>";
      //      echo "<br> Mapping mumber = " . $sense  ."<br>"  ;
      $needle = 'SUMO Mappings:';       $positions1 = [];       $pos_last = 0;
      while( ($pos_last = strpos($file, $needle, $pos_last)) !== false ) {
          $positions1[] = $pos_last;
          $pos_last = $pos_last + strlen($needle);
      }
      $needle = 'mapping)';       $positions2 = [];       $pos_last = 0;
      while( ($pos_last = strpos($file, $needle, $pos_last)) !== false ) {
          $positions2[] = $pos_last;
          $pos_last = $pos_last + strlen($needle);
      }
      for( $i = 0 ; $i < $sense ; $i++  )
      {
            $positions = $positions2[$i] - $positions1[$i];
            $sumo = substr($file,$positions1[$i]+14,$positions);
            $sumo = substr($sumo, strpos($sumo,'>')+1,strpos($sumo,'</')-strpos($sumo,'>')-1);
      }
      if ( $sense == 1 )
      {
      $class = $sumo  ;
      echo "<label>Learnt Class  :</label>  <input type='text' name='class' value='$class' style='width: 200px' >";
     }
     else
     {
      echo "<label>Learnt Class  :</label> <select name='class' style='width: 200px'>";
      for( $i = 0 ; $i < $sense ; $i++  )
      {
            $positions = $positions2[$i] - $positions1[$i];
            $sumo = substr($file,$positions1[$i]+14,$positions);
            $sumo = substr($sumo, strpos($sumo,'>')+1,strpos($sumo,'</')-strpos($sumo,'>')-1);
            echo "<option value='$sumo' > $sumo </option>";
      }
      echo "</select>";
     }
      $word = str_replace("+","_",$word);
      echo "<label> Label :</label>  <input type='text' name='label' value='$word' ><br><br>";
    }
}
?>

<hr>
<h3>Identify Object</h3>
<label for="lname"> Object Name :</label>  <input type="text" id="lname" name="object"   style="width: 195px">
<label for="lname">Description :</label>  <input type="text" id="lname" name="description"  style="width: 400px"><br><br>

<hr>
<h3>Identify Robot</h3>
<label for="fname">Trained  Robot : </label>
<select id="urdf" name="robot" style="width: 200px">
  <option value="RobotArm">RobotArm</option>
  <option value="Pr2">Robot-Pr2</option>
</select>
<label onclick="preview_urdf()"> [ Preview URDF ] </label>
<br><br>
<input type="submit" value="Submit">
</form>
