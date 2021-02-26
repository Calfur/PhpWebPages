<?php

define('MYSQL_HOST',"localhost");  
define('MYSQL_USER',"root");  
define('MYSQL_PW',"");  
define('MYSQL_DB',"m307_auto"); 
define('MYSQL_TABLE',"autos"); 

checkDB();
$con = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PW, MYSQL_DB);

function checkDB(){
   $con = NULL;
   if($con = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PW)){
      //$con->select_db(MYSQL_DB);
      if(!$con->select_db(MYSQL_DB)){
         //echo "DB erstellen.....Deten hinzufügen";
         $sqlstr = "CREATE DATABASE IF NOT EXISTS " . MYSQL_DB . " DEFAULT CHARACTER SET utf8";
         //echo $sqlstr;
         $con->query($sqlstr);
         $con->select_db(MYSQL_DB);
         //$con->query("USE " . MYSQL_DB);
         $sqlstr = "CREATE TABLE IF NOT EXISTS ".MYSQL_TABLE." (
                     id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
                     name VARCHAR(255) NOT NULL,
                     kraftstoff VARCHAR(255) NOT NULL,
                     farbe VARCHAR(255) NOT NULL,
                     bauart VARCHAR(255) NOT NULL,
                     betankungen INTEGER NOT NULL DEFAULT 0
                  )";
         $con->query($sqlstr);
         $sqlstr = "INSERT INTO ".MYSQL_TABLE." (name, kraftstoff, farbe, bauart) VALUES ('Passat', 'Diesel', '#000000', 'Limousine')";
         $con->query($sqlstr);
         $sqlstr = "INSERT INTO ".MYSQL_TABLE." (name, kraftstoff, farbe, bauart) VALUES ('Opel', 'Benzin', '#222222', 'PickUP')";
         $con->query($sqlstr);
         $sqlstr = "INSERT INTO ".MYSQL_TABLE." (name, kraftstoff, farbe, bauart) VALUES ('Honda', 'Elektro', '#777777', 'VAN')";
         $con->query($sqlstr);
      }
      $con->close();
   }
}