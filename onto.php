<!DOCTYPE html>

<script>
function preview_urdf()
{
 //var location = "http://132.145.136.98/urdf-loaders/urdf.php?urdf=http://132.145.136.98/urdf-loaders/urdf/";
var location = "http://174.138.23.208/urdf-loaders/urdf.php?urdf=http://174.138.23.208/urdf/";
 //  var location = "http://vr.dodeep.co/urdfloaders/urdf.php?urdf=http://vr.dodeep.co/urdfloaders/urdf/";
window.open(location+document.getElementById('urdf').value+".urdf","_blank","resizable=yes,top=400,left=400,width=400,height=400");
}
</script>

<html>
<body>

<h1>Robotic Object Description (ROD) Ontology </h1>


<form action="/ontoinsert.php">
  <label for="fname">Trained  Robot : </label>
  <select id="urdf" name="robot" style="width: 200px">
    <option value="RobotArm">RobotArm</option>
    <option value="Pr2">Robot-Pr2</option>
  </select>
  <label onclick="preview_urdf()"> [ Preview URDF ] </label>
  <br><br>

  <h3>A common understanding</h3>
  <label for="lname"> Word </label> <input type="text" name="word" style="width: 200px"> Semantic analysis <br>
  This tool relates English terms to concepts from the SUMO ontology by means of mappings to WordNet synsets.<br><br><br>

  <label for="lname">Learnt Class  :</label>  <input type="text" id="lname" name="object" value='AmazonCup' style="width: 200px">
  <label for="lname">Label :</label>  <input type="text" id="lname" name="label" value='Coffee Mug'><br><br>

  <h3>Identify Object</h3>
  <label for="lname"> Object Name :</label>  <input type="text" id="lname" name="class" value='DrinkingCup'  style="width: 195px">
  <label for="lname">Description :</label>  <input type="text" id="lname" name="description" value='Amazon Cup 40 oz.' style="width: 250px"><br><br>

  <input type="submit" value="Submit">
</form>

<p> ROD ontology to represent the world of objects known by the collective and use the WordNet to provide a common understanding of objects across various robotic applications.</p>

</body>
</html>
