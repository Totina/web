<?php

class AssignmentsController extends Controller{

    public function execute($params){

        // Kontrola, zda je uživatel přihlášen
        $this->userIsLoggedIn();

        // Vytvoření instance modelu pro práci s uživateli, články a recenzemi
        $um = new UserManager();
        $am = new ArticleManager();
        $asm = new AssignmentManager();

        // Vrátí uživatele
        $user = $um->getUser();

        // Naplnění daty
        $this->data['user_id'] = $user['user_id'];
        $this->data['role'] = $user['role'];

        // Vrať všechny recenzenty
        $reviewers = $um->getReviewers();
        $this->data['reviewers'] = $reviewers;

        // Vrať všechny články
        $clanky = $am->vratClanky();
        $this->data['clanky'] = $clanky;

        // Vrať všechny assignments
        $assignments = $asm->vratAssignments();
        $this->data['assignments'] = $assignments;

        // Příprava assignment
        $assignment = array(
            'assignment_id' => '',
            'article_id' => '',
            'user_id' => '',
        );

        // odstraneni assignmentu
        if(!empty($params[1]) && $params[1] == 'odstranit') {
            $this->verifyUser(true);
            $asm->removeAssignment($params[0]);

            $this->addMessage('Přidělení bylo odstraněno.');
            $this->route('assignments');
        }

        // Odeslán formulář
        if ($_POST) {

            // Získání recenze z $_POST
            $klice = array('assignment_id', 'article_id', 'user_id');
            $assignment = array_intersect_key($_POST, array_flip($klice));      // vymění klíče za hodnoty

            // Uložení recenze do DB
            $asm->ulozAssignment($assignment);

            $this->addMessage('Přidělení bylo úspěšně uloženo.');
            $this->route('assignments');
        }

        // Pohled
        $this->view = 'assignments';
    }

}