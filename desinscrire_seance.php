<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="resources/style.css" type="text/css">
    <meta charset="utf-8">
  </head>
  <body>

    <!-- Titre de niveau 1 -->
    <h1> Désinscription d'un élève à une séance </h1>
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
      $desinscrit = False;
      $traiter1 = False;
      $traiter2 = False;

      // Connexion à la base de données
      $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');


      if (isset($_POST["choixEleve"]) && ctype_digit($_POST["choixEleve"])) { // Si un élève a été sélectionné et est un entier
        $eleve = $_POST["choixEleve"];

        if (isset($_POST["menuChoixSeance"]) && ctype_digit($_POST["menuChoixSeance"])) { // Si une séance a été sélectionnée et est un entier
          $seance = $_POST["menuChoixSeance"];

          // Affichage de la requête de sécurité
          $query_secu1 = "select inscription.idseance, dateSeance from inscription
                          inner join seances on inscription.idseance = seances.idseance
                          where inscription.idseance = '$seance' and inscription.ideleve='$eleve'";/*
          echo "<br>Requête SQL : <font color='blue'>$query_secu1</font><br>";
          echo "<br>";
          echo "<br>";*/

          // Stockage de la requête dans la variable $result_secu1
          $result_secu1 = mysqli_query($connect, $query_secu1);

          // Test de sécurité en cas de modification html
          if (!$result_secu1) { // Si la requête n'est pas valide
            echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
          }else { // Si la requête est valide
            $row = mysqli_fetch_array($result_secu1, MYSQL_NUM);
            if (mysqli_num_rows($result_secu1) == 0 || new DateTime($row[1]) <= new DateTime($date)) { // Si la séance et/ou l'élève n'existe pas
              echo "<p class='erreur'>Il y a un problème avec la désinscription. <br>Il faut éviter de modifier le code html :)</p>";
              exit('<br><br><br>
              <!-- Ajout d\'un lien pour retourner au formulaire -->
              <a href="desinscription_seance.php" target="_self"> Retour au formulaire </a>');
            }else { // Si tout va bien, on autorise la désinscription
              $traiter1 = True;
            }
          }

          // Traitement des informations
          if ($traiter1) {

            // Affichage de la requête pour supprimer l'élève de la table inscription
            $query_desinscrire = "delete from inscription where idseance = '$seance' and ideleve='$eleve'";/*
            echo "<br>Requête SQL : <font color='blue'>$query_desinscrire</font><br>";
            echo "<br>";
            echo "<br>";*/

            // Stockage de la requête dans la variable $result_desinscrire
            $result_desinscrire = mysqli_query($connect, $query_desinscrire);

            // Test pour vérifier si la requête est valide
            if (!$result_desinscrire) { // Si la requête n'est pas valide
              echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect);
              echo "<br> L'élève n'a pas été désinscrit</p>";
            }else { // Si la requête est valide
              $desinscrit = True;
            }
          }
        }
      }

      // Test de sécurité en cas de modification html
      if (isset($_POST["menuChoixSeance"]) && ctype_digit($_POST["menuChoixSeance"])) {
        $seance = $_POST["menuChoixSeance"];

        // Affichage de la requête de sécurité
        $query_secu2 = "select inscription.idseance, dateSeance from inscription
                        inner join seances on inscription.idseance = seances.idseance
                        where inscription.idseance = '$seance'";/*
        echo "<br>Requête SQL : <font color='blue'>$query_secu2</font><br>";
        echo "<br>";
        echo "<br>";*/

        // Stockage de la requête dans la variable $result_secu2
        $result_secu2 = mysqli_query($connect, $query_secu2);

        // Test pour vérifier si la requête est valide
        if (!$result_secu2) { // Si la requête n'est pas valide
          echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
        }else { // Si la requête est valide
          $row = mysqli_fetch_array($result_secu2, MYSQL_NUM);
          if (new DateTime($row[1]) <= new DateTime($date)) { // Si la séance se situe dans le passé
            echo "<p class='erreur'>Erreur : cette séance se situe dans le passé. <br>Il faut éviter de modifier le code html :)</p>";
            exit('<br><br><br>
            <!-- Ajout d\'un lien pour retourner au formulaire -->
            <a href="desinscription_seance.php" target="_self"> Retour au formulaire </a>');
          }elseif (mysqli_num_rows($result_secu2) == 0) { // Si la séance n'existe pas ou ne contient pas d'élève (cela revient à la même chose)
            echo "<p class='erreur'>Cette séance ne contient aucun élève.</p>";
          }else { // Si tout va bien, on autorise l'affichage des élèves inscrits à la séance choisie
            $traiter2 = True;
          }
        }
      }else { // Si la séance n'existe pas
        echo "<p class='erreur'>Erreur : il y a un problème avec la séance. <br>Il faut éviter de modifier le code html :)</p>";
      }

      // Affichage des élèves inscrits à la séance
      if ($traiter2) {

        // Affichage de la requête permettant de récupérer toutes les séances
        $query = "select inscription.ideleve, nom, prenom from inscription inner join eleves on inscription.ideleve = eleves.ideleve where inscription.idseance='$seance'";/*
        echo "<br>Requête SQL : <font color='blue'>$query</font><br>";
        echo "<br>";
        echo "<br>";*/

        // Stockage de la requête dans la variable $result
        $result = mysqli_query($connect, $query);

        // Test pour vérifier si la requête est valide
        if (!$result) { // Si la requête n'est pas valide
          echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
        }else { // Si la requête est valide

          // Test pour vérifier si la séance contient des élèves
          if (mysqli_num_rows($result) == 0) { // Si le tableau ne contient aucune ligne
            echo "<p class='erreur'>Aucun élève n'est inscrit à cette séance.</p>";
          }else { // Si le tableau contient au moins une ligne

            // Formulaire pour désinscrire un élève
            echo "<div class='formula'><form method='POST' action='desinscrire_seance.php'>";
            while ($row = mysqli_fetch_array($result, MYSQL_NUM)) { // Tant que $row est une ligne
              echo "<input type='radio' name='choixEleve' value='$row[0]'> $row[1] $row[2]";
              echo "<br>";
            }
            echo "<input type='hidden' name='menuChoixSeance' value='$seance'>";
            echo "<input type='submit' value='Désinscrire cet élève'>";
            echo "<form></div>";
          }
        }
      }

      // Si l'élève est bien désinscrit
      if ($desinscrit){
        echo "<p class='succes'><br>L'élève a bien été désinscrit</p>";
      }

      // Déconnexion de la base de données
      mysqli_close($connect);
    ?>
    <br>
    <br>
    <br>

    <!-- Ajout d'un lien pour retourner au formulaire -->
    <a href="desinscription_seance.php" target="_self"> Retour au formulaire </a>
  </body>
</html>
