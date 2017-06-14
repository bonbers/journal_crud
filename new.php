<?php

	error_reporting( ~E_NOTICE ); // Notice !

	include ('connect.php');

	if(isset($_POST['btnsave']))
	{
		$titre = $_POST['titre'];// titre
		$article = $_POST['article'];// article

		$imgFile = $_FILES['picture']['name'];
		$tmp_dir = $_FILES['picture']['tmp_name'];
		$imgSize = $_FILES['picture']['size'];


		if(empty($titre)){
			$errMSG = "Entrez un titre valide";
		}
		else if(empty($article)){
			$errMSG = "Entrez un article cohérent !";
		}
		else if(empty($imgFile)){
			$errMSG = "Sélectionner une image";
		}
		else
		{
			$upload_dir = './images/'; // Répertoire de destination

			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // extension image

			// validation extension
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // validation de l'extension

			// Renommer image
			$picture = rand(1000,1000000).".".$imgExt;

			// Validation format image
			if(in_array($imgExt, $valid_extensions)){
				// Taille fichier max '5MB'
				if($imgSize < 5000000)				{
					move_uploaded_file($tmp_dir,$upload_dir.$picture);
				}
				else{
					$errMSG = "Désolé image trop grande";
				}
			}
			else{
				$errMSG = "Désolé, seulement les formats JPG, JPEG, PNG & GIF sont supportés.";
			}
		}


		// Si aucune erreur , on continue ...
		if(!isset($errMSG))
		{
			$stmt = $DB_con->prepare('INSERT INTO article(titre,article,picture) VALUES(:utitre, :uarticle, :upicture)');
			$stmt->bindParam(':utitre',$titre);
			$stmt->bindParam(':uarticle',$article);
			$stmt->bindParam(':upicture',$picture);

			if($stmt->execute())
			{
				$successMSG = "Article ajouté !";
				// header("refresh:5;index.php"); // redirection vue image après 5 secondes
			}
			else
			{
				$errMSG = "Erreur d'insertion !";
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload, Insert, Update, Delete an Image</title>

<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

<!-- thème optionnel -->
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">

</head>
<body>

<div class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container">

    </div>
</div>

<div class="container">


	<div class="page-header">
    	<h1 class="h2">Journal du Geek !<a class="btn btn-default" href="index.php"> <span class="glyphicon glyphicon-eye-open"></span> &nbsp; Voir tous les articles !</a></h1>
    </div>


	<?php
	if(isset($errMSG)){
			?>
            <div class="alert alert-danger">
            	<span class="glyphicon glyphicon-info-sign"></span> <strong><?php echo $errMSG; ?></strong>
            </div>
            <?php
	}
	else if(isset($successMSG)){
		?>
        <div class="alert alert-success">
              <strong><span class="glyphicon glyphicon-info-sign"></span> <?php echo $successMSG; ?></strong>
        </div>
        <?php
	}
	?>

<form method="post" enctype="multipart/form-data" class="form-horizontal">

  <label class="control-label">Titre</label>
      <input class="form-control" type="text" name="titre" placeholder="Entrer un titre" value="<?php echo $titre; ?>" />
  <label class="control-label">Article</label>
      <input class="form-control" type="text" name="article" placeholder="Votre article" value="<?php echo $article; ?>" />
  <label class="control-label">Image</label>
      <input class="input-group" type="file" name="picture" accept="image/*" />

      <button type="submit" name="btnsave" class="btn btn-default">
        <span class="glyphicon glyphicon-save"></span> &nbsp; Sauvegarder
        </button>
</form>



<div class="alert alert-info">

</div>

</div>

<script src="bootstrap/js/bootstrap.min.js"></script>

</body>
</html>
