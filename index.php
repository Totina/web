<?php

// start session
session_start();

// coding
mb_internal_encoding("UTF-8");

// načítání tříd
function loadClass($name){
    if (preg_match('/Controller/i', $name) == 1)  {
        require("controllers/" . $name . ".php");       // trida obsahuje 'controller' - case insensitive, trida je ve slozce controllers
    }
    elseif(preg_match('/Manager/i', $name) == 1) {
        require("models/" . $name . ".php");            // trida obsahuje 'manager', trida je ve slozce models
    }
}

spl_autoload_register("loadClass");

// Připojení k databázi
DtbManager::connect("127.0.0.1", "root", "", "mvc_db");

// Vytvoření router kontroleru
$router = new RouterController();
$router->execute(array($_SERVER['REQUEST_URI']));
$router->drawView();