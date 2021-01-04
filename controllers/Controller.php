<?php

// abstraktni trida Controller
abstract class Controller {

    // Data od modelu
    public $data = array();

    // Název view, který se má vykreslit
    public $view = "";

    // Hlavička HTML stránky
    public $header = array('titulek' => '', 'klicova_slova' => '', 'popis' => '');

    // Složka s views
    public $viewsFolderName = "views/";

    // Abstraktni funkce
    abstract function execute($params);

    // Vykresleni pohledu
    public function drawView(){
        if ($this->view) {
            $safedata = $this -> makeSafe($this -> data);       // ochrana proti XSS
            extract($safedata);                                 // rozbalíme bezpečná data
            require($this->viewsFolderName . $this->view . ".phtml");
        }
    }

    // Přesměrování na dané URL
    public function route($url){
        header("Location: /$url");
        header("Connection: close");
        exit;
    }

    // Zabezpečení proti XSS útokum
    // Zavolá funkci htmlspecialchars() na všechny předávané stringy
    private function makeSafe($element){        //  = null
        if (!isset($element)) {                                 // promenna neni inicializovana
            return null;
        }
        elseif (is_string($element)) {                          //element je string
            //return htmlspecialchars($x, ENT_QUOTES);
            return htmlspecialchars($element);
        }
        elseif (is_array($element)) {                           // element je pole
            foreach($element as $key => $value) {
                $element[$key] = $this->makeSafe($value);       // pouzijeme funkci pro vsechny prvky pole
            }
            return $element;
        }
        else                                                    // ostatni datove typy
            return $element;
    }

    // Přidá zprávu do session
    public function addMessage($zprava){
        if (isset($_SESSION['zpravy'])) {
            $_SESSION['zpravy'][] = $zprava;
        }
        else {
            $_SESSION['zpravy'] = array($zprava);
        }
    }

    // Vrátí zprávy uložené v session
    public function getMessages(){
        if (isset($_SESSION['zpravy'])) {
            $zpravy = $_SESSION['zpravy'];
            unset($_SESSION['zpravy']);
            return $zpravy;
        }
        else {
            return array();
        }
    }

    // Nepovinný parametr, kde můžeme určit, zda uživatel musí být i administrátor
    public function verifyUser($admin = false){
        $um = new UserManager();
        $user = $um->getUser();

        if (!$user || ($admin && !$user['admin'])) {
            $this->addMessage('Nedostatečná oprávnění.');
            $this->route('prihlaseni');
        }
    }

    // Vraci, zda je uživatel přihlášen
    public function userIsLoggedIn(){
        $um = new UserManager();
        $user = $um->getUser();

        if (!$user) {
            $this->addMessage('Tato stránka není dostupná pro nepřihlášené uživatele. Přihlaste se nebo si založte účet.');
            $this->route('prihlaseni');
        }
    }


}


