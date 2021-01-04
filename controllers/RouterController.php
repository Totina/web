<?php

class RouterController extends Controller{

    public $controller;

    // Složka s kontrolery
    public $controllersFoldername = 'controllers/';

    public function execute($params){
        // Parse url
        $parsedURL = $this->parseURLAddress($params[0]);     //na 0 indexu URL adresa

        // Adresa je prázdná - úvodní stránka
        if (empty($parsedURL[0])) {
            $this->route('home');
        }

        // Array_shift získá první parametr z pole a odstraní jej
        $controllerName = $this->addressToController(array_shift($parsedURL)) . 'Controller';

        // Jmeno souboru
        $filename = $this->controllersFoldername . $controllerName . '.php';

        // Pokud soubor existuje, přejdeme tam, jinak chybová stránka
        if (file_exists($filename)) {
            $this->controller = new $controllerName;
        }
        else {
            $this->route('error');
        }

        // Voláme metodu kontroleru execute
        $this->controller->execute($parsedURL);

        // Naplnění daty
        $this->data['titulek'] = $this->controller->header['titulek'];
        $this->data['popis'] = $this->controller->header['popis'];
        $this->data['klicova_slova'] = $this->controller->header['klicova_slova'];
        $this->data['zpravy'] = $this->getMessages();

        // Voláme základni view
        $this->view = 'base';
    }

    // Převede URL adresu na název potřebného kontroleru
    private function addressToController($url){
        $controllerName = str_replace('-', ' ', $url);                  // místo pomlček mezery
        $controllerName = ucwords($controllerName);                                  // zvětšení prvních písmen ve slovech
        $controllerName = str_replace(' ', '', $controllerName);        // odtraníme mezery

        return $controllerName;
    }

    // Parsování URL adresy
    private function parseURLAddress($url){
        $newURL = parse_url($url);
        $newURL['path'] = ltrim($newURL['path'], "/");      // odstranime lomitka

        $path = explode('/', $newURL['path']);
        return $path;
    }

}