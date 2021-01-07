<?php

class AdministraceController extends Controller{

    public function execute($params){
        // Kontrola, že je uživatel přihlášen
        $this->userIsLoggedIn();

        // Hlavička
        $this->header['titulek'] = 'Přihlášení';

        // Založení modelu pro práci s uživatelem
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