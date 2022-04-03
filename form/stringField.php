<?php
include_once "field.php";

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
