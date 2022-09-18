<?php

class Address
{
    private string $street;
    private string $suite;
    private string $zipcode;
    private string $city;

    public function __construct(string $street, string $suite, string $zipcode, string $city)
    {
        $this -> street = $street;
        $this -> suite = $suite;
        $this -> zipcode = $zipcode;
        $this -> city = $city;
    }

    public function getAddressString(): string
    {
        return "{$this -> street}\n{$this -> suite}\n{$this -> zipcode}\n{$this -> city}";
    }

    public function getAddress(): Address
    {
        return $this;
    }
}