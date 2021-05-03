<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="resources/style.css" type="text/css">
    <meta charset="utf-8">
  </head>
  <body>

    <!-- Titre de niveau 1 -->
    <h1> Suppression d'un thème </h1>
    <hr>

    <!-- Paragraphe explicatif -->
    <p class="texte">
      Ce formulaire vous permet de supprimer des thèmes de code.<br>
      Pour cela, il suffit de cocher un thème parmi la liste ci-dessous.<br><br>
      Il est possible de réactiver un ancien thème : il suffit de créer un thème avec EXACTEMENT
      le même nom que l'ancien avec une nouvelle description, et il sera réactivé et mis à jour.<br><br>
    </p>
    <?php

      // Déclaration des variables
      $dbhost = "";
      $dbuser = "";
      $dbpass = "";
      $dbname = "";

      // Connexion à la base de données
      $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');

      // Affichage de la requête permettant de récupérer les informations des élèves inscrits à la séance
      $query_themes = "select nom, descriptif, idtheme from themes where supprime = '0'";/*
      echo "<br>Requête SQL : <font color='blue'>$query_themes</font><br>";
      echo "<br>";
      echo "<br>";*/

      // Stockage de la requête dans la variable $result
      $result = mysqli_query($connect, $query_themes);

      if (!$result) {
        echo '<br><p class"erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
      }else {

        // Tableau récapitulatif des thèmes actifs
        echo "<div class='formula'><p class='texte'>Voici les thèmes actifs avec leur description</p>";
        echo "<form method='POST' action='supprimer_theme.php'>";
        echo "<table border='1'>";
        while ($row = mysqli_fetch_array($result, MYSQL_NUM)){// Tant que $row contient une ligne de la table
            echo "<tr>";
            echo "<td>".$row[0]."</td><td>".$row[1]."</td>"."<td> <input type='radio' name='supprimer' value='$row[2]'>";
            echo "</tr>";
          }
        echo "</table>";
        echo "<input type='submit' value='Supprimer le thème'>";
        echo "</form></div>";

      }

      // Déconnexion de la base de données
      mysqli_close($connect);
    ?>
  </body>
</html>
