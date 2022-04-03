<?php
include_once "field.php";

class BooleanField extends Field
{
  protected bool $mustBeTrue;

  function __construct($name, $displayName, $mustBeTrue)
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
