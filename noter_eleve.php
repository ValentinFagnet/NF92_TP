<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="resources/style.css" type="text/css">
    <meta charset="utf-8">
  </head>
  <body>

    <!-- Titre de niveau 1 -->
    <h1> Noter une séance </h1>
    <hr>

    <?php

      // Déclaration des variables
      $dbhost = "";
      $dbuser = "";
      $dbpass = "";
      $dbname = "";
      $traiter = False;
      $seance = $_POST["idseance"];

      // Connexion à la base de données
      $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');

      // Test de sécurité
      if (isset($_POST["idseance"]) && ctype_digit($_POST["idseance"])) { // Si la séance a bien été choisie et si c'est bien un entier
        $seance = $_POST["idseance"];
        $traiter = True;
      }else { // S'il y a un problème avec la transmission de l'id de la s"ance
        echo "<p class='erreur'> Erreur : il y a un problème avec la séance sélectionnée. Il faut éviter de modifier le code html :) </p>";
      }

      // Traitement des informations
      if ($traiter) {

        // Affichage de la requête permettant de récupérer les informations des élèves inscrits à la séance
        $query = "select inscription.ideleve, nom, prenom, note from inscription, eleves where inscription.idseance='$seance' and inscription.ideleve = eleves.ideleve";/*
        echo "<br>Requête SQL : <font color='blue'>$query</font><br>";
        echo "<br>";
        echo "<br>";*/

        // Stockage de la requête dans la variable $result
        $result = mysqli_query($connect,$query);

        // Test qui permet de vérifier que la requête est valide
        if (!$result) { // Si la requête n'est pas valide
          echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
        }else { // Si la requête est valide
          while ($row = mysqli_fetch_array($result)) { // Tant que $row est une ligne

            // Sécurité
            if ((!empty($_POST["$row[0]"]) || $_POST["$row[0]"] == '0') && $_POST["$row[0]"] >= 0 && $_POST["$row[0]"] <= 40 ) { // Si le nombre de fautes a bien été entré et est compris entre 0 et 40
              echo "<br>";
              $note = 40-$_POST["$row[0]"]; // Permet d'obtenir la note de l'élève à partir du nombre de fautes

              // Affichage de la requête permettant de mettre à jour la note de l'élève
              $query_update = "update inscription set note = '$note' where inscription.ideleve='$row[0]' and inscription.idseance='$seance'";/*
              echo "<br>Requête SQL : <font color='blue'>$query_update</font><br>";
              echo "<br>";
              echo "<br>";*/

              // Stockage de la requête dans la variable $result_update
              $result_update = mysqli_query($connect, $query_update);

              // Test qui permet de vérifier que la requête est valide
              if (!$result_update) { // Si la requête n'est pas valide
                echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect);
                echo "<br>L'élève n'a pas été noté.</p>";
              }else { // Si la requête est valide
                echo "<p class='succes'>La note a été mise à jour pour l'élève $row[1] $row[2].";
                echo "<br></p>";
              }
            }else { // Si la note de l'élève n'a pas été entrée
              echo "<p class='texte'>Aucune note n'a été entrée pour l'élève $row[1] $row[2]. Vous pourrez renoter cet élève plus tard.";
              echo "<br></p>";
            }
          }
        }
      }

      // Déconnexion de la base de données
      mysqli_close($connect);
    ?>
    <br>
    <br>
    <br>

    <!-- Ajout d'un lien pour retourner au formulaire -->
    <a href="validation_seance.php" target="_self"> Retour à la sélection d'une séance </a>
  </body>
</html>
