<?php

class ClanekController extends Controller{

    public function execute($params){
        // Vytvoření instance modelů pro práci s články, uživateli a recenzemi
        $am = new ArticleManager();
        $um = new UserManager();
        $rm = new ReviewManager();

        // Složka se soubory
        $fileDirectory = 'uploads/';

        // Získání uživatele
        $user = $um->getUser();

        if ($user) {
            $this->data['admin'] = $user['admin'];
            $this->data['role'] = $user['role'];
            $this->data['user_id'] = $user['user_id'];
        }

        // Odstranění článku
        if (!empty($params[1]) && $params[1] == 'odstranit') {
            $clanek = $am->vratClanek($params[0]);

            // Uživatel je admin, nebo mu článek patří
            if($user['role'] == 2 || $user['user_id'] == $clanek['user_id']) {
                $am->odstranClanek($params[0]);

                $this->addMessage('Článek byl úspěšně odstraněn');
                $this->route('clanek');
            }
            else {
                $this->addMessage('Nedostatečná oprávnění.');
                $this->route('prihlaseni');
            }
        }
        // Schválení článku
        else if(!empty($params[1]) && $params[1] == 'schvalit') {
            $this->verifyUser(true);
            $am->schvalClanek($params[0]);

            $this->addMessage('Článek byl schválen.');
            $this->route('clanek');
        }
        // Odmitnuti článku
        else if(!empty($params[1]) && $params[1] == 'odmitnout') {
            $this->verifyUser(true);
            $am->odmitniClanek($params[0]);

            $this->addMessage('Článek byl odmítnut.');
            $this->route('clanek');
        }
        // Zobrazení článku
        else if (!empty($params[0])) {
            // Získání článku podle URL
            $clanek = $am->vratClanek($params[0]);

            // Článek s danou URL nenalezen, přesměrujeme na error stranku
            if (!$clanek) {
                $this->route('error');
                $this->addMessage('Článek neexistuje');
            }

            // Ziskání autora
            $author = $am->getAuthor($clanek['user_id']);

            if($author) {
                $authorName = $author['jmeno'];
                $this->data['author'] = $authorName;
            }
            else {
                // autor clanku jiz neexistuje
                $this->data['author'] = "neznámý";
            }

            // Hlavička stránky
            $this->header = array(
                'titulek' => $clanek['titulek'],
                'klicova_slova' => $clanek['klicova_slova'],
                'popis' => $clanek['popisek'],
            );

            // Naplnění proměnných pro pohled
            $this->data['titulek'] = $clanek['titulek'];
            $this->data['obsah'] = $clanek['obsah'];
            $this->data['file'] = $fileDirectory.$clanek['file'];
            $this->data['clanky_id'] = $clanek['clanky_id'];

            // Recenze
            $reviews = $rm->vratRecenzeClanku($clanek['clanky_id']);
            $this->data['reviews'] = $reviews;

            // Pohled
            $this->view = 'article';
        }
        // Není zadáno URL článku, vypíšeme všechny články
        else {
            $clanky = $am->vratClanky();
            $this->data['clanky'] = $clanky;
            $this->view = 'articles';
        }
    }
}