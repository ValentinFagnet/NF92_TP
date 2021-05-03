<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="resources/style.css" type="text/css">
    <meta charset="utf-8">
  </head>
  <body>

    <!-- Titre de niveau 1 -->
    <h1> Noter une séance </h1>
    <hr>

    <!-- Paragraphe explicatif -->
    <p class="texte">
      Ce formulaire vous permet de noter les élèves d'une séance de code passée.<br><br>
      Pour noter les élèves, vous devez entrer le <i>NOMBRE DE FAUTES</i> commises pendant la séance. La note sur 40 sera calculée par l'ordinateur.<br><br>
      Si le champ de notation est vide, c'est que l'élève n'a pas encore été noté. S'il contient un nombre, ce dernier correspond <i>AU NOMBRE DE FAUTES ACTUEL</i> de l'élève.
    </p>

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

      if (isset($_POST["validerSeance"]) && ctype_digit($_POST["validerSeance"])) { // Si une séance a été sélectionnée et est un entier
        $seance = $_POST["validerSeance"];

        // Affichage de la requête de sécurité
        $query_secu1 = "select idseance, dateSeance from seances where idseance = '$seance'";/*
        echo "<br>Requête SQL : <font color='blue'>$query_secu1</font><br>";
        echo "<br>";
        echo "<br>";*/

        // Stockage de la requête dans la variable $result_secu1
        $result_secu1 = mysqli_query($connect, $query_secu1);

        // Test de sécurité en cas de modification html de la séance
        if (!$result_secu1) { // Si la requête n'est pas valide
          echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
        }else { // Si la requête est valide
          $row = mysqli_fetch_array($result_secu1, MYSQL_NUM);
          if (mysqli_num_rows($result_secu1) == 0 || new DateTime($row[1]) >= new DateTime($date)) { // Si la séance se situe dans le futur ou n'existe pas
            echo "<p class='erreur'>Erreur : la séance se situe dans le futur ou n'existe pas. <br>Il faut éviter de modifier le code html :)</p>";
            exit('<br><br><br>
            <!-- Ajout d\'un lien pour retourner au formulaire -->
            <a href="validation_seance.php" target="_self"> Retour au formulaire </a>');
          }else { // Si tout va bien
                $traiter = True;
            }
          }
        }else { // Si la séance n'est pas saisie ou n'est pas un digit
        echo "<p class='erreur'>Erreur : il y a un problème avec la séance. <br>Veuillez recommencer la saisie.</p>";
      }

      // Traitement des informations
      if ($traiter) {

        // Affichage de la requête permettant de récupérer les informations des élèves inscrits à la séance
        $query = "select inscription.ideleve, nom, prenom, note from inscription, eleves where inscription.idseance='$seance' and inscription.ideleve = eleves.ideleve";/*
        echo "<br>Requête SQL : <font color='blue'>$query</font><br>";
        echo "<br>";
        echo "<br>";*/

        // Stockage de la requête dans la variable $result
        $result = mysqli_query($connect,$query);

        // Test qui permet de vérifier que la requête est valide
        if (!$result) { // Si la requête n'est pas valide
          echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
        }else { // Si la requête est valide
          if (!mysqli_num_rows($result)) { // Si aucun élève n'est inscrit à la séance
            echo "<br><br><br><p class='erreur'>Il n'y a aucun élève à noter.</p>";
          }else { // Si il y a au moins un élève inscrit à la séance

            // Formulaire dynamique pour entrer le nombre de fautes pour chaque élève inscrit à une séance
            echo "<div class='formula'><form method='POST' action='noter_eleve.php'>";
            while ($row = mysqli_fetch_array($result)) { // Tant que $row est une ligne
              if ($row[3] == '-1') { // Si la note vaut -1, alors l'élève n'est pas encore noté donc on n'affiche pas sa note actuelle
                echo "<input type='number' name='$row[0]' min='0' max='40'>"." Nombre de fautes pour l'élève "."$row[1] $row[2]";
                echo "<br>";
              }else { // Si la note est différente de -1, alors on affiche la note de l'élève
                $fautes = 40-$row[3];
                echo "<input type='number' name='$row[0]' value='$fautes' min='0' max='40'>"." Nombre de fautes pour l'élève "."$row[1] $row[2] (Note actuelle : $row[3]/40)";
                echo "<br>";
              }
            }
            echo "<input type='hidden' name='idseance' value='$seance'>";
            echo "<input type='submit' value='Valider les notes'>";
            echo "</form></div>";
          }
        }
      }

      // Déconnexion de la base de données
      mysqli_close($connect);
    ?>
    <br>
    <br>
    <br>

    <!-- Ajout d\'un lien pour retourner au formulaire -->
    <a href="validation_seance.php" target="_self"> Retour au formulaire </a>
  </body>
</html>
