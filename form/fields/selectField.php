<?php
include_once "field.php";

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
