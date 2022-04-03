<?php
include_once "fields/field.php";
include_once "fields/radioSelectField.php";
include_once "fields/stringField.php";
include_once "fields/emailField.php";
include_once "fields/selectField.php";
include_once "fields/passwordField.php";
include_once "fields/paragraphField.php";
include_once "fields/booleanField.php";

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