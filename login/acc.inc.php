<!DOCTYPE html>
<html lang="de">

<?php
/*  Sollte diese Datei direkt aufgerufen werden, 
	wird man auf die Startseite weitergeleitet.
	(Ansonsten würde der Webshop ohne Anmeldung angezeigt.)
	Weitere Verbesserungen auf Webserver:
	* alle inc-Dateien in separaten Ordner, der für die Benutzer
	  nicht zugänglich ist. 
	* für Benutzer nur index.php zulassen
*/
if (!isset($_SESSION['status'])) {
	header('Location: ../index.php');
	exit;
} else {
?>

	<head>
		<title><?php echo $titel ?></title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="schutz/style.css">
	</head>

	<body>

		<form method="post">
			<h1>Status: Kontodaten lesen</h1>

			<?php echo "Willkommen im internen Bereich beim Kontodaten lesen!" . "<br>" . "<br>"; ?>
			Hier sind die Kontodaten: <br>
			<?php echo $kontodaten ?>
			<hr>
			Hier kommen Sie zur&uuml;ck zum Webshop:<br>
			<input type="submit" name="webshop" value="Zum Webshop"><br>
			<hr>
		</form>

	</body>

</html>

<?php
}
?>