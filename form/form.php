<html>

<head>
  <title>Bestellformular</title>
</head>

<body>
  <?php
  //
  // Die Daten werden in Variable gefüllt:
  //
  $Anrede = (isset($_POST["Anrede"]) && !empty($_POST["Anrede"]) && filter_var($_POST['Anrede'], 513)) ? $_POST["Anrede"] : "";
  $Vorname = new StringField("Vorname", "Vorname");
  $Nachname = (isset($_POST["Nachname"]) && !empty($_POST["Nachname"]) && filter_var($_POST['Nachname'], 513)) ? $_POST["Nachname"] : "";
  $Email = (isset($_POST["Email"]) && !empty($_POST["Email"]) && filter_var($_POST['Email'], 513)) ? $_POST["Email"] : "";
  $Anzahl = (isset($_POST["Anzahl"]) && !empty($_POST["Anzahl"])) ? $_POST["Anzahl"] : "";
  $Promo = (isset($_POST["Promo"]) && !empty($_POST["Promo"]) && filter_var($_POST['Promo'], 513)) ? $_POST["Promo"] : "";
  $Sektion = (isset($_POST["Sektion"]) && !empty($_POST["Sektion"]) && is_array($_POST["Sektion"])) ? $_POST["Sektion"] : array();
  $Kommentare = (isset($_POST["Kommentare"]) && !empty($_POST["Kommentare"]) && filter_var($_POST['Kommentare'], 513)) ? $_POST["Kommentare"] : "";
  $AGB = (isset($_POST["AGB"]) && !empty($_POST["AGB"]) && filter_var($_POST['AGB'], 513)) ? $_POST["AGB"] : "";
  $ok = false;
  $validationErrors = array();

  if (filledFormGotReturned()) {
    $ok = validateForm($validationErrors);

    if ($ok) {
      ?>
        <h1>Bestätigung</h1>
        <p>Ihre Bestellung für WM-Tickets wurde mit den folgenden Daten angenommen:</p>
      <?php
      
      displayUserInput($Anrede, "Anrede");
      displayUserInput($Vorname->getValue(), "Vorname");
      displayUserInput($Nachname, "Nachname");
      displayUserInput($Email, "E-Mail");
      displayUserInput($Promo, "Promo");
      displayUserInput($Anzahl, "Anzahl Karten");
      displayUserInput(implode(", ", $Sektion), "Sektion");
      displayUserInput($Kommentare, "Kommentare");
      displayUserInput($AGB, "AGB");
    } else {
      displayValidationErrors($validationErrors);
    }
  }

  function filledFormGotReturned(){
    return isset($_POST["Submit"]) && !empty($_POST["Submit"]);
  }

  function validateForm(&$validationErrors){
    $ok = true;
    //
    // Die Eingabewerte werden überprüft:
    //
    $AnredeArr = array('Hr.', 'Fr.');
    $tmpIo = false;
    if (!isset($_POST["Anrede"]) || empty($_POST["Anrede"]) || !filter_var($_POST['Anrede'], 513)) {
      $ok = false;
      $validationErrors[] = "Anrede";
    } else {
      // Anrede ist gesetzt und ist ein String
      foreach ($AnredeArr as $anr) {
        if (strcmp($anr, $_POST["Anrede"])) {
          $tmpIo = true;
        }
      }
      if (!$tmpIo) {
        // Anrede enthält weder Hr. noch Fr. 
        $ok = false;
        $validationErrors[] = "Anrede";
      }
    }

    if (!isset($_POST["Vorname"]) || empty($_POST["Vorname"]) || !filter_var($_POST['Vorname'], 513) || trim($_POST["Vorname"]) == "") {
      $ok = false;
      $validationErrors[] = "Vorname";
    }
    if (!isset($_POST["Nachname"]) || empty($_POST["Nachname"]) || !filter_var($_POST['Nachname'], 513) || trim($_POST["Nachname"]) == "") {
      $ok = false;
      $validationErrors[] = "Nachname";
    }
    if (
      !isset($_POST["Email"]) || empty($_POST["Email"]) || trim($_POST["Email"]) == "" ||
      !preg_match('/^[a-zA-Z0-9_\-.]+@[a-zA-Z0-9\-.]+\.[a-zA-Z]{2,6}$/', $_POST["Email"])
    ) {
      $ok = false;
      $validationErrors[] = "E-Mail";
    }
    if (!isset($_POST["Promo"]) || empty($_POST["Promo"]) || !filter_var($_POST['Promo'], 513) || trim($_POST["Promo"]) == "") {
      $ok = false;
      $validationErrors[] = "Promo";
    }
    if (!isset($_POST["Anzahl"]) || empty($_POST["Anzahl"]) || !filter_var($_POST['Anzahl'], 513) || $_POST["Anzahl"] == "0") {
      $ok = false;
      $validationErrors[] = "Anzahl Karten";
    }
    if (!isset($_POST["Sektion"]) || empty($_POST["Sektion"]) || !is_array($_POST["Sektion"])) {
      $ok = false;
      $validationErrors[] = "Sektion";
    }
    if (!isset($_POST["Kommentare"]) || empty($_POST["Kommentare"]) || !filter_var($_POST['Kommentare'], 513) || trim($_POST["Kommentare"]) == "") {
      $ok = false;
      $validationErrors[] = "Kommentare";
    }
    if (!isset($_POST["AGB"]) || empty($_POST["AGB"]) || !filter_var($_POST['AGB'], 513)) {
      $ok = false;
      $validationErrors[] = "AGB";
    }

    return $ok;
  }

  function displayUserInput($input, $inputName){
    $input = nl2br(htmlspecialchars($input));
    echo "<b>$inputName:</b> $input<br />";
  }

  function displayValidationErrors($validationErrors){
    echo "<p><b>Formular unvollst&auml;ndig</b></p>";
    echo "<ul><li>";
    echo implode("</li><li>", $validationErrors);
    echo "</li></ul>";
  }

  if (!$ok) {
    //
    // Das Formular wird entweder leer oder mit Vorgabewerten ausgegeben:
    //
    ?>
    <h1>WM-Ticketservice</h1>
    <form method="post">
      <input type="radio" name="Anrede" value="Hr." <?php
                                                    if ($Anrede == "Hr.") {
                                                      echo "checked=\"checked\" ";
                                                    }
                                                    ?> />Herr
      <input type="radio" name="Anrede" value="Fr." <?php
                                                    if ($Anrede == "Fr.") {
                                                      echo "checked=\"checked\" ";
                                                    }
                                                    ?> />Frau <br />
      Vorname <input type="text" name="Vorname" value="<?php
                                                        echo $Vorname->getValue();
                                                        ?>" /><br />
      Nachname <input type="text" name="Nachname" value="<?php
                                                          echo htmlspecialchars($Nachname);
                                                          ?>" /><br />
      E-Mail-Adresse <input type="text" name="Email" value="<?php
                                                            echo htmlspecialchars($Email);
                                                            ?>" /><br />
      Promo-Code <input type="password" name="Promo" value="<?php
                                                            echo htmlspecialchars($Promo);
                                                            ?>" /><br />
      Anzahl Karten
      <select name="Anzahl">
        <option value="0">Bitte w&auml;hlen</option>
        <option value="1" <?php
                          if ($Anzahl == "1") {
                            echo " selected=\"selected\"";
                          }
                          ?>>1</option>
        <option value="2" <?php
                          if ($Anzahl == "2") {
                            echo " selected=\"selected\"";
                          }
                          ?>>2</option>
        <option value="3" <?php
                          if ($Anzahl == "3") {
                            echo " selected=\"selected\"";
                          }
                          ?>>3</option>
        <option value="4" <?php
                          if ($Anzahl == "4") {
                            echo " selected=\"selected\"";
                          }
                          ?>>4</option>
      </select><br />
      Gew&uuml;nschte Sektion im Stadion
      <select name="Sektion[]" size="4" multiple="multiple">
        <option value="nord" <?php
                              if (in_array("nord", $Sektion)) {
                                echo " selected=\"selected\"";
                              }
                              ?>>Nordkurve</option>
        <option value="sued" <?php
                              if (in_array("sued", $Sektion)) {
                                echo " selected=\"selected\"";
                              }
                              ?>>S&uuml;dkurve</option>
        <option value="haupt" <?php
                              if (in_array("haupt", $Sektion)) {
                                echo " selected=\"selected\"";
                              }
                              ?>>Haupttrib&uuml;ne</option>
        <option value="gegen" <?php
                              if (in_array("gegen", $Sektion)) {
                                echo " selected=\"selected\"";
                              }
                              ?>>Gegentrib&uuml;ne</option>
      </select><br />
      Kommentare/Anmerkungen
      <textarea cols="70" rows="10" name="Kommentare"><?php
                                                      echo htmlspecialchars($Kommentare);
                                                      ?></textarea><br />
      <input type="checkbox" name="AGB" value="ok" <?php
                                                    if ($AGB != "") {
                                                      echo "checked=\"checked\" ";
                                                    }
                                                    ?> />
      Ich akzeptiere die AGB.<br />
      <input type="submit" name="Submit" value="Bestellung aufgeben" />
    </form>
  <?php
  }
  class Field{
    protected $name;
    protected $displayName;

    function __construct($name, $displayName) {
      $this->name = $name;
      $this->displayName = $displayName;
    }

    public function getName(){
      return $this->name;
    }

    public function getDisplayName(){
      return $this->displayName;
    }

    public function getValue(){
      return htmlspecialchars($this->isValid() ? $_POST[$this->name] : "");
    }

    public function isValid(&$validationErrors = array()){
      if (!isset($_POST[$this->name])) {
        $validationErrors[] = "Das Feld $this->name ist nicht gesetzt";
        return false;
      }
      if (empty($_POST[$this->name])) {
        $validationErrors[] = "Das Feld $this->name ist leer";
        return false;
      }
      if (!filter_var($_POST[$this->name], 513)) {
        $validationErrors[] = "Die Eingabe vom Feld $this->name ist nicht gültig";
        return false;
      }
      return true;
    }
  }

  class BooleanField extends Field{
    
  }
  class StringField extends Field{
    function __construct($name, $displayName) {
      parent::__construct($name, $displayName);
    }

    public function isValid(&$validationErrors = array()){
      $isValid = parent::isValid($validationErrors);
      if(!$isValid){
        return false;
      }
      if(trim($_POST[$this->name]) == ""){
        $validationErrors[] = "Das Feld $this->name ist leer";
        return false;
      }
      return true;
    }
  }
  ?>
</body>

</html>