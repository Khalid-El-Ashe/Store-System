<?php

class Person
{
    const MALE = 'male';
    const FEMALE = 'female';
    static $departmanet;

    public $name = 'Web Backend Developer';
    protected $gender;
    private $age;

    public static $country;

    function hello()
    {
        print "Hello World";
    }

    public function __construct() {}

    public function setAge($age)
    {
        $this->age = $age;
        return $this;
    }

    public function setGender($gender)
    {

        $this->gender = $gender;
        return $this;
    }


    public static function setCountry($country)
    {
        Person::$country = $country;
        self::$departmanet = 'IT';
        self::MALE; // you can access constants using self::
        Person::FEMALE; // or using class name
    }
}
