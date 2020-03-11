<?php

require 'db.php';
require_once('lognewupload.php'); // Log ip og tidspunkt som folk prøver å laste opp for å kunne finne feilmeldinger eller om annet forsøk må følgesopp.


$goforsql = false;

// function to upload the files to a folder;
$folder = "./upload/";


try {
  $file = $_POST['file'];
  $name = $_POST['name'];
} catch (Exception $e){
  echo 'feil skjedd ved innput... ' . $e;
}
$target_file = $folder . basename($_FILES[$file][$name]);

$erMulig = 1; //settes til 0 hvis kondisjonene ikke samsvarer.

$bildeFilType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

if ( isset($_POST["submit"])){
  $sjekker = getimagesize($_FILES["fileToUpload"]["tmp_name"]); // sjekker om filen har en størrelse
  if($sjekker !== false){
    echo 'fil er et bilde';
    if (file_exists($target_file)){  //Sjekker om filen eksisterer
      $erMulig = 0;
      echo ' Fil eksisterer allerede, Prøv et nytt navn';
    } else {
      if($_FILES["fileToUpload"]["size"] > 400000){ //er filen over 400kb?
        echo ' Filen er for stor ...';
        $erMulig = 0;
      } else {
        //Tillat kun png filer.
        if($bildeFilType != "png"){ //> -- ENDRE HER OM ANDRE FILTYPER SKAL VÆRE MULIG!
          echo ' Kun mulig å laste opp png. filer med Alpha kanaler / Gjennomsiktighet ';
          $erMulig = 0;
        } else {
          $erMulig = 1;
          echo ' Alt er ok så langt ';
          echo ' Sender inn filen til lagring ';
        }
      }
    }
  } else {
    $erMulig = 0;
    echo 'fil er ikke et bilde';
  }
}

if ( $erMulig === 1 ){

  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
    echo basename( $_FILES["fileToUpload"]["name"]) . " has been uploaded";
    $goforsql = true;
  } else {
    "checked alright. but still failed to upload!";
  }


} else {
  echo ' Failed to upload... ';
}

/*
 * Så hær har vi nå fått lastet opp filen. vi har fått en ny variabel som
 */

if ( $goforsql ){
  $teller = 0;
  try {

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM tags");
    $stmt->execute();
    while ($row = $stmt->fetch())
    {
      $teller++; // må sjekke om post tag er lik row-name så kjører vi sql insert tags.
      if ( isset ($_POST[$row['Name']])){



      }


    }
  }

  catch(PDOException $e)
  {
    echo "Connection failed: " . $e->getMessage();
  }

  $conn = null; //close connection


}












?>