<?php

class AdministraceController extends Controller{

    public function execute($params){
        // Do administrace mají přístup jen přihlášení uživatelé
        $this->userIsLoggedIn();

        // Hlavička stránky
        $this->header['titulek'] = 'Přihlášení';

        // Získání dat o přihlášeném uživateli
        $um = new UserManager();

        // Odhlášení uživatele
        if (!empty($params[0]) && $params[0] == 'odhlasit') {
            $um->logout();
            $this->route('prihlaseni');
        }

        // Získání uživatele
        $user = $um->getUser();

        // Naplnění daty
        $this->data['user_id'] = $user['user_id'];
        $this->data['jmeno'] = $user['jmeno'];
        $this->data['role'] = $user['role'];

        // Pohled
        $this->view = 'myaccount';
    }
}