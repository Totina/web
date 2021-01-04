<?php

class ReviewManager {

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
    public function ulozRecenzi($review){
        DtbManager::insert('reviews', $review);
    }

}