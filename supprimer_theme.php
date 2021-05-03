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

    <?php

      // Déclaration des variables
      $dbhost = "";
      $dbuser = "";
      $dbpass = "";
      $dbname = "";
      $traiter = False;

      // Connexion à la base de données
      $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');

      // Sécurité
      if (!empty($_POST["supprimer"])) { // Si un thème a été sélectionné
        $idtheme = $_POST["supprimer"];

        // Affichage de la requête permettant de vérifier si le thème existe
        $query_secu = "select idtheme, supprime from themes where idtheme = '$idtheme'";/*
        echo "<br>Requête SQL : <font color='blue'>$query_secu</font><br>";
        echo "<br>";
        echo "<br>";*/

        $result_secu = mysqli_query($connect, $query_secu);

        // Test qui permet de vérifier si la requête est valide
        if (!$result_secu) { // Si la requête n'est pas valide
          echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
        }else { // Si la requête est valide
          if (mysqli_num_rows($result_secu) == 0 || mysqli_fetch_array($result_secu, MYSQL_NUM)[1] == '1') { // Si le thème n'existe pas ou est déjà supprimé
            echo "<p class='erreur'>Impossible de supprimer le thème (soit il n'existe pas, soit il est déjà supprimé). Il faut éviter de modifier le code html :)</p>";
          }else { // Si le thème existe
            $traiter = True;
          }
        }
      }else { // Si aucune thème n'a été sélectionné
        echo "<p class='erreur'>Vous n'avez pas sélectionné de thème. <br>Veuillez recommencer la saisie.</p>";
      }

        // Traitement des informations
        if ($traiter) {

          // Affichage de la requête permettant de récupérer les informations des élèves inscrits à la séance
          $query_supprimer = "update themes set supprime = '1' where themes.idtheme = '$idtheme'";/*
          echo "<br>Requête SQL : <font color='blue'>$query_supprimer</font><br>";
          echo "<br>";
          echo "<br>";*/

          // Stockage de la requête dans la variable $result
          $result = mysqli_query($connect, $query_supprimer);

          // Test qui permet de vérifier que la requête est valide
          if (!$result){ // Si la requête n'est pas valide
            echo '<br><p class="texte">Erreur dans la requête : '.mysqli_error($connect);
            echo "<br>Le thème n'a pas pu être supprimé.</p>";
          }else { // Si la requête est valide
            echo "<p class='succes'>Le thème a bien été supprimé.</p>";
          }
        }

      // Déconnexion de la base de données
      mysqli_close($connect);
    ?>
    <br>
    <br>
    <br>

    <!-- Ajout d'un lien pour retourner au formulaire -->
    <a href="suppression_theme.php" target="_self"> Retour à la sélection d'un thème </a>
  </body>
</html>
