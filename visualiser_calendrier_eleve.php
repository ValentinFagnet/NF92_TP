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

    <?php

      // Définition de la date en Europe, fuseau horaire de Paris
      date_default_timezone_set('Europe/Paris');

      // Déclaration des variables
      $dbhost = "";
      $dbuser = "";
      $dbpass = "";
      $dbname = "";
      $date = date("Y\-m\-d");
      $traiter = False;

      // Connexion à la base de données
      $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');

      // Sécurité
      if (!empty($_POST["menuChoixEleve"]) && ctype_digit($_POST["menuChoixEleve"])) { // Si la variable $eleve n'est pas vide et est bien un entier
        $eleve = $_POST["menuChoixEleve"];

        // Affichage de la requête permettant de vérifier que l'élève existe
        $query_secu = "select ideleve from eleves where ideleve = $eleve";/*
        echo "<br>Requête SQL : <font color='blue'>$query_secu</font><br>";
        echo "<br>";
        echo "<br>";*/

        // Stockage de la requête dans la variable $result_secu
        $result_secu = mysqli_query($connect, $query_secu);

        // Test qui permet de vérifier si la requête est valide
        if (!$result_secu) { // Si la requête n'est pas valide
          echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
        }else { // Si la requête est valide
          if (mysqli_num_rows($result_secu) == 0) { // Si l'élève n'existe pas
            echo "<p class='erreur'>Cet élève n'existe pas : il faut éviter de modifier le code html :)</p>";
          }else { // Si l'élève existe
            $traiter = True;
        }
       }
      }else { // Si la variable $eleve est vide ou n'est pas un entier
        echo "<p class='erreur'>Il y a un problème avec le choix de l'élève : il faut éviter de modifier le code html :)</p>";
      }

      // Traitement des informations
      if ($traiter) {

        // Affichage de la requête permettant de récupérer les informations des séances auxquelles l'élève est inscrit
        $query_seances = "select dateSeance, nom, note
                          from seances
                          inner join inscription on seances.idseance = inscription.idseance
                          inner join themes on seances.idtheme = themes.idtheme
                          where ideleve = $eleve order by dateSeance asc";/*
        echo "<br>Requête SQL : <font color='blue'>$query_seances</font><br>";
        echo "<br>";
        echo "<br>";*/

        // Stockage de la requête dans la variable $result_seances
        $result_seances = mysqli_query($connect, $query_seances);

        // Test qui permet de vérifier si la requête est valide
        if (!$result_seances) { // Si la requête n'est pas valide
          echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
        }else { // Si la requête est valide
          echo "<p class='texte'>Séances à venir de l'élève</p>";
          echo "<table border=2>";
          echo "<tr>";
          echo "<th> <b> Date de la séance </b> </th>";
          echo "<th> <b> Thème de la séance </b> </th>";
          echo "</tr>";
          while ($row = mysqli_fetch_array($result_seances, MYSQL_NUM)){
            echo "<tr>";
              if (new DateTime($row[0]) > new DateTime($date)) { // Si la séance se situe dans le futur
                echo "<td>$row[0]</td>";
                echo "<td>$row[1]</td>";
              }
            echo "</tr>";
          }
          echo "</table>";
          echo "</center>";
        }
      }

      // Déconnexion de la base de données
      mysqli_close($connect);
    ?>

    <br>
    <br>
    <br>

    <!-- Ajout d'un lien pour retourner au formulaire -->
    <a href="visualisation_calendrier_eleve.php" target="_self"> Retour au formulaire </a>
  </body>
</html>
