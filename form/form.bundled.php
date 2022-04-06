<?php

$fields = array(
  $salutation = new RadioSelectField("salutation", "Anrede", [
    [
      "name" => "mister",
      "displayName" => "Herr"
    ],
    [
      "name" => "miss",
      "displayName" => "Frau"
    ]
  ]),
  $prename = new StringField("prename", "Vorname"),
  $surname = new StringField("surname", "Nachname"),
  $email = new EmailField("email", "E-Mail"),
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
  ], false, 1),
  $promoCode = new PasswordField("promoCode", "Promo-Code"),
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
  ], true, 4),
  $comment = new ParagraphField("comment", "Kommentare"),
  $agb = new BooleanField("agb", "AGB", true)
);

?>

<html>

<head>
  <title>Bestellformular</title>
</head>

<body>
  <?php

  $ok = false;
  $validationErrors = array();

  if (filledFormGotReturned()) {
    processFormContent($fields);
  } else {
    displayForm($fields);
  }

  function filledFormGotReturned()
  {
    return isset($_POST["Submit"]) && !empty($_POST["Submit"]);
  }

  function processFormContent($fields)
  {
    $ok = validateFields($fields, $validationErrors);

    if ($ok) {
      echo "<h1>Bestätigung</h1>";
      echo "<p>Ihre Bestellung für WM-Tickets wurde mit den folgenden Daten angenommen:</p>";

      displayFieldValues($fields);
    } else {
      echo "<p><b>Formular unvollst&auml;ndig</b></p>";

      displayValidationErrors($validationErrors);
      displayForm($fields);
    }
  }

  function displayForm($fields)
  {
    echo "<h1>WM-Ticketservice</h1>";
    echo "<form method='post'>";

    foreach ($fields as $field) {
      $field->displayAsFormElement();
    }

    echo "<input type='submit' name='Submit' value='Bestellung aufgeben' />";
    echo "</form>";
  }

  function validateFields($fields, &$validationErrors)
  {
    $ok = true;

    foreach ($fields as $field) {
      if (!$field->isValid($validationErrors)) {
        $ok = false;
      }
    }

    return $ok;
  }

  function displayFieldValues($fields)
  {
    foreach ($fields as $field) {
      $field->displayValue();
    }
  }

  function displayValidationErrors($validationErrors)
  {
    echo "<ul><li>";
    echo implode("</li><li>", $validationErrors);
    echo "</li></ul>";
  }
  ?>
</body>

</html>

<!-- 
  Field classes 
-->

<?php

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

  public function displayValue()
  {
    echo "<b>" . $this->getDisplayName() . ": </b>" . $this->getDisplayValue() . "<br />";
  }

  protected function getDisplayValue()
  {
    return nl2br(htmlspecialchars($this->getValue()));
  }

  protected function displayLabel()
  {
    echo "<label for='" . $this->name . "'>" . $this->displayName . ": </label>";
  }

  abstract public function displayAsFormElement();
}
?>
<?php
class EmailField extends Field
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

    if (!preg_match('/^[a-zA-Z0-9_\-.]+@[a-zA-Z0-9\-.]+\.[a-zA-Z]{2,6}$/', $_POST[$this->name])) {
      $validationErrors[] = "Das Feld $this->displayName enthält keine gültige E-Mail";
      return false;
    }

    return true;
  }

  public function displayAsFormElement()
  {
    parent::displayLabel();
    echo "<input 
        type='email' 
        name='" . $this->name . "' 
        value='" . $this->getValue() . "'/>
      <br />";
  }
}
?>

<?php
class ParagraphField extends Field
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
    echo "<textarea
          cols='70' 
          rows='10' 
          name='" . $this->name . "'
          >" . $this->getValue() . "</textarea>
        <br />";
  }
}
?>
<?php

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
<?php

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

  protected function getDisplayValue()
  {
    $value = $this->getValue();

    foreach ($this->selectables as $selectable) {
      if ($selectable["name"] == $value) {
        return nl2br(htmlspecialchars($selectable["displayName"]));
      }
    }
  }
}
?>
<?php
class SelectField extends Field
{
  protected array $selectables;
  protected bool $allowMultiple;
  protected int $size;

  function __construct($name, $displayName, $selectables, $allowMultiple = false, $size = 1)
  {
    parent::__construct($name, $displayName);
    $this->selectables = $selectables;
    if ($size == 1) {
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

    if ($this->allowMultiple && !is_array($_POST[$this->name])) {
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

  protected function getDisplayValue()
  {
    $displayValues = array();

    foreach ($this->selectables as $selectable) {
      if ($this->isSelected($selectable["name"])) {
        $displayValues[] = nl2br(htmlspecialchars($selectable["displayName"]));
      }
    }

    return implode(", ", $displayValues);
  }

  private function isSelected($value): bool
  {
    if ($this->allowMultiple) {
      return in_array($value, $this->getValue());
    } else {
      return $value == $this->getValue();
    }
  }
}
?>
<?php

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
?>
<?php

class BooleanField extends Field
{
  protected bool $mustBeTrue;

  function __construct($name, $displayName, $mustBeTrue = false)
  {
    parent::__construct($name, $displayName);
    $this->mustBeTrue = $mustBeTrue;
  }

  public function getValue()
  {
    return isset($_POST[$this->name]);
  }

  public function isValid(&$validationErrors = array())
  {
    if ($this->mustBeTrue && !$this->getValue()) {
      $validationErrors[] = "Der Input '$this->displayName' muss ausgefüllt sein";
      return false;
    }
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
?>