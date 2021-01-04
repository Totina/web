<?php

class ListOfUsersController extends Controller{

    public function execute($params){
        // Vytvoření instance modelu pro práci s uživateli
        $um = new UserManager();

        // Vrátí uživatele
        $user = $um->getUser();

        // Naplnění daty
        $this->data['user_id'] = $user['user_id'];
        $this->data['role'] = $user['role'];

        // Je zadáno user_id uzivatele ke smazání
        if (!empty($params[1]) && $params[1] == 'odstranit') {
            $this->verifyUser(true);
            $um->removeUser($params[0]);

            $this->addMessage('Uživatel byl odstraněn');
            $this->route('listofusers');
        }
        // Je zadáno user_id uzivatele a role, na jakou se má nastavit
        if (!empty($params[1]) && $params[1] == 'role') {
            $this->verifyUser(true);
                if(!empty($params[2]) || $params[2] == 0) {
                    $um->changeRole($params[2], $params[0]);

                    $this->addMessage('Změna role uživatele byla provedena.');
                    $this->route('listofusers');
                }
        }

        // Vrať všechny uživatele
        $users = $um->getUsers();
        $this->data['users'] = $users;

        // Pohled
        $this->view = 'listofusers';
    }

}