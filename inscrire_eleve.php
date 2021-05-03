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

      if (isset($_POST["menuChoixEleve"]) && ctype_digit($_POST["menuChoixEleve"])) { // Si un élève a été sélectionné et est un entier
        $eleve = $_POST["menuChoixEleve"];

        if (isset($_POST["menuChoixSeance"]) && ctype_digit($_POST["menuChoixSeance"])) { // Si une séance a été sélectionnée et est un entier
          $seance = $_POST["menuChoixSeance"];

          // Affichage de la requête de sécurité
          $query_secu1 = "select idseance, dateSeance from seances where idseance = '$seance'";/*
          echo "<br>Requête SQL : <font color='blue'>$query_secu1</font><br>";
          echo "<br>";
          echo "<br>";*/

          // Affichage de la requête de sécurité
          $query_secu2 = "select ideleve from eleves where ideleve = '$eleve'";/*
          echo "<br>Requête SQL : <font color='blue'>$query_secu2</font><br>";
          echo "<br>";
          echo "<br>";*/

          // Stockage de la requête dans la variable $result_secu1
          $result_secu1 = mysqli_query($connect, $query_secu1);

          // Stockage de la requête dans la variable $result_secu1
          $result_secu2 = mysqli_query($connect, $query_secu2);

          // Test de sécurité en cas de modification html de la séance
          if (!$result_secu1) { // Si la requête n'est pas valide
            echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
          }else { // Si la requête est valide
            $row = mysqli_fetch_array($result_secu1, MYSQL_NUM);
            if (mysqli_num_rows($result_secu1) == 0 || new DateTime($row[1]) <= new DateTime($date)) { // Si la séance se situe dans le passé ou n'existe pas
              echo "<p class='erreur'>Erreur : la séance se situe dans le passé ou n'existe pas. <br>Il faut éviter de modifier le code html :)</p>";
              exit('<br><br><br>
              <!-- Ajout d\'un lien pour retourner au formulaire -->
              <a href="inscription_eleve.php" target="_self"> Retour au formulaire </a>');
            }else { // Si tout va bien

              // Test de sécurité en cas de modification html de l'élève
              if (!$result_secu2) { // Si la requête n'est pas valide
                echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
              }else { // Si la requête est valide
                if (mysqli_num_rows($result_secu2) == 0) { // Si l'élève n'existe pas
                  echo "<p class='erreur'>Erreur : Cet élève n'existe pas. <br>Il faut éviter de modifier le code html :)</p>";
                  exit('<br><br><br>
                  <!-- Ajout d\'un lien pour retourner au formulaire -->
                  <a href="inscription_eleve.php" target="_self"> Retour au formulaire </a>');
                }else { // Si l'élève existe
                  $traiter = True;
                }
              }
            }
          }
        }else { // Si la séance n'est pas saisie ou n'est pas un digit
          echo "<p class='erreur'>Erreur : il y a un problème avec la séance. <br>Veuillez recommencer la saisie.</p>";
        }
      }else { // Si l'élève n'est pas saisie ou n'est pas un digit
        echo "<p class='erreur'>Erreur : il y a un problème avec l'élève. <br>Veuillez recommencer la saisie.</p>";
      }

      // Traitement des informations
      if ($traiter) {

        // Affichage de la requête permettant de récupérer les élèves inscrits à la séance choisie
        $query_check = "select * from inscription where idseance = $seance and ideleve = $eleve";/*
        echo "<br>Requête SQL : <font color='blue'>$query_check</font><br>";
        echo "<br>";
        echo "<br>";*/

        // Stockage de la requête dans la variable $result_check
        $result_check = mysqli_query($connect, $query_check);

        // Sécurité
        if (!mysqli_num_rows($result_check)){ // Si l'élève n'est pas inscrit à la séance

          // Affichage de la requête permettant d'inscrire l'élève à la séance
          $query = "insert into inscription values ('$seance', '$eleve', '-1')"; /* // La convention choisie est la suivante : on attribue la note de '-1' à un élève non noté
          echo "<br>Requête SQL : <font color='blue'>$query</font><br>";
          echo "<br>";
          echo "<br>";*/

          // Affichage de la requête permettant de récupérer l'effectif maximal de la séance
          $query_eff = "select effMax from seances where idseance='$seance'";/*
          echo "<br>Requête SQL : <font color='blue'>$query_eff</font><br>";
          echo "<br>";
          echo "<br>";*/

          $effMax = mysqli_fetch_array(mysqli_query($connect, $query_eff)); // On récupère l'effectif maximale de la séance

          // Affichage de la requête permettant de récupérer le nombre d'inscrits à la séance
          $query_nb = "select * from inscription where idseance = $seance";/*
          echo "<br>Requête SQL : <font color='blue'>$query_nb</font><br>";
          echo "<br>";
          echo "<br>";*/

          if (mysqli_num_rows(mysqli_query($connect, $query_nb)) < $effMax[0]) { // Si la séance n'est pas pleine

            // Stockage de la requête dans la variable $result
            $result = mysqli_query($connect, $query);

            // Test qui permet de vérifier que la requête est valide
            if (!$result) { // Si la requête n'est pas valide
              echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
            }else{ // Si la requête est valide
              echo "<p class='succes'>L'élève a bien été inscrit.</p>";
            }
          }else{ // Si la séance est pleine
            echo "<p class='erreur'>La séance est déjà pleine !</p>";
          }
        }else{ // Si l'élève est déjà inscrit à la séance
          echo "<p class='erreur'>L'élève est déjà inscrit à cette séance.</p>";
        }
      }

      // Déconnexion de la base de données
      mysqli_close($connect);
    ?>
    <br>
    <br>
    <br>

    <!-- Ajout d'un lien pour retourner au formulaire -->
    <a href="inscription_eleve.php" target="_self"> Retour au formulaire </a>
  </body>
</html>
