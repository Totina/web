<?php

class ErrorController extends Controller{

    public function execute($params){
        // Hlavička
        header("HTTP/1.0 404 Not Found");
        $this->header['titulek'] = 'Chyba 404';
        // Pohled
        $this->view = 'error';
    }
}