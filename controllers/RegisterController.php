<?php

class RegisterController extends Controller{

    public function execute($params){
        // Hlavička
        $this->header['titulek'] = 'Registrace';

        // Odeslán formulář
        if ($_POST) {
            try {
                // Vytvořen model pro práci s uživateli
                $um = new UserManager();

                // Registrace
                $um->register($_POST['jmeno'], $_POST['heslo'], $_POST['heslo_znovu']);

                // Login
                $um->login($_POST['jmeno'], $_POST['heslo']);

                $this->addMessage('Registrace proběhla úspěšně.');
                $this->route('administrace');
            }
            catch (ErrorManager $chyba) {
                $this->addMessage('Při registraci došlo k chybě.');
            }
        }

        // Pohled
        $this->view = 'register';
    }
}