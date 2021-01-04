<?php

class AboutUsController extends Controller{

    public function execute($params){
        // Hlavička
        header("About Us");
        $this->header['titulek'] = 'O nas';
        // Pohled
        $this->view = 'aboutus';
    }
}