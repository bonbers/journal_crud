<?php
// Connexion à la base de données
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=journal_complete;charset=utf8', 'root', 'coda');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

// $id = $_POST['id'];
// // Insertion du message à l'aide d'une requête préparée
// $req = $bdd->prepare('INSERT INTO commentaire (pseudo, message) VALUES(?, ?)');
// $req->execute(array($_POST['pseudo'], $_POST['message']));
$id = $_POST['id'];
$pseudo = $_POST['pseudo'];
$message = $_POST['message'];
// Insertion du message à l'aide d'une requête préparée
$req = $bdd->prepare('INSERT INTO commentaire (idarticle, pseudo, message) VALUES(:idarticle, :pseudo, :message)');
$req->execute(array(
	'idarticle' => $id,
	'pseudo' => $pseudo,
	'message' => $message
 ));

// Redirection du visiteur vers la page du minichat
header('Location: com.php?id='. $id .'');

?>
