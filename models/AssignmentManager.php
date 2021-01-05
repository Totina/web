<?php

class AssignmentManager {

    // Vrátí vsechny assignments
    public function vratAssignments(){
        return DtbManager::dotazVsechnyRadky('
            SELECT `assignment_id`, `article_id`, `user_id`
            FROM `assignments`
            ORDER BY `assignment_id` DESC
        ');
    }

    // Uloží assignment do databáze
    public function ulozAssignment($assignment){
            DtbManager::insert('assignments', $assignment);
    }

    // Smaže assignment z databáze
    public function removeAssignment($id){
        DtbManager::dotaz('
        DELETE FROM assignments
        WHERE assignment_id = ?
    ', array($id));
    }

}