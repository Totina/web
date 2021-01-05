<?php

class ArticleManager {

    // Vrátí článek podle jeho URL
    public function vratClanek($url){
        return DtbManager::dotazRadka('
            SELECT `clanky_id`, `titulek`, `obsah`, `url`, `popisek`, `klicova_slova`, `user_id`, `stav`, `file`
            FROM `clanky`
            WHERE `url` = ?
        ', array($url));
    }

    // Vrátí seznam článků v databázi
    public function vratClanky(){
        return DtbManager::dotazVsechnyRadky('
            SELECT `clanky_id`, `titulek`, `url`, `popisek`, `klicova_slova`, `user_id`, `stav`, `file`
            FROM `clanky`
            ORDER BY `clanky_id` DESC
        ');
    }

    // Vrátí seznam článků přihlášeného autora
    public function vratMojeClanky($user_id){
        return DtbManager::dotazVsechnyRadky('
            SELECT `clanky_id`, `titulek`, `url`, `popisek`, `klicova_slova`, `user_id`, `stav`, `file`
            FROM `clanky`
            WHERE `user_id` = ?
            ORDER BY `clanky_id` DESC
        ', array($user_id));
    }

    // Vrátí jméno autora článku podle jeho URL
    public function getAuthor($user_id){
        return DtbManager::dotazRadka('
            SELECT `jmeno`
            FROM `users`
            WHERE `user_id` = ?
        ', array($user_id));
    }

    // Uloží článek do databáze, pokud článek už existuje, přesměruje na edit
    public function ulozClanek($id, $clanek){
        if(!$id) {
            DtbManager::insert('clanky', $clanek);
        }
        else {
            $this->edit($id, $clanek);
        }
    }

    // Schválí článek
    public function schvalClanek($url){
        DtbManager::dotaz('
        UPDATE clanky
        SET stav = 1
        WHERE url = ?;
    ', array($url));
    }

    // Odmitne článek
    public function odmitniClanek($url){
        DtbManager::dotaz('
        UPDATE clanky
        SET stav = 2
        WHERE url = ?;
    ', array($url));
    }

    // Odstraní článek z databáze
    public function odstranClanek($url){
        DtbManager::dotaz('
        DELETE FROM clanky
        WHERE url = ?
    ', array($url));
    }

    // Edituje clanek
    public function edit($id, $clanek){
        DtbManager::dotaz('
        UPDATE clanky
        SET titulek = ?,
            obsah = ?,
            url = ?,
            popisek = ?,
            klicova_slova = ?,
            file = ?
        WHERE clanky_id = ?
    ', array($clanek['titulek'], $clanek['obsah'], $clanek['url'], $clanek['popisek'], $clanek['klicova_slova'],$clanek['file'], $id));
    }

}