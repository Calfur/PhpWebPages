<html>

<head>
  <title>Bestellformular</title>
</head>

<body>
  <?php
  //
  // Die Daten werden in Variable gefüllt:
  //

  $salutation = new SelectField("salutation", "Anrede", ["Herr", "Frau"]);
  $prename = new StringField("prename", "Vorname");
  $surname = new StringField("surname", "Nachname");
  $Email = (isset($_POST["Email"]) && !empty($_POST["Email"]) && filter_var($_POST['Email'], 513)) ? $_POST["Email"] : "";
  $Anzahl = (isset($_POST["Anzahl"]) && !empty($_POST["Anzahl"])) ? $_POST["Anzahl"] : "";
  $Promo = (isset($_POST["Promo"]) && !empty($_POST["Promo"]) && filter_var($_POST['Promo'], 513)) ? $_POST["Promo"] : "";
  $Sektion = (isset($_POST["Sektion"]) && !empty($_POST["Sektion"]) && is_array($_POST["Sektion"])) ? $_POST["Sektion"] : array();
  $Kommentare = (isset($_POST["Kommentare"]) && !empty($_POST["Kommentare"]) && filter_var($_POST['Kommentare'], 513)) ? $_POST["Kommentare"] : "";
  $agb = new BooleanField("agb", "AGB");

  $fields = array($salutation, $prename, $surname, $agb);
  $ok = false;
  $validationErrors = array();

  if (filledFormGotReturned()) {
    $ok = validateFields($fields, $validationErrors);

    if ($ok) {
  ?>
      <h1>Bestätigung</h1>
      <p>Ihre Bestellung für WM-Tickets wurde mit den folgenden Daten angenommen:</p>
    <?php

      displayUserInput($salutation->getValue(), $salutation->getDisplayName());
      displayUserInput($prename->getValue(), $prename->getDisplayName());
      displayUserInput($surname->getValue(), $surname->getDisplayName());
      displayUserInput($Email, "E-Mail");
      displayUserInput($Promo, "Promo");
      displayUserInput($Anzahl, "Anzahl Karten");
      displayUserInput(implode(", ", $Sektion), "Sektion");
      displayUserInput($Kommentare, "Kommentare");
      displayUserInput($agb->getValue(), $agb->getDisplayName());
    } else {
      displayValidationErrors($validationErrors);
    }
  }

  function filledFormGotReturned()
  {
    return isset($_POST["Submit"]) && !empty($_POST["Submit"]);
  }

  function validateFields($fields, &$validationErrors)
  {
    $ok = true;

    foreach ($fields as $field) {
      if ($field->isValid($validationErrors)) {
        $ok = false;
      }
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

    return $ok;
  }

  function displayUserInput($input, $inputName)
  {
    $input = nl2br(htmlspecialchars($input));
    echo "<b>$inputName:</b> $input<br />";
  }

  function displayValidationErrors($validationErrors)
  {
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
      <?php
      $salutation->displayAsFormElement();
      $prename->displayAsFormElement();
      $surname->displayAsFormElement();
      ?>
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
      <?php
      $agb->displayAsFormElement();
      ?>
      <input type="submit" name="Submit" value="Bestellung aufgeben" />
    </form>
  <?php
  }

  abstract class Field
  {
    protected $name;
    protected $displayName;

    function __construct($name, $displayName)
    {
      $this->name = $name;
      $this->displayName = $displayName;
    }

    public function getName()
    {
      return $this->name;
    }

    public function getDisplayName()
    {
      return $this->displayName;
    }

    public function getValue()
    {
      return htmlspecialchars($this->isValid() ? $_POST[$this->name] : "");
    }

    public function isValid(&$validationErrors = array())
    {
      if (!isset($_POST[$this->name])) {
        $validationErrors[] = "Der Input $this->displayName ist nicht ausgefüllt";
        return false;
      }
      if (empty($_POST[$this->name])) {
        $validationErrors[] = "Der Input $this->displayName ist leer";
        return false;
      }
      if (!filter_var($_POST[$this->name], 513)) {
        $validationErrors[] = "Die Eingabe vom Input $this->displayName ist nicht gültig";
        return false;
      }
      return true;
    }

    protected function displayLabel()
    {
      echo "<label for='" . $this->name . "'>" . $this->displayName . ": </label>";
    }

    abstract public function displayAsFormElement();
  }

  class BooleanField extends Field
  {
    public function getValue()
    {
      return isset($_POST[$this->name]);
    }

    public function isValid(&$validationErrors = array())
    {
      // Booleans are always valid
      return true;
    }

    public function displayAsFormElement()
    {
      parent::displayLabel();
      echo  
        "<input 
          type='checkbox' 
          name='" . $this->name . "'
          " . ($this->getValue() ? "checked" : "") . "/>
        <br />";
    }
  }

  class StringField extends Field
  {
    public function isValid(&$validationErrors = array())
    {
      if (!parent::isValid($validationErrors)) {
        return false;
      }

      if (trim($_POST[$this->name]) == "") {
        $validationErrors[] = "Das Feld $this->displayName ist leer";
        return false;
      }

      return true;
    }

    public function displayAsFormElement()
    {
      parent::displayLabel();
      echo
        "<input 
          type='text' 
          name='" . $this->name . "' 
          value='" . $this->getValue() . "'/>
        <br />";
    }
  }

  class SelectField extends Field
  {
    protected $selectableValues;

    function __construct($name, $displayName, $selectableValues)
    {
      parent::__construct($name, $displayName);
      $this->selectableValues = $selectableValues;
    }

    public function isValid(&$validationErrors = array())
    {
      if (!parent::isValid($validationErrors)) {
        return false;
      }

      if (!in_array($_POST[$this->name], $this->selectableValues)) {
        $validationErrors[] = "Ungültiger wert für " . $this->displayName . " ausgewählt.";
        return false;
      }

      return true;
    }

    public function displayAsFormElement()
    {
      parent::displayLabel();

      foreach ($this->selectableValues as $selectableValue) {
        echo 
          "<input 
            type='radio' 
            name='" . $this->name . "' 
            value='" . $selectableValue . "'
            " . ($this->getValue() == $selectableValue ? "checked" : "") . " />
          " . $selectableValue;
      }

      echo "<br />";
    }
  }

  // class MultiselectField extends Field
  // {

  // }

  // class ParagraphField extends Field
  // {

  // }

  // class EmailField extends Field
  // {

  // }

  // class PasswordField extends Field
  // {

  // }
  ?>
</body>

</html>