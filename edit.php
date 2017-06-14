<?php

	error_reporting( ~E_NOTICE );

	include ('connect.php');

	if(isset($_GET['edit_id']) && !empty($_GET['edit_id']))
	{
		$id = $_GET['edit_id'];
		$stmt_edit = $DB_con->prepare('SELECT titre, article, picture FROM article WHERE id =:uid');
		$stmt_edit->execute(array(':uid'=>$id));
		$edit_row = $stmt_edit->fetch(PDO::FETCH_ASSOC);
		extract($edit_row);
	}
	else
	{
		header("Location: index.php");
	}



	if(isset($_POST['btn_save_updates']))
	{
		$titre = $_POST['titre'];// titre
		$article = $_POST['article'];// article

		$imgFile = $_FILES['picture']['name'];
		$tmp_dir = $_FILES['picture']['tmp_name'];
		$imgSize = $_FILES['picture']['size'];

		if($imgFile)
		{
			$upload_dir = './images/'; // Repertoire de destination
			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // recupere l'extension de l'image
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valide l'extension
			$picture = rand(1000,1000000).".".$imgExt;
			if(in_array($imgExt, $valid_extensions))
			{
				if($imgSize < 5000000)
				{
					unlink($upload_dir.$edit_row['picture']);
					move_uploaded_file($tmp_dir,$upload_dir.$picture);
				}
				else
				{
					$errMSG = "Désolé, l'image est trop grande";
				}
			}
			else
			{
				$errMSG = "Désolé, seulement les formats JPG, JPEG, PNG & GIF files sont supportés.";
			}
		}
		else
		{
			// Si aucune image sélection on récupère la plus vieille
			$picture = $edit_row['picture']; // Plus vieille image de la base de données
		}


		// Si aucune erreur, on continue... !!!!!
		if(!isset($errMSG))
		{
			$stmt = $DB_con->prepare('UPDATE article SET titre=:utitre, article=:uarticle, picture=:upicture WHERE id=:uid');
			$stmt->bindParam(':utitre',$titre);
			$stmt->bindParam(':uarticle',$article);
			$stmt->bindParam(':upicture',$picture);
			$stmt->bindParam(':uid',$id);

			if($stmt->execute()){
				?>
                <script>
				alert('Update effectué avec succès');
				window.location.href='index.php';
				</script>
                <?php
			}
			else{
				$errMSG = "Désolé aucune données Update !";
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

<!-- Thème optionnel -->
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">

<!-- Ma feuille de style -->
<link rel="stylesheet" href="style.css">

<!-- Dernier version de Bootstrap -->
<script src="bootstrap/js/bootstrap.min.js"></script>

<script src="jquery-1.11.3-jquery.min.js"></script>
</head>
<body>


<div class="container">


	<div class="page-header">
    	<h1 class="h2">Update<a class="btn btn-default" href="index.php">Tous les articles</a></h1>
    </div>

<div class="clearfix"></div>

<form method="post" enctype="multipart/form-data" class="form-horizontal">


    <?php
	if(isset($errMSG)){
		?>
        <div class="alert alert-danger">
          <span class="glyphicon glyphicon-info-sign"></span> &nbsp; <?php echo $errMSG; ?>
        </div>
        <?php
	}
	?>

    	<label class="control-label">Titre</label>
      <input class="form-control" type="text" name="titre" value="<?php echo $titre; ?>" required />

    	<label class="control-label">Article</label>
      <input class="form-control" type="text" name="article" value="<?php echo $article; ?>" required />

    	<label class="control-label">Image</label>

        	<p><img src="./images/<?php echo $picture; ?>" height="50" width="50" /></p>
        	<input class="input-group" type="file" name="picture"/>
        <button type="submit" name="btn_save_updates" class="btn btn-default"><span class="glyphicon glyphicon-save"></span>Update</button>

        <a class="btn btn-default" href="index.php"> <span class="glyphicon glyphicon-backward"></span> cancel </a>

</form>


<div class="alert alert-info">
</div>

</div>
</body>
</html>
