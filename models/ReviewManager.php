<?php

class ReviewManager {

    // Vrátí recenzi podle jejiho id
    public function vratRecenzi($id){
        return DtbManager::dotazRadka('
            SELECT `review_id`, `article_id`, `user_id`, `criterium_1`, `criterium_2`, `criterium_3`, `note`
            FROM `reviews`
            WHERE `review_id` = ?
        ', array($id));
    }

    // Vrátí vsechny recenze k danému článku
    public function vratRecenzeClanku($clanky_id){
        return DtbManager::dotazVsechnyRadky('
            SELECT `review_id`, `article_id`, `user_id`, `criterium_1`, `criterium_2`, `criterium_3`, `note`
            FROM `reviews`
            WHERE `article_id` = ?
            ORDER BY `review_id` DESC
        ', array($clanky_id));
    }

    // Uloží recenzi do databáze
    public function ulozRecenzi($id, $review){
        if(!$id) {
            DtbManager::insert('reviews', $review);
        }
        else {
            $this->edit($id, $review);
        }
    }

    // Edituje recenzi
    public function edit($id, $review){
        DtbManager::dotaz('
        UPDATE reviews
        SET criterium_1 = ?,
            criterium_2 = ?,
            criterium_3 = ?,
            note = ?
        WHERE review_id = ?
    ', array($review['criterium_1'], $review['criterium_1'], $review['criterium_1'], $review['note'], $id));
    }

}