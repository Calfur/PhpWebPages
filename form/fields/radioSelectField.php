<?php
include_once "field.php";

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
