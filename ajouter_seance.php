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
      if (!empty($_POST["menuChoixTheme"]) && ctype_digit($_POST["menuChoixTheme"])){ // Si le thème est sélectionné et est un entier
        $choixTheme = $_POST["menuChoixTheme"];

        if (!empty($_POST["effMax"]) && $_POST["effMax"] >= 1 && $_POST["effMax"] <= 20 && ctype_digit($_POST["effMax"])){ // Si l'effectif maximal est choisi est un entier et compris entre 1 et 20 élèves
          $effMax = $_POST["effMax"];

          if (!empty($_POST["dateSeance"]) && new DateTime($_POST["dateSeance"]) > new DateTime($date)){ // Si la date de la séance a été saisie et se situe au minimum un jour plus tard
            $ds = $_POST["dateSeance"];

            // Affichage de la requête permettant de détecter un thème existant
            $query_secu = "select idtheme, supprime from themes where idtheme = '$choixTheme'";/*
            echo "<br>Requête SQL : <font color='blue'>$query_secu</font><br>";
            echo "<br>";
            echo "<br>";
            */

            // Stockage de la requête dans la variable $result_secu
            $result_secu = mysqli_query($connect, $query_secu);

            // Test pour vérifier si la requête est valide
            if (!$result_secu) { // Si la requête n'est pas valide
              echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
            }else { // Si la requête est valide
              if (mysqli_num_rows($result_secu) == 0 || mysqli_fetch_array($result_secu)[1] == 1) { // Si le thème n'existe pas ou est désactivé
                echo "<p class='erreur'>Ce thème n'existe pas ou est désactivé : il faut éviter de modifier le code html :)</p>";
              }else {
                $traiter = True; // On autorise le traitement des informations

                /*
                // Débogage
                echo "<hr>";
                echo "<center> Début du débogage </center>";
                echo "<br>";
                echo "choixTheme = $choixTheme";
                echo "<br>";
                echo "effMax = $effMax";
                echo "<br>";
                echo "ds = $ds";
                echo "<br>";
                echo "date = $date";
                echo "<br>";
                echo "<center> Fin du débogage </center>";
                echo "<hr>";
                */
              }
            }
          }else{ // Si la date de la séance n'a été saisie ou si la date se situe dans le passé
            echo "<p class='erreur'>Erreur : Il y a un problème avec la date de la séance ou la date de la séance se situe dans le passé. Une séance ne peut être ajoutée au minimum J+1.<br>Veuillez recommencer la saisie.</p>";
          }

        }else{ // Si l'effectif n'a pas été saisi ou n'est pas compris entre 1 et 20
            echo "<p class='erreur'>Erreur : Il y a un problème avec l'effectif ou l'effectif n'est pas compris entre 1 et 20 élèves.<br>Veuillez recommencer la saisie.</p>";
        }

      }else{ // Si le thème n'a pas été saisi
            echo "<p class='erreur'>Erreur : Il y a un problème avec le thème de la séance.<br>Veuillez recommencer la saisie.</p>";
      }

      // Traitement des informations
      if ($traiter){


        // Affichage de la requête permettant de détecter un tuple déjà existant
        $query = "select * from seances where Idtheme='$choixTheme' and DateSeance='$ds'";/*
        echo "<br>Requête SQL : <font color='blue'>$query</font><br>";
        echo "<br>";
        echo "<br>";
        */

        // Stockage de la requête dans la variable $result
        $result = mysqli_query($connect,$query);

        $existe = mysqli_num_rows($result); // Retourne le nombre de lignes que contient la table stockée dans $result

        // Test pour vérifier si la requête est valide
        if (!$result) { // Si la requête n'est pas valide
          echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
        }else { // Si la requête est valide

          // Test pour éviter d'avoir 2 séances sur le même thème le même jour
          if ($existe){ // S'il existe déjà une séance sur ce thème ce jour là
            echo "<p class='erreur'>Il y a déjà une séance sur ce thème le $ds.<br>Veuillez choisir une autre date.</p>";
          }
          else{ // Si aucune séance sur ce thème n'existe ce jour là


            // Affichage de la requête pour ajouter la séance dans la table séances
            $query2 = "insert into seances value (NULL, '$ds', '$effMax', '$choixTheme')";/*
            echo "<br>Requête SQL : <font color='blue'>$query2</font><br>";
            echo "<br>";
            echo "<br>";
            */

            // Stockage de la requête dans la variable $result2
            $result2 = mysqli_query($connect, $query2);

            if (!$result2) {
              echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
              echo "<p class='erreur'><br>La séance n'a pas été ajoutée.</p>";
            }else{
              echo "<p class='succes'><br>La séance a bien été ajoutée.</p>";
            }
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
    <a href="ajout_seance.php" target="_self"> Retour au formulaire </a>
  </body>
</html>
