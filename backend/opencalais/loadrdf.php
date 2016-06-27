<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function getloadrdf() {
//l'accé database des triplets arc2_opclaisdb4_db
    $store = _config('arc2_opclaisdb4_db');
    $rows = $store->query('LOAD <../../data/schemacontent.xml>');
    return $rows;
}

?>