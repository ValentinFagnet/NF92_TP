<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="resources/style.css" type="text/css">
    <meta charset="utf-8">
  </head>
  <body>

    <!-- Titre de niveau 1 -->
    <h1> Visualisation du calendrier d'un élève </h1>
    <hr>

    <!-- Paragraphe explicatif -->
    <p class="texte">
      Ce formulaire vous permet de consulter le calendrier des futures séances de code pour un élève.<br>
      Pour consulter le calendrier d'un élève, il suffit de sélectionner un élève dans le menu déroulant ci-dessous.<br><br>
    </p>

    <?php

      // Déclaration des variables
      $dbhost = "";
      $dbuser = "";
      $dbpass = "";
      $dbname = "";
      
      // Connexion à la base de données
      $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');

      // Affichage de la requête permettant de récupérer les informations de chaque élève
      $query = "select ideleve, nom, prenom from eleves";/*
      echo "<br>Requête SQL : <font color='blue'>$query</font><br>";
      echo "<br>";
      echo "<br>";*/

      // Stockage de la requête dans la variable $result
      $result = mysqli_query($connect, $query);

      // Test qui permet de vérifier que la requête est valide
      if (!$result){ // Si la requête n'est pas valide
        echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
        echo "<br><p class='texte'>Aucun élève n'a pu être sélectionné.</p>";
      }else { // Si la requête est valide

        // Formulaire pour sélectionner l'élève à consulter
        echo "<div class='formula'><form method='POST' action='visualiser_calendrier_eleve.php'>";

        // Menu déroulant
        echo "Consulter le calendrier de l'élève :<br>";
        echo "<select name='menuChoixEleve' size='1' required>";
        while ($row = mysqli_fetch_array($result, MYSQL_NUM)) { // Tant que $row est une ligne
          echo "<option value='$row[0]'> $row[1] $row[2] </option>";
        }
        echo "</select>";
        echo "<br>";
        echo "<input type='submit' value='Visualiser le calendrier élève'>";
        echo "</form></div>";
      }

      // Déconnexion de la base de données
      mysqli_close($connect);
    ?>
  </body>
</html>
