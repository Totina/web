<?php

class EditorController extends Controller {

    public function execute($params){

        // Kontrola, zda je uživatel přihlášen
        $this->userIsLoggedIn();

        // Hlavička stránky
        $this->header['titulek'] = 'Editor článku';

        // Vytvoření instance modelu pro práci s články
        $am = new ArticleManager();

        // Uživatel
        $um = new UserManager();
        $user = $um->getUser();
        $this->data['user_id'] = $user['user_id'];

        // Složka pro uložení souboru
        $target_dir = "uploads";

        // Příprava článku
        $clanek = array(
            'clanky_id' => '',
            'titulek' => '',
            'obsah' => '',
            'url' => '',
            'popisek' => '',
            'klicova_slova' => '',
            'user_id' => $this->data['user_id'],
            'stav' =>'',
            'file' => '',
        );

        // Odeslán formulář
        if ($_POST) {

            // Nahrání článku
            $pname = $_FILES["fileToUpload"]["name"];
            $tname = $_FILES["fileToUpload"]["tmp_name"];
            $fileType = strtolower(pathinfo($pname,PATHINFO_EXTENSION));
            $uploadOK = 1;

            // Kontrola zda již neexistuje soubor se stejným jménem, pokud ano, změníme jméno
            if (file_exists($target_dir.'/'.$pname)) {
                $pname = $pname.rand(1000,10000);
            }

            // Soubor je formátu PDF
            if($fileType != "pdf") {
                $this->addMessage('Soubor musi byt typu PDF.');
                $uploadOK = 0;
            }

            // Soubor se podařilo nahrát
            if ($uploadOK && move_uploaded_file($tname, $target_dir.'/'.$pname)) {
                $this->addMessage('Soubor byl uložen.');

                // Získání článku z $_POST
                $klice = array('titulek', 'obsah', 'url', 'popisek', 'klicova_slova', 'user_id', 'stav');
                $clanek = array_intersect_key($_POST, array_flip($klice));      // vyměni kliče za hodnoty
                $clanek['file'] = $pname;

                // Uložení článku do DB
                $am->ulozClanek($clanek);

                $this->addMessage('Článek byl úspěšně uložen.');
                $this->route('clanek/' . $clanek['url']);

            } else {
                $this->addMessage('Článek se nepodařilo uložit.');
            }

        }

        $this->data['clanek'] = $clanek;
        $this->view = 'editor';
    }
}