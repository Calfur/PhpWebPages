<html>

<head>
  <title>Bestellformular</title>
</head>

<body>
  <?php
  //
  // Die Daten werden in Variablen gefüllt:
  //

  $salutation = new RadioSelectField("salutation", "Anrede", [
    [
      "name" => "mister",
      "displayName" => "Herr"
    ],
    [
      "name" => "miss",
      "displayName" => "Frau"
    ]
  ]);
  $prename = new StringField("prename", "Vorname");
  $surname = new StringField("surname", "Nachname");
  $Email = (isset($_POST["Email"]) && !empty($_POST["Email"]) && filter_var($_POST['Email'], 513)) ? $_POST["Email"] : "";
  $amount = new SelectField("amount", "Anzahl Karten", [
    [
      "name" => "1",
      "displayName" => "1"
    ],
    [
      "name" => "2",
      "displayName" => "2"
    ],
    [
      "name" => "3",
      "displayName" => "3"
    ],
    [
      "name" => "4",
      "displayName" => "4"
    ]
  ], false, 1);
  $promoCode = new PasswordField("promoCode", "Promo-Code");
  $section = new SelectField("section", "Gew&uuml;nschte Sektion im Stadion", [
    [
      "name" => "north",
      "displayName" => "Nordkurve"
    ],
    [
      "name" => "south",
      "displayName" => "Südkurve"
    ],
    [
      "name" => "mainStand",
      "displayName" => "Haupttribüne"
    ],
    [
      "name" => "oppositeStand",
      "displayName" => "Gegentribüne"
    ]
  ], true, 4);
  $Kommentare = (isset($_POST["Kommentare"]) && !empty($_POST["Kommentare"]) && filter_var($_POST['Kommentare'], 513)) ? $_POST["Kommentare"] : "";
  $agb = new BooleanField("agb", "AGB");

  $fields = array($salutation, $prename, $surname, $amount, $section, $agb);
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
      displayUserInput($promoCode->getValue(), $promoCode->getDisplayName());
      displayUserInput($amount->getValue(), $amount->getDisplayName());
      displayUserInput($section->getValue(), $section->getDisplayName());
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
      if (!$field->isValid($validationErrors)) {
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
    if (!isset($_POST["Kommentare"]) || empty($_POST["Kommentare"]) || !filter_var($_POST['Kommentare'], 513) || trim($_POST["Kommentare"]) == "") {
      $ok = false;
      $validationErrors[] = "Kommentare";
    }

    return $ok;
  }

  function displayUserInput($input, $inputName)
  {
    if(gettype($input) == "array"){
      $input = implode(", ", $input);
    }
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
      
      <?php
      $promoCode->displayAsFormElement();
      $amount->displayAsFormElement();
      $section->displayAsFormElement();
      ?>

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
    protected string $name;
    protected string $displayName;

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
        $validationErrors[] = "Der Input '$this->displayName' ist nicht ausgefüllt";
        return false;
      }
      if (empty($_POST[$this->name])) {
        $validationErrors[] = "Der Input '$this->displayName' ist leer";
        return false;
      }
      if (!filter_var($_POST[$this->name], 513)) {
        $validationErrors[] = "Die Eingabe vom Input '$this->displayName' ist nicht gültig";
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
      echo "<input 
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
      echo "<input 
          type='text' 
          name='" . $this->name . "' 
          value='" . $this->getValue() . "'/>
        <br />";
    }
  }

  class RadioSelectField extends Field
  {
    protected array $selectables;

    function __construct($name, $displayName, $selectables)
    {
      parent::__construct($name, $displayName);
      $this->selectables = $selectables;
    }

    public function isValid(&$validationErrors = array())
    {
      if (!parent::isValid($validationErrors)) {
        return false;
      }

      if (!in_array($_POST[$this->name], array_column($this->selectables, "name"))) {
        $validationErrors[] = "Ungültiger Wert für " . $this->displayName . " ausgewählt.";
        return false;
      }

      return true;
    }

    public function displayAsFormElement()
    {
      parent::displayLabel();

      foreach ($this->selectables as $selectable) {
        echo "<input 
            type='radio' 
            name='" . $this->name . "' 
            value='" . $selectable["name"] . "'
            " . ($this->getValue() == $selectable["name"] ? "checked" : "") . " />
          " . $selectable["displayName"];
      }

      echo "<br />";
    }
  }

  class SelectField extends Field
  {
    protected array $selectables;
    protected bool $allowMultiple;
    protected int $size;

    function __construct($name, $displayName, $selectables, $allowMultiple, $size)
    {
      parent::__construct($name, $displayName);
      $this->selectables = $selectables;
      if($size == 1){
        array_unshift($this->selectables, [
          "name" => "0",
          "displayName" => "Bitte wählen"
        ]);
      }
      $this->allowMultiple = $allowMultiple;
      $this->size = $size;
    }

    public function getValue()
    {
      return $this->isValid() ? $_POST[$this->name] : array();
    }

    public function isValid(&$validationErrors = array())
    {
      if (!isset($_POST[$this->name])) {
        $validationErrors[] = "Der Input '$this->displayName' ist nicht ausgefüllt";
        return false;
      }
      if (empty($_POST[$this->name])) {
        $validationErrors[] = "Der Input '$this->displayName' ist leer";
        return false;
      }

      if($this->allowMultiple && !is_array($_POST[$this->name])){
        $validationErrors[] = "Ungültige Werte für " . $this->displayName . " ausgewählt.";
        return false;
      }

      return true;
    }

    public function displayAsFormElement()
    {
      parent::displayLabel();

      echo "<select 
          name='" . $this->name . ($this->allowMultiple ? "[]" : "") . "'
          " . ($this->allowMultiple ? "multiple='multiple'" : "") . " 
          size='" . $this->size . "'>";

      foreach ($this->selectables as $selectable) {
        echo "<option value='" . $selectable["name"] . "'
            " . ($this->isSelected($selectable["name"]) ? "selected" : "") . "> 
            " . $selectable["displayName"] .
          "</option>";
      }

      echo "</select><br />";
    }

    private function isSelected($value) : bool
    {
      if($this->allowMultiple)
      {
        return in_array($value, $this->getValue());
      } else
      {
        return $value == $this->getValue();
      }      
    }
  }

  // class ParagraphField extends Field
  // {

  // }

  // class EmailField extends Field
  // {

  // }

  class PasswordField extends Field
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
      echo "<input 
          type='password' 
          name='" . $this->name . "' 
          value='" . $this->getValue() . "'/>
        <br />";
    }
  }
  ?>
</body>

</html>