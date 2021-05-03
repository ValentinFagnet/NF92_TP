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

    <!-- Paragraphe explicatif -->
    <p class="texte">
      Ce formulaire vous permet de noter les élèves d'une séance de code qui a déjà eu lieue.<br>
      Pour cela, il faut d'abord sélectionner une séance avec le menu déroulant ci-dessous.
      <br>
      <br>
      Les séances du jour même ne peuvent pas être notées, il faut attendre au moins un jour.
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
      $query = "select idseance, DateSeance, nom  from seances, themes where seances.idtheme = themes.idtheme order by DateSeance asc";/*
      echo "<br>Requête SQL : <font color='blue'>$query</font><br>";
      echo "<br>";
      echo "<br>";*/

      // Stockage de la requête dans la variable $result
      $result = mysqli_query($connect, $query);

      // Test qui permet de vérifier que la requête est valide
      if (!$result) { // Si la requête n'est pas valide
        echo '<br><p class"erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
      }else { // Si la requête est valide
        echo "<div class='formula'><form method='POST', action='valider_seance.php'>";
        echo "<select name='validerSeance' size='1'>";
        while ($row = mysqli_fetch_array($result, MYSQL_NUM)) { // Tant que $row est une ligne
          if (new DateTime($row[1]) < new DateTime($date)) { // Si la date de la séance est située dans le passé (donc si la séance a déjà eu lieu)
            echo "<option value='$row[0]'>"."Séance du ".$row[1]." : ".$row[2]."</option>";
          }
        }
        echo "</select>";
        echo "<br>";
        echo "<input type='submit' value='Choisir cette séance'>";
        echo "</form></div>";
      }

      // Déconnexion de la base de données
      mysqli_close($connect);
    ?>
  </body>
</html>
