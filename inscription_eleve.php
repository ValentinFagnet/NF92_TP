<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="resources/style.css" type="text/css">
    <meta charset="utf-8">
  </head>
  <body>

    <!-- Titre de niveau 1 -->
    <h1> Inscription à une séance </h1>
    <hr>

    <!-- Paragraphe explicatif -->
    <p class="texte">
      Ce formulaire vous permet d'inscrire un élève à une séance de code.<br>
      Pour cela, il suffit de sélectionner une séance de code et un élève avec les deux menus déroulant ci-dessous.
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

      // Affichage de la requête permettant de récupérer les informations de chaque élève
      $query = "select ideleve, nom, prenom from eleves";/*
      echo "<br>Requête SQL : <font color='blue'>$query</font><br>";
      echo "<br>";
      echo "<br>";*/

      // Stockage de la requête dans la variable $result
      $result = mysqli_query($connect, $query);

      // Affichage de la requête permettant de récupérer toutes les séances existantes
      $query2 = "select idseance, dateSeance, nom from seances, themes where seances.idtheme = themes.idtheme order by dateSeance asc";/*
      echo "<br>Requête SQL : <font color='blue'>$query2</font><br>";
      echo "<br>";
      echo "<br>";*/

      // Stockage de la requête dans la variable $result2
      $result2 = mysqli_query($connect, $query2);

      // Test pour vérifier si la requête est valide
      if (!$result) { // Si la requête n'est pas valide
        echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
      }else { // Si la requête est valide

        // Test pour vérifier si la requête est valide
        if (!$result2) { // Si la requête n'est pas valide
          echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
        }else { // Si la requête est valide

          // Formulaire pour choisir un thème parmi ceux actifs
          echo "<div class='formula'><form method='POST' action='inscrire_eleve.php'>";

          // Premier menu déroulant pour choisir l'élève
          echo "Elève à inscrire :";
          echo "<br><select name='menuChoixEleve' size='1'>";
          while ($row = mysqli_fetch_array($result, MYSQL_NUM)){ // Tant que $row contient une ligne de la table
            echo "<option value='$row[0]'>".$row[1]." "."$row[2]"."</option>";
          }
          echo "</select>";
          echo "<br>";

          // Second menu déroulant pour choisir la séance
          echo "Séance :";
          echo "<br><select name='menuChoixSeance' size='1'>";
          while ($row = mysqli_fetch_array($result2, MYSQL_NUM)){ // Tant que $row contient une ligne de la table
            if (new DateTime($row[1]) > new DateTime($date)) { // N'affiche que les séances futures à minimum J-1. Les séances du jour ne sont pas affichées (il suffirait d'ajouter un >=)
              echo "<option value='$row[0]'>"."Séance du ".$row[1]." : "."$row[2]"."</option>";
            }
          }
          echo "</select>";
          echo "<br>";
          echo "<input type='submit' value='Inscrire élève'>";
          echo "</form></div>";
        }
      }

      // Déconnexion de la base de données
      mysqli_close($connect);
    ?>
  </body>
</html>
