<!DOCTYPE html> 
<html lang="de">

<?php
/*  Sollte diese Datei direkt aufgerufen werden, 
	wird man auf die Startseite weitergeleitet.
	(Ansonsten würde eine unerwünschte Fehlermeldung erzeugt.)
	Weitere Verbesserungen auf Webserver:
	* alle inc-Dateien in separaten Ordner, der für die Benutzer
	  nicht zugänglich ist. 
	* für Benutzer nur 'index.php' zulassen
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
    <?php 
        foreach($kunde->GetAllKunde() as $customer){
            foreach($customer as $key => $value){
                echo '<b>' . $key . '</b>: ';
                echo $value . '<br>';
            }
        }
    ?>
    <form method="post">
        <input type="submit" name="zumWebshop" value="Zum Webshop">
    </form>
</body>
</html>

<?php
}	
?>