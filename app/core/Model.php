<?php

class Model
{
    protected static $db;

    protected function __construct()
    {
        self::$db = Database::getInstance();
    }

}