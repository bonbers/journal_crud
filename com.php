<?php

	error_reporting( ~E_NOTICE );

	include ('connect.php');

	if(isset($_GET['id']) && !empty($_GET['id']))
	{
		$id = $_GET['id'];
		$stmt_edit = $DB_con->prepare('SELECT titre, article, picture FROM article WHERE id =:uid');
		$stmt_edit->execute(array(':uid'=>$id));
		$edit_row = $stmt_edit->fetch(PDO::FETCH_ASSOC);
		extract($edit_row);
	}
	else
	{
		header("Location: index.php");
	}

?>
<?php

// Récupération des 10 derniers messages
// Methode sans les commentaire ---->
// $reponse = $DB_con->query('SELECT pseudo, message, date FROM commentaire ORDER BY id DESC LIMIT 0, 10');
// <-----------------------------------------
$reponse = $DB_con->prepare('SELECT pseudo, message, date FROM commentaire WHERE idarticle= ? ORDER BY date DESC LIMIT 0, 10');

// Affichage de chaque message (toutes les données sont protégées par htmlspecialchars)
$reponse->execute(array($_GET['id']));

while ($donnees = $reponse->fetch())
{
  echo '<p>'.($donnees['date']) .'<p><strong>' . htmlspecialchars($donnees['pseudo']) . '</strong> : ' . htmlspecialchars($donnees['message']) . '</p>';
}
$reponse->closeCursor();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload, Insert, Update, Delete an Image</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<!-- Thème optionnel -->
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">
<!-- Ma feuille de styl Bootstrap -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="jquery-1.11.3-jquery.min.js"></script>
</head>
<body>

<div class="container">

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
      <p><?php echo $titre; ?></p>

    	<label class="control-label">Article</label>
      <p><?php echo $article; ?></p>

    	<label class="control-label">Image</label>
      <p><img src="./images/<?php echo $picture; ?>" height="50" width="50" /></p>

</form>

<div class="alert alert-info">
</div>
</div>
</body>
</html>


  <form action="minichat_post.php" method="post">
      <p>
			<input type="hidden" name="id" value="<?php echo $id; ?>">
      <label for="pseudo">Pseudo</label> : <input type="text" name="pseudo" id="pseudo" /><br />
      <label for="message">Message</label> :  <input type="text" name="message" id="message" /><br />
      <input type="submit" value="Envoyer" />
  </p>
  </form>
