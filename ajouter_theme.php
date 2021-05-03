<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="resources/style.css" type="text/css">
    <meta charset="utf-8">
  </head>
  <body>

    <!-- Titre de niveau 1 -->
    <h1> Ajout d'un thème </h1>
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
      $pattern = '/[\'\"1234567890]/';

      // Connexion à la base de données MySQL
      $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');

      // Sécurité
      if (!empty($_POST["theme"]) && !ctype_space($_POST["theme"]) && !preg_match($pattern, $_POST["theme"])){ // Si le thème a été saisi et ne contient aucun chiffre et pas de simple/double quote et pas que des espaces
        $theme = $_POST["theme"];

        if (!empty($_POST["desc"]) && !ctype_space($_POST["desc"]) && !preg_match($pattern, $_POST["desc"])){ // Si le descriptif a été saisi et et pas de simple/double quote ne contient aucun chiffre et pas que des espaces

          $descriptif = $_POST["desc"];
          $traiter = True; // On autorise le traitement des informations

          /* // Débogage
          echo "<hr>";
          echo "<center> Début du débogage </center>";
          echo "<br>";
          echo "theme = $theme";
          echo "<br>";
          echo "descriptif = $descriptif";
          echo "<br>";
          echo "<center> Fin du débogage </center>";
          echo "<hr>";
          */

        } else { // Si le descriptif n'a pas été saisi ou ne contient que des espaces

          echo "<p class='erreur'>Erreur : Il y a un problème avec le descriptif du thème. <br>Les apostrophes, les guillemets et les chiffres ne sont pas autorisés. <br>Veuillez recommencer la saisie.</p>";
        }

      } else { // Si le thème n'a pas été saisi ou ne contient que des espaces

        echo "<p class='erreur'>Erreur : Il y a un problème avec le thème. <br>Les apostrophes, les guillemets et les chiffres ne sont pas autorisés. <br>Veuillez recommencer la saisie.</p>";
      }

      if ($traiter){

        // Affichage des résultats obtenus avec le formulaire
        echo "<p class='texte'>Voici les informations obtenues via le formulaire : "."<br>"."Nom du thème : ".$theme."<br>"."Descriptif du thème : ".$descriptif."<br></p>";

        // Affichage de la requête
        $query_check_existe = "select idtheme from themes where supprime = '0' and nom = '$theme'";/*
        echo "<br>Requête SQL : <font color='blue'>$query_check_existe</font><br>";
        echo "<br>";
        echo "<br>";*/

        // Stockage de la requête dans la varable $result2
        $result3 = mysqli_query($connect, $query_check_existe);

        // Test pour vérifier si la requête est valide
        if (!$result3) { // Si la requête n'est pas valide
          echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
        }else { // Si la requête est valide
          if (mysqli_num_rows($result3) != 0) { // Si le thème existe déjà sans être supprimé
            echo "<p class='erreur'>Ce thème existe déjà.</p>";
          }else {

            // Affichage de la requête
            $query_check_supprime = "select idtheme from themes where supprime = '1' and nom = '$theme'";/*
            echo "<br>Requête SQL : <font color='blue'>$query_check_supprime</font><br>";
            echo "<br>";
            echo "<br>";*/

            // Stockage de la requête dans la varable $result2
            $result2 = mysqli_query($connect, $query_check_supprime);

            if (!$result2) { // Si la requête n'est pas valide
              echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
            }else { // Si la requête est valide
              if (mysqli_num_rows($result2) != 0) { // Si le thème existe déjà et était supprimé

                // Affichage de la requête
                $query_update = "update themes set supprime = '0', descriptif = '$descriptif' where nom = '$theme'";/*
                echo "<br>Requête SQL : <font color='blue'>$query_update</font><br>";
                echo "<br>";
                echo "<br>";*/

                // Stockage de la requête dans la variable $result_update
                $result_update = mysqli_query($connect, $query_update);

                // Test qui permet de vérifier que la requête est valide
                if (!$result_update) { // Si la requête n'est pas valide
                  echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
                  echo "<p class='erreur'><br>Le thème n'a pas pu être réactivé.</p>";
                }else { // Si la requête est valide
                  echo "<p class='succes'>Un thème avec le même nom existe déjà. Il a été réactivé et le descriptif a été mis à jour.</p>";
                }
              }else { // Si le thème n'existe pas

                // Affichage de la requête
                $query = "insert into themes values (NULL, '$theme', '0', '$descriptif')";/*
                echo "<br>Requête SQL : <font color='blue'>$query</font><br>";
                echo "<br>";
                echo "<br>";*/

                // Stockage de la requête dans la variable $result
                $result = mysqli_query($connect, $query);

                // Test qui permet de vérifier que la requête est valide
                if (!$result){ // Si la requête n'est pas valide
                  echo '<br><p class="erreur">Erreur dans la requête : '.mysqli_error($connect)."</p>";
                  echo "<p class='erreur'><br>Le thème n'a pas été ajouté.</p>";
                }else { // Si la requête est valide
                  echo "<p class='succes'><br>Le thème a bien été ajouté.</p>";
                }
              }
            }
          }
        }
      }

      // Deconnexion de la base de données
      mysqli_close($connect);
    ?>
    <br>
    <br>
    <br>

    <!-- Ajout d'un lien pour retourner au formulaire -->
    <a href="ajout_theme.html" target="_self"> Retour au formulaire </a>
  </body>
</html>
