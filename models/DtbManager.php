<?php

class DtbManager{

    private static $connection;

    // Připojení k databázi
    public static function connect($host, $user, $password, $dtb_name){
        try {
            self::$connection = new PDO("mysql:host=$host;dbname=$dtb_name", $user, $password);

            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES utf8");
        } catch(PDOException $e){
            echo "Connection failed: " . $e->getMessage();
        }
    }

    // Spustí dotaz
    public static function dotaz($dotaz, $params = array()){
        $result = self::$connection->prepare($dotaz);
        $result->execute($params);
        return $result->rowCount();
    }

    // Ziská jeden řádek z databáze
    public static function dotazRadka($dotaz, $params = array()){
        $result = self::$connection->prepare($dotaz);
        $result->execute($params);
        return $result->fetch();
    }

    // Ziská pole řádků
    public static function dotazVsechnyRadky($dotaz, $params = array()){
        $result = self::$connection->prepare($dotaz);
        $result->execute($params);
        return $result->fetchAll();
    }

    // Získá 1. hodnotu v 1. radku
    public static function dotazJednoPole($dotaz, $params = array()){
        $result = self::dotazRadka($dotaz, $params);
        return $result[0];
    }

    // Vloží do tabulky nový řádek
    public static function insert($tabulka, $params = array()) {
        $keys = implode('`, `', array_keys($params));        // implode vytvoří string z jednotlivých elementů pole
        return self::dotaz(
            "INSERT INTO `$tabulka` (`". $keys . "`) 
            VALUES (".str_repeat('?,', sizeOf($params)-1)."?)",
            array_values($params));
    }

    // Vrací ID posledniho vloženého záznamu
    public static function getLastId(){
        return self::$connection->lastInsertId();
    }

}