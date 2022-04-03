<?php
include_once "field.php";

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
