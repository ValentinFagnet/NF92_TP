<!DOCTYPE html>
<html lang="for" dir="ltr">
  <head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="resources/style.css" type="text/css">
    <meta charset="utf-8">
  </head>
  <body>

    <!-- Titre de niveau 1 -->
    <h1> Consultation d'un élève </h1>
    <hr>

    <?php

      // Définition de la date en Europe, fuseau horaire de Paris
      date_default_timezone_set('Europe/Paris');

      // Déclaration des variables
      $dbhost = "";
      $dbuser = "";
      $dbpass = "";
      $dbname = "";
      $traiter = False;
      $date = date("Y\-m\-d");

      // Connexion à la base de données
      $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');

      // Sécurité
      if (!empty($_POST["menuChoixEleve"]) && ctype_digit($_POST["menuChoixEleve"])){ // Si l'élève est sélectionné et est un entier

        $eleve = $_POST["menuChoixEleve"];

        // Affichage de la requête permettant de détecter un élève existant
        $query_secu = "select ideleve from eleves where ideleve = '$eleve'";/*
        echo "<br>Requête SQL : <font color='blue'>$query_secu</font><br>";
        echo "<br>";
        echo "<br>";*/

        // Stockage de la requête dans la variable $result_secu
        $result_secu = mysqli_query($connect, $query_secu);

        // Test pour vérifier si la requête est valide
        if (!$result_secu) { // Si la requête n'est pas valide
          echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
        }else { // Si la requête est valide
          if (mysqli_num_rows($result_secu) == 0) { // Si l'élève n'existe pas
            echo "<p class='erreur'>Cet élève n'existe pas : il faut éviter de modifier le code html :)</p>";
          }else {
            $traiter = True; // On autorise le traitement des informations

            /*// Débogage
            echo "<hr>";
            echo "<center> Début du débogage </center>";
            echo "<br>";
            echo "eleve = $eleve";
            echo "<br>";
            echo "<center> Fin du débogage </center>";
            echo "<hr>";*/
          }
        }
      }else {
        echo "<p class='erreur'>Il y a un problème avec la sélection de l'élève. <br>Veuillez recommencer la saisie.</p>";
      }

      // Traitement des informations
      if ($traiter) { // Si les informations sont correctes

        // Affichage de la requête permettant de récupérer les informations de l'élève sélectionné
        $query_caract = "select nom, prenom, dateNaiss, dateInscription from eleves where eleves.ideleve = $eleve";/*
        echo "<br>Requête SQL : <font color='blue'>$query_caract</font><br>";
        echo "<br>";
        echo "<br>";*/

        // Stockage de la requête dans la variable $result
        $result_caract = mysqli_query($connect, $query_caract);

        // Affichage de la requête permettant de récupérer les informations des séances auxquelles l'élève est inscrit
        $query_seances = "select dateSeance, nom, note from seances
                          inner join inscription on seances.idseance = inscription.idseance
                          inner join themes on seances.idtheme = themes.idtheme
                          where ideleve = $eleve order by dateSeance asc";/*
        echo "<br>Requête SQL : <font color='blue'>$query_seances</font><br>";
        echo "<br>";
        echo "<br>";*/

        // Stockage de la requête dans la variable $result
        $result_seances = mysqli_query($connect, $query_seances);

        $row = mysqli_fetch_array($result_caract, MYSQL_NUM);

        echo "<p class='texte'>";

        echo "Identité de l'élève";
        echo "<table border=2>";
        echo "<tr><th> Nom </th>";
        echo "<th> Prénom </th>";
        echo "<th> Date de naissance </th>";
        echo "<th> Date d'inscription </th></tr>";
        echo "<tr><td>$row[0]</td>";
        echo "<td>$row[1]</td>";
        echo "<td>$row[2]</td>";
        echo "<td>$row[3]</td></tr>";
        echo "</table></p>";

        echo "<br>";

        echo "<p class='texte'> ";

        echo "Séances de l'élève";
        echo "<table border=2>";
        echo "<tr>";
        echo "<th> Date de la séance </th>";
        echo "<th> Thème de la séance </th>";
        echo "<th> Note </th>";
        echo "</tr>";
        while ($row = mysqli_fetch_array($result_seances, MYSQL_NUM)){ // Tant que $row est une ligne
          echo "<tr>";
          if (new DateTime($row[0]) <= new DateTime($date)) { // Si la séance s'est déjà déroulée
            echo "<td>$row[0]</td>";
            echo "<td>$row[1]</td>";
            if ($row[2] == '-1') { // Si l'élève n'est pas noté
              echo "<td> Non notée </td>";
            }else{ // Si l'élève est noté
              echo "<td>$row[2]/40</td>";
            }
          }
          echo "</tr>";
        }
        echo "</table> </p>";
      }

      // Déconnexion de la base de données
      mysqli_close($connect);
    ?>
    <br>
    <br>
    <br>

    <!-- Ajout d'un lien pour retourner au formulaire -->
    <a href="consultation_eleve.php" target="_self"> Retour au formulaire </a>
  </body>
</html>
