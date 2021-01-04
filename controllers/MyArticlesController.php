<?php

class MyArticlesController extends Controller{

    public function execute($params){
        // Vytvoření instanci modelů pro práci s články a uživateli
        $am = new ArticleManager();
        $um = new UserManager();

        // Vrať uživatele
        $user = $um->getUser();

        // Naplň daty
        $this->data['user_id'] = $user['user_id'];
        $this->data['role'] = $user['role'];

        // Vrať články daného uživatele
        $clanky = $am->vratMojeClanky($this->data['user_id']);
        $this->data['clanky'] = $clanky;

        // Pohled
        $this->view = 'articles';
    }

}