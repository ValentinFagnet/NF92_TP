<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="resources/style.css" type="text/css">
    <meta charset="utf-8">
  </head>
  <body>

    <!-- Titre de niveau 1 -->
    <h1> Suppression d'une séance </h1>
    <hr>

    <!-- Paragraphe explicatif -->
    <p class="texte">
      Ce formulaire vous permet de supprimer une séance qui n'a pas encore eu lieu et qui ne contient aucun élève.<br>
      Pour cela, il suffit de choisir, dans le menu déroulant ci-dessous, une séance parmi celles qui n'ont pas encore eu lieu.
    </p>

    <?php

      // Définition de la date en Europe, fuseau horaire de Paris
      date_default_timezone_set('Europe/Paris');

// Déclaration des variables
      $dbhost = "";
      $dbuser = "";
      $dbpass = "";
      $dbname = "";
      $date = date("Y\-m\-d");

      // Connexion à la base de données
      $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');

      // Affichage de la requête permettant de récupérer toutes les séances
      $query = "select idseance, DateSeance, nom from seances inner join themes on seances.idtheme = themes.idtheme order by DateSeance asc";/*
      echo "<br>Requête SQL : <font color='blue'>$query</font><br>";
      echo "<br>";
      echo "<br>";*/

      // Stockage de la requête dans la variable $result
      $result = mysqli_query($connect, $query);

      // Test pour vérifier si la requête est valide
      if (!$result) { // Si la requête n'est pas valide
        echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
      }else { // Si la requête est valide
        echo "<div class='formula'><form method='POST' action='supprimer_seance.php'>";
        echo "<select name='menuChoixSeance' size = '1'>";
        while ($row = mysqli_fetch_array($result, MYSQL_NUM)){
          if (new DateTime($row[1]) > new DateTime($date)) { // Si la séance se situe dans le futur
            echo "<option value='$row[0]'> Séance du $row[1] : $row[2] </option>";
          }
        }
        echo "</select>";
        echo "<br>";
        echo "<input type='submit' value='Supprimer cette séance'>";
        echo "</form></div>";
      }

      // Déconnexion de la base de données
      mysqli_close($connect);
    ?>

  </body>
</html>
