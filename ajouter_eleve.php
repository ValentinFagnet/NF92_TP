<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="resources/style.css" type="text/css">
    <meta charset="utf-8">
  </head>
  <body>

    <!-- Titre de niveau 1 -->
    <h1> Ajout d'un élève </h1>
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
      $pattern = '/[[](){}<>\/+²"*%&=?`"\'^\!$_:;,.1234567890 ]/';

      // Connexion à la base de données
      $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');

      // Sécurité
      if (!empty($_POST["prenom"]) && !preg_match($pattern, $_POST["prenom"])){ // Si le prénom a été saisi et ne contient aucun caractère interdit

        $prenom = $_POST["prenom"];

        if (!empty($_POST["nom"]) && !preg_match($pattern, $_POST["nom"])){ // Si le nom a été saisi et ne contient aucun caractère interdit

          $nom = $_POST["nom"];

          if (!empty($_POST["naissance"])){ // Si la date de naissance a été saisie

            $ddn = $_POST["naissance"];
            $traiter = True; // On autorise le traitement des informations

            /*
            // Débogage
            echo "<hr>";
            echo "<center> Début du débogage </center>";
            echo "<br>";
            echo "nom = $nom";
            echo "<br>";
            echo "prenom = $prenom";
            echo "<br>";
            echo "naissance = $ddn";
            echo "<br>";
            echo "date = $date";
            echo "<br>";
            echo "<center> Fin du débogage </center>";
            echo "<hr>";
            */

          }else{ // Si la date de naissance n'a été saisie
            echo "<p class='erreur'> Erreur : Il y a un problème avec la date de naissance de l'élève. <br>Veuillez recommencer la saisie. <br>Il faut éviter de modifier le code html :)</p>";
          }

        }else{ // Si le nom n'a pas été saisi et/ou contient des caractères interdits
          echo "<p class='erreur'> Erreur : Il y a un problème avec le nom de l'élève ou vous avez entré des caractères non-autorisés. <br>Veuillez recommencer la saisie.</p>";
        }

      }else{ // Si le prénom n'a pas été saisi et/ou contient des caractères interdits
        echo "<p class='erreur'> Erreur : Il y a un problème avec le prénom de l'élève ou vous avez entré des caractères non-autorisés. <br>Veuillez recommencer la saisie.</p>";
      }

      // Traitement des informations
      if ($_POST["choix"] == "oui" && $traiter){ // On traite les informations si l'utilisateur a répondu "oui" et si les champs n'ont pas été modifiés


        // Affichage de la requête
        $query = "insert into eleves values (NULL, '$nom', '$prenom', '$ddn', '$date')";/*
        echo "<br>Requête SQL : <font color='blue'>$query</font><br>";
        echo "<br>";
        echo "<br>";
        */

        // Stockage de la requête dans la variable $result
        $result = mysqli_query($connect, $query);

        // Test qui permet de vérifier que la requête est valide
        if (!$result){ // Si la requête n'est pas valide
          echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
          echo "<br><p class='erreur'>L'élève n'a pas été ajouté.</p>";
        }else{ // Si la requête est valide
          echo "<p class='succes'>L'élève a bien été ajouté.</p>";
        }

      }else{ // On ne traite pas les informations si l'utilisateur a répondu "non"
        echo "<p class='erreur'><br>L'élève n'a pas été ajouté.</p>";
      }

      // Déconnexion de la base de données
      mysqli_close($connect);
    ?>
    <br>
    <br>
    <br>

    <!-- Ajout d'un lien pour retourner au formulaire -->
    <a href="ajout_eleve.html" target="_self"> Retour au formulaire </a>
  </body>
</html>
