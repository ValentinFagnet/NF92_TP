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
    $traiter = False;
    $date = date("Y\-m\-d");
    $pattern = '/[](){}<>\/+²"*%&=?`"\'^\!$_:;,.1234567890 ]/';

    // Connexion à la base de données
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');

    // Sécurité
    if (!empty($_POST["prenom"]) && !preg_match($pattern, $_POST["prenom"])){ // Si le prénom a été saisi et ne contient aucun chiffre/espace
      $prenom = $_POST["prenom"];

      if (!empty($_POST["nom"]) && !preg_match($pattern, $_POST["nom"])){ // Si le nom a été saisi et ne contient aucun chiffre/espace
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

        }else{ // Si la date de naissance n'a été saisi
          echo "<p class='erreur'> Erreur : Il y a un problème avec la date de naissance de l'élève. <br>Veuillez recommencer la saisie.</p>";
        }

      }else{ // Si le nom n'a pas été saisi et/ou chiffres/espaces
        echo "<p class='erreur'> Erreur : Il y a un problème avec le nom de l'élève ou vous avez entré des caractères non-autorisés. <br>Veuillez recommencer la saisie. (Vous pouvez seulement utiliser des lettres minuscules et majuscules et/ou accentuées, pas d'espace ou de chiffre)</p>";
      }

    }else{ // Si le prénom n'a pas été saisi et/ou contient des chiffres/espaces
      echo "<p class='erreur'> Erreur : Il y a un problème avec le prénom de l'élève ou vous avez entré des caractères non-autorisés. <br>Veuillez recommencer la saisie. (Vous pouvez seulement utiliser des lettres minuscules et majuscules et/ou accentuées, pas d'espace ou de chiffre)</p>";
    }

    // Traitement des informations
    if ($traiter){ // Si la sécurité est respectée

      // Affichage des résultats obtenus avec le formulaire
      echo "<p class='texte'> Voici les informations obtenues via le formulaire : "."<br>"."Prénom de l'élève : ".$prenom."<br>"."Nom de l'élève : ".$nom."<br>"."Date de naissance de l'élève : ".$ddn."<br>";
      echo "Date d'ajout de l'élève : "."'$date'";
      echo "<br></p>";


      // Affichage de la requête permettant de détecter un tuple déjà existant
      $query = "select * from eleves where nom='$nom' and prenom='$prenom'";/*
      echo "<br>Requête SQL : <font color='blue'>$query</font><br>";
      echo "<br>";
      echo "<br>";
      */

      // Stockage de la requête dans la variable $result
      $result = mysqli_query($connect, $query);

      $existe = mysqli_num_rows($result); // Retourne le nombre de lignes que contient la table stockée dans $result

      // Test pour vérifier si la requête est valide
      if (!$result){ // Si la requête n'est pas valide
        echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
      }else { // Si la requête est valide
        if ($existe){ // Si la personne existe déjà dans la BDD
          echo "<div class='formula'><p class='texte'>Attention, un élève avec le même prénom et le même nom existe déjà dans la base de donnée. Êtes-vous sûr de vouloir ajouter cet élève ?</p><br>";
          echo "<form method='POST' action='ajouter_eleve.php'>";
          echo "<br>";
          echo "<input type='radio' name='choix' value='non' checked> Non";
          echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
          echo "<input type='radio' name='choix' value='oui'> Oui";
          echo "<br>";

          // Transmission d'informations cachées pour l'utilisateur
          echo "<input type='hidden' name='prenom' value='$prenom'>";
          echo "<input type='hidden' name='nom' value='$nom'>";
          echo "<input type='hidden' name='naissance' value='$ddn'>";

          echo "<input type='submit' value='Valider'>";
          echo "</form></div>";
        }else{ // Si la personne n'existe pas dans la BDD

          // Affichage de la requête pour ajouter l'élève dans la table eleves
          $query2 = "insert into eleves values (NULL, '$nom', '$prenom', '$ddn', '$date')";/*
          echo "<br>Requête SQL : <font color='blue'>$query2</font><br>";
          echo "<br>";
          echo "<br>";
          */

          // Stockage de la requête dans la variable $result
          $result2 = mysqli_query($connect, $query2);

          // Test qui permet de vérifier que la requête est valide
          if (!$result2){ // Si la requête n'est pas valide
                echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect);
                echo "<br>L'élève n'a pas été ajouté.</p>";
          }else{ // Si la requête est valide
            echo "<br><p class='succes'> L'élève a bien été ajouté.</p>";
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
    <a href="ajout_eleve.html" target="_self"> Retour au formulaire </a>
  </body>
</html>
