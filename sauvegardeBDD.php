<?php

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$distance = $_POST['distance'];
$vitesse = $_POST['vitesse'];
$temps = $_POST['tempsM'];
$imageD = $_POST['imageD'];
$imageA = $_POST['imageA'];


$servername = "172.16.133.2";
$username = "radar";
$password = "radar";
$db = "projetradar";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

if($conn->connect_error)
{
    die('Erreur : ' .$conn->connect_error);
}
//echo 'Connexion réussie';

// verification si l'eleve existe et insertion des donnees pour cette eleve
$eleve = "SELECT idEleve FROM eleve WHERE nom = '$nom' AND prenom = '$prenom'";
$result = $conn->query($eleve);
if ($result->num_rows > 0) 
{
  $row = $result->fetch_assoc();
  //echo "id: " . $row["idEleve"]. "<br>";
  $insertCourse = "INSERT INTO participation (idParticipation, idEleve, vitesse, temps, distance) VALUES (NULL, '$row[idEleve]', '$vitesse', '$temps', '$distance')";

  if ($conn->query($insertCourse) === TRUE)
  {
    //echo "Insertion participation "; //test Insertion participation
  } 
  else 
  {
    //echo "Error: " . $insertCourse . "<br>" . $conn->error; //test Insertion participation
  }
  
  $participation = "SELECT idParticipation FROM participation";
  $result = $conn->query($participation);
  if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $idparticipation = $row["idParticipation"];
    }
  }

  //echo "id: " . $idparticipation. "<br>";
  $insertPhoto = "INSERT INTO photo (idPhoto, idPerformance, depart, arrive) VALUES (NULL, '$idparticipation', '$imageD', '$imageA')";
  if ($conn->query($insertPhoto) === TRUE) 
  {
    //echo "Insertion photos "; //test insertion photos
  } 
  else 
  {
    //echo "Error: " . $insertPhoto . "<br>" . $conn->error; //test insertion photos
  }
}

// creation nouvelle eleve et insertion des donners
else 
{
  $insertEleve = "INSERT INTO eleve (idEleve, nom, prenom) VALUES (NULL, '$nom', '$prenom')";
  if ($conn->query($insertEleve) === TRUE) 
  {
    //echo "Insertion Eleve "; //test Insertion Eleve
  } 
  else 
  {
    //echo "Error: " . $insertEleve . "<br>" . $conn->error; //test Insertion Eleve
  }
  $eleve = "SELECT idEleve FROM eleve WHERE nom = '$nom' AND prenom = '$prenom'";
  $result = $conn->query($eleve);
  $row = $result->fetch_assoc();
  $insertCourse = "INSERT INTO participation (idParticipation, idEleve, vitesse, temps, distance) VALUES (NULL, '$row[idEleve]', '$vitesse', '$temps', '$distance')";
  if ($conn->query($insertCourse) === TRUE)
  {
    //echo "Insertion participation "; //test Insertion participation
  } 
  else 
  {
    //echo "Error: " . $insertCourse . "<br>" . $conn->error; //test Insertion participation
  }

  $participation = "SELECT idParticipation FROM participation";
  $result = $conn->query($participation);
  if ($result->num_rows > 0) 
  {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $idparticipation = $row["idParticipation"];
    }
  }
  $insertPhoto = "INSERT INTO photo (idPhoto, idPerformance, depart, arrive) VALUES (NULL, '$idparticipation', '$imageD', '$imageA')";
  if ($conn->query($insertPhoto) === TRUE) 
  {
    //echo "Insertion photos"; //test insertion photos
  } 
  else 
  {
    //echo "Error: " . $insertPhoto . "<br>" . $conn->error; //test insertion photos
  }

}
$conn->close();
?>

<!--Diplome-->
<!DOCTYPE html>
    <html lang="fr-fr">
    <head>
        <meta charset="UTF-8">
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <title>Diplome</title>
        <link rel="stylesheet" href="./rapport.css" />
        <link rel="stylesheet" type="text/css" href="./print-rapport.css" media="print">
        
        
        <script>
            window.print();
        </script>
    </head>

    </div>
    <body>
        
    <a href="index.php">Accueil</a>
        <br>
        
        <img src='diplomehaut.png' style="width:70%;">
        <h2>Délivré à <?php echo"$nom $prenom" ?></h2>
        <p>Tu as parcouru <?php echo $distance ?> mètre, en <?php echo $temps; ?> seconde </br>
        a une vitesse de <strong><?php echo $vitesse ?> km/h<strong></p>
        <table>
            <tr> <!-- Ligne -->
                <td> <!-- Colonne -->
                    <img src="../uploads/<?php echo $imageD ?>" alt="" style="width:60%;">
                </td>
                <td>
                    <img src="../uploads/<?php echo $imageA ?>" alt="" style="width:60%;">
                </td>
            </tr>
        </table>
        
    </body>
</html> 