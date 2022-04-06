<?php
include_once "field.php";

class EmailField extends Field
{
  public function isValid(&$validationErrors = array())
  {
    if (!parent::isValid($validationErrors)) {
      return false;
    }

    if (trim($_POST[$this->name]) == "") {
      $validationErrors[] = "Das Feld '$this->displayName' ist leer";
      return false;
    }

    if (!preg_match('/^[a-zA-Z0-9_\-.]+@[a-zA-Z0-9\-.]+\.[a-zA-Z]{2,6}$/', $_POST[$this->name])) {
      $validationErrors[] = "Das Feld '$this->displayName' enthält keine gültige E-Mail";
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
