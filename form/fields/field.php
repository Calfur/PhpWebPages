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
