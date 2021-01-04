<?php

class PrihlaseniController extends Controller{

    public function execute($params){
        // Vytvoření instance modelu pro práci s uživateli
        $um = new UserManager();

        // Vrať uživatele a přesměruj na jeho účet
        if ($um->getUser()) {
            $this->route('administrace');
        }

        // Hlavička
        $this->header['titulek'] = 'Přihlášení';

        // Odeslán formulář
        if ($_POST) {
            try {
                $um->login($_POST['jmeno'], $_POST['heslo']);

                $this->addMessage('Přihlášení proběhlo úspěšně.');
                $this->route('administrace');
            }
            catch (ErrorManager $chyba) {
                $this->addMessage($chyba->getMessage());
            }
        }

        // Pohled
        $this->view = 'prihlaseni';
    }
}