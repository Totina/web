<?php

class UserManager {

    // Registrace nového uživatele
    public function register($jmeno, $heslo, $hesloZnovu){
        // Hesla se neshodují
        if ($heslo != $hesloZnovu) {
            throw new ErrorManager('Zadaná hesla se neshodují.');
        }

        // Příprava uživatele
        $user = array(
            'jmeno' => $jmeno,
            'heslo' => $this->getHash($heslo),
            'role' => 0,                        // defaultne je přidělena role autora
        );

        // Vložení do databáze
        try {
            DtbManager::insert('users', $user);
        }
        catch (PDOException $chyba) {
            throw new ErrorManager('Při registraci nastala chyba.');
        }
    }

    // Přihlášení uživatele
    public function login($jmeno, $heslo) {
        $uzivatel = DtbManager::dotazRadka('
            SELECT user_id, jmeno, admin, heslo, role
            FROM users
            WHERE jmeno = ?
            ', array($jmeno));

        // Nepodařilo se přihlásit
        if (!$uzivatel || !password_verify($heslo, $uzivatel['heslo'])) {
            throw new ErrorManager('Nepodařilo se přihlásit. Neplatné jméno nebo heslo.');
        }

        $_SESSION['uzivatel'] = $uzivatel;
    }

    // Smazání uživatele z databáze
    public function removeUser($user_id){
        DtbManager::dotaz('
        DELETE FROM users
        WHERE user_id = ?
    ', array($user_id));
    }

    // Odhlášení uživatele
    public function logout(){
        unset($_SESSION['uzivatel']);
    }

    // Vrátí aktuálně přihlášeného uživatele
    public function getUser(){
        if (isset($_SESSION['uzivatel'])) {
            return $_SESSION['uzivatel'];
        }
        return null;
    }

    // Vrátí seznam uživatelů v databázi
    public function getUsers(){
        return DtbManager::dotazVsechnyRadky('
            SELECT `user_id`, `jmeno`, `role`
            FROM `users`
            ORDER BY `user_id` ASC 
        ');
    }

    // Vrátí seznam recenzentů v databázi
    public function getReviewers(){
        return DtbManager::dotazVsechnyRadky('
            SELECT `user_id`, `jmeno`, `role`
            FROM `users`
            WHERE role = 1
            ORDER BY `user_id` ASC 
        ');
    }

    // Změní roli uživatele
    public function changeRole($newrole, $user_id){
        DtbManager::dotaz('
        UPDATE users
        SET role = ?
        WHERE user_id = ?;
    ', array($newrole, $user_id));
    }

    // Vrátí hash hesla
    public function getHash($password){
        return password_hash($password, PASSWORD_DEFAULT);
    }

}