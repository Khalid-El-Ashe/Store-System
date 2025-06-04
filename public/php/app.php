<?php
include 'Person.php'; // this is to import the class

$person = new Person();
Person::$country = 'Palestine';
echo "Hello " . Person::$country . " you have a Profisional {$person->name}";
