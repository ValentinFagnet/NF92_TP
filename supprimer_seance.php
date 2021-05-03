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

      if (!empty($_POST["menuChoixSeance"]) && ctype_digit($_POST["menuChoixSeance"])) {
        $seance = $_POST["menuChoixSeance"];

        // Affichage de la requête permettant de récupérer toutes les séances
        $query_secu = "select idseance, dateSeance from seances where idseance = $seance";/*
        echo "<br>Requête SQL : <font color='blue'>$query_secu</font><br>";
        echo "<br>";
        echo "<br>";*/

        // Stockage de la requête dans la variable $result_inscrits
        $result_secu = mysqli_query($connect, $query_secu);

        // Test pour vérifier si la requête est valide
        if (!$result_secu) { // Si la requête n'est pas valide
          echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect);
        }else { // Si la requête est valide
          if (mysqli_num_rows($result_secu) == 0) { // Si la séance n'existe pas
            echo '<br><p class="erreur"> Erreur : cette séance n\'existe pas. Il faut éviter de modifier le code html :)</p>';
          }elseif (new DateTime(mysqli_fetch_array($result_secu, MYSQL_NUM)[1]) <= new DateTime($date)) {
            echo '<br><p class="erreur"> Erreur : cette séance déjà eu lieu. Il faut éviter de modifier le code html :)</p>';
          }else { // Si la séance existe
            $traiter = True;
          }
        }
      }

      if ($traiter) {
        // Affichage de la requête permettant de récupérer toutes les séances
        $query_inscrits = "select ideleve from inscription where idseance = $seance";/*
        echo "<br>Requête SQL : <font color='blue'>$query_inscrits</font><br>";
        echo "<br>";
        echo "<br>";*/

        // Stockage de la requête dans la variable $result_inscrits
        $result_inscrits = mysqli_query($connect, $query_inscrits);

        // Test pour vérifier si la requête est valide
        if (!$result_inscrits) { // Si la requête n'est pas valide
          echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect);
          echo "La séance n'a pas pu être supprimée.</p>";
        }else { // Si la requête est valide

          if (mysqli_num_rows($result_inscrits) == 0) { // Si aucun élève n'est inscrit à la séance

            // Affichage de la requête permettant de récupérer toutes les séances
            $query_supprimer = "delete from seances where idseance = '$seance'";/*
            echo "<br>Requête SQL : <font color='blue'>$query_supprimer</font><br>";
            echo "<br>";
            echo "<br>";*/

            // Stockage de la requête dans la variable $result_supprimer
            $result_supprimer = mysqli_query($connect, $query_supprimer);

            // Test pour vérifier si la requête est valide
            if (!$result_supprimer) { // Si la requête n'est pas valide
              echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect);
              echo "<br>La séance n'a pas pu être supprimée.</p>";
            }else { // Si la requête est valide
              echo "<p class='succes'>La séance a bien été supprimée</p>";
            }
          }else { // S'il y a au moins un élève inscrit à la séance
            echo "<p class='erreur'>Il y a toujours des élèves inscrits à cette séance. <br>Veuillez les désinscrire avant de supprimer cette séance.</p>";
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
    <a href="suppression_seance.php" target="_self"> Retour au formulaire </a>
  </body>
</html>
