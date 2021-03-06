<?php

class ReviewController extends Controller {

    public function execute($params){

        // Kontrola, zda je uživatel přihlášen
        $this->userIsLoggedIn();

        // Hlavička
        $this->header['titulek'] = 'Editor recenze';

        // Vytvoření instance modelu pro práci s recenzemi
        $rm = new ReviewManager();

        // Uživatel
        $um = new UserManager();
        $user = $um->getUser();
        $this->data['user_id'] = $user['user_id'];

        // Příprava review
        $review = array(
            'review_id' => '',
            'article_id' => '',
            'user_id' => $this->data['user_id'],
            'criterium_1' => '',
            'criterium_2' => '',
            'criterium_3' => '',
            'note' =>'',
        );

        // Naplnění daty
        if (!empty($params[0])) {
            $review['article_id'] = $params[0];
        }

        // Recenzi chceme editovat
        if (!empty($params[1])) {
            // Načtení článku k editaci
            $editovanaRecenze = $rm->vratRecenzi($params[1]);

            // Recenze uživateli patří
            if($user['user_id'] == $editovanaRecenze['user_id']) {
                if ($editovanaRecenze) {
                    $review = $editovanaRecenze;
                }
                else {
                    $this->addMessage('Recenze nebyla nalezen');
                }
            }
            else {
                $this->addMessage('Nedostatečná oprávnění.');
                $this->route('prihlaseni');
            }
        }

        // Odeslán formulář
        if ($_POST) {

            // Získání recenze z $_POST
            $klice = array('review_id', 'article_id', 'user_id', 'criterium_1', 'criterium_2', 'criterium_3', 'note');
            $review = array_intersect_key($_POST, array_flip($klice));      // vymění klíče za hodnoty

            // Uložení recenze do DB
            $rm->ulozRecenzi($_POST['review_id'], $review);

            $this->addMessage('Recenze byla úspěšně uložena.');
            $this->route('clanek');
        }

        $this->data['review'] = $review;
        $this->view = 'revieweditor';
    }
}