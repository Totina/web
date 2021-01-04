<?php

class KontaktController extends Controller{

    public function execute($params){
        // Hlavička
        header("Kontakt");
        $this->header['titulek'] = 'Kontakt';
        // Pohled
        $this->view = 'kontakt';
    }
}