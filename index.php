<?php

	include 'connect.php';

	if(isset($_GET['delete_id']))
	{
		// Selection image de la DB à supprimer
		$stmt_select = $DB_con->prepare('SELECT picture FROM article WHERE id =:uid');
		$stmt_select->execute(array(':uid'=>$_GET['delete_id']));
		$imgRow=$stmt_select->fetch(PDO::FETCH_ASSOC);
		unlink("images/".$imgRow['picture']);

		// Selectionner le contenu actuel de la DB à supprimer
		$stmt_delete = $DB_con->prepare('DELETE FROM article WHERE id =:uid');
		$stmt_delete->bindParam(':uid',$_GET['delete_id']);
		$stmt_delete->execute();

		header("Location: index.php");
	}

?>
<!DOCTYPE html PUBLIC>
<html lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
<title>Upload, Insert, Update, Delete an Image</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">
</head>

<body>

<div class="container">

	<div class="page-header">
    	<h1 class="h2">Tous les articles<a class="btn btn-default" href="new.php"> <span class="glyphicon glyphicon-plus"></span> &nbsp;Ajouter un nouveau</a></h1>
    </div>

<br />

<div class="row">
<?php

	$stmt = $DB_con->prepare('SELECT id, titre, article, picture FROM article ORDER BY id DESC');
	$stmt->execute();

	if($stmt->rowCount() > 0)
	{
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			extract($row);
			?>
			<div class="col-xs-3">
				<p class="page-header"><?php echo $titre."&nbsp;/&nbsp;".$article; ?></p>
				<img src="./images/<?php echo $row['picture']; ?>" class="img-rounded" width="80px" height="80px" />
				<p class="page-header">
				<span>
				<a class="btn btn-info" href="edit.php?edit_id=<?php echo $row['id']; ?>" title="click for edit" onclick="return confirm('Sur de modifier ?')"><span class="glyphicon glyphicon-edit"></span> Edit</a>
				<a class="btn btn-danger" href="?delete_id=<?php echo $row['id']; ?>" title="click for delete" onclick="return confirm('Sur de supprimer?')"><span class="glyphicon glyphicon-remove-circle"></span> Delete</a>
				<a class="btn btn-info" href="com.php?id=<?php echo $row['id']; ?>" title="click for com" onclick="return confirm('laissez un com ?')"><span class="glyphicon glyphicon-edit"></span> Com </a>
				</span>
				</p>
			</div>
			<?php
		}
	}
	else
	{
		?>
        <div class="col-xs-12">
        	<div class="alert alert-warning">
            	<span class="glyphicon glyphicon-info-sign"></span> &nbsp; Aucune donnée trouvée...
            </div>
        </div>
        <?php
	}

?>
</div>

<div class="alert alert-info">
</div>

</div>

<script src="bootstrap/js/bootstrap.min.js"></script>


</body>
</html>
