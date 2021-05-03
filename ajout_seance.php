<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="resources/style.css" type="text/css">
    <meta charset="utf-8">
  </head>
  <body>

    <!-- Titre de niveau 1 -->
    <h1> Ajout d'une séance </h1>
    <hr>

    <!-- Paragraphe explicatif -->
    <p class="texte">
      Ce formulaire vous permet d'ajouter une séance de code pour les élèves.<br>
      Pour cela, il suffit de sélectionner un thème parmi les thèmes actifs, un effectif maximal (entre 1 et 20 élèves) et une date (Minimum J+1).
    </p>

    <?php

      // Déclaration des variables
      $dbhost = '';
      $dbuser = '';
      $dbpass = '';
      $dbname = '';

      // Connexion à la base de données
      $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');


      // Affichage de la requête permettant de récupérer les thèmes
      $query = "select * from themes";/*
      echo "<br>Requête SQL : <font color='blue'>$query</font><br>";
      echo "<br>";
      echo "<br>";*/


      // Stockage de la requête dans les variables $result et $result2
      $result = mysqli_query($connect,"$query");
      $result2 = mysqli_query($connect,"$query");

      // Test pour vérifier si les requêtes sont valides
      if (!$result) { // Si la requête n'est pas valide
        echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
      }else { // Si la requête est valide
        if (!$result2) { // Si la requête n'est pas valide
          echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
        }else { // Si la requête est valide

          // Tableau récapitulatif des thèmes actifs
          echo "<p class='texte'>Voici les thèmes actifs avec leur description</p>";
          echo "<table border='1'>";
          echo "<tr>";
          echo "<th> Thème </th>";
          echo "<th> Description </th>";
          echo "</tr>";
          while ($row = mysqli_fetch_array($result, MYSQL_NUM)){// Tant que $row contient une ligne de la table
            if (!$row[2]) { // Si la colonne "supprime" vaut 0 (False)
              echo "<tr>";
              echo "<td>".$row[1]."</td><td>".$row[3]."</td>";
              echo "</tr>";
            }
          }
          echo "</table>";


          // Formulaire pour choisir un thème parmi ceux actifs
          echo "<div class='formula'><form method='POST' action='ajouter_seance.php'>";
          echo "Thème de la séance :<br><select name='menuChoixTheme' size='4' required>";
          while ($row = mysqli_fetch_array($result2, MYSQL_NUM)){ // Tant que $row contient une ligne de la table
            if (!$row[2]) { // Si la colonne "supprime" vaut 0 (False)
              echo "<option value='$row[0]'>".$row[1]."</option>";
            }
          }
          echo "</select>";
          echo "<br>";
          echo "Effectif maximal :<br><input type='number' name='effMax' min='1' max='20' required>"; // On impose un effectif minimal de 1 élève et maximal de 20 élèves. (En théorie pas besoin de limite max mais pour rester réaliste on impose 20 élèves maximum)
          echo "<br>";
          echo "Date de la séance de code :<br><input type='date' name='dateSeance' required>";
          echo "<br>";
          echo "<input type='submit' value='Enregistrer séance'>";
          echo "</form></div>";
        }
      }

      // Déconnexion de la base de données
      mysqli_close($connect);
    ?>
  </body>
</html>
