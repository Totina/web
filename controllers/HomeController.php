<?php

class HomeController extends Controller{

    public function execute($params){
        // Hlavička
        header("Home page");
        $this->header['titulek'] = 'Home';
        // Pohled
        $this->view = 'home';
    }
}