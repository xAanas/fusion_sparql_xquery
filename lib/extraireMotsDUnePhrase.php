<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function extraireMotsDUnePhrase($phrase) {
    /* caractères que l'on va remplacer (tout ce qui sépare les mots, en fait) */
    $towcote = '"';
    $aremplacer = array("#", ",", ".", ";", ":", "!", "?", "(", ")", "[", "]", "{", "}", "$towcote", "'", " ");
    /* ... on va les remplacer par un espace, il ny aura donc plus dans $phrase que des mots et des espaces */
    $enremplacement = " ";

    /* on fait le remplacement (comme dit ci-avant), puis on supprime les espaces de début et de fin de chaîne (trim) */
    $sansponctuation = trim(str_replace($aremplacer, $enremplacement, $phrase));

    /* on coupe la chaîne en fonction dun séparateur, et chaque élément est une valeur d'un tableau */
    $separateur = "[ ]+"; // 1 ou plusieurs espaces
    $mots = @split($separateur, $sansponctuation);

    return $mots;
}
function extraireDeuxMotsDUnePhrase($param) {
    $tab2words=array();
    for ($index = 0; $index < count($param); $index++) {
        //$tab2words[$index]=$param[$index];
        if(@$param[$index+1]!='')
            $tab2words[$index]=$param[$index].' '.$param[$index+1];
    }
    return $tab2words;
}
?>