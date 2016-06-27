<?php

/**
 * Comment configurer le magasin de triplets ?
 * Pour utiliser le magasin de triplets natif d'ARC2, il faut créer une base de données MySQL 
 * (ARC2 en utilise des fonctionnalités avancées), ARC2 peut se charger de créer le schéma nécessaire.
 * Pour l'utilisation du magasin, on utilise un tableau de configuration :
 */
include '../lib/getSearchResults.php';
include '../lib/extraireMotsDUnePhrase.php';
include_once '../lib/gettriplets.php';
include_once '../lib/merge_filter.php';
include_once '../lib/arc/ARC2.php';
include_once '../config/config.php';
include_once '../backend/wordnik/wordnik.php';

// extraire les type et les categories lookup
// extraire les mots 
$params = array();
$param = $_REQUEST['key'] . '';
//$synonymparam = $_REQUEST['synonym'] . '';
$keys = explode("+", $param);
foreach ($keys as $valeur) {
    $person = getSearchResults($valeur); //lookup
    if (!is_object($person)) {
        if (strstr($person[0]->Label, $valeur)) {
            $clstype = $person[0]->Classes->Class;
            $idx = 0;
            foreach (extraireMotsDUnePhrase($valeur) as $val) {
                if (strlen($val) >= 3 && $val != 'http' && $val != '//xmlns' && $val != 'com/foaf/0' && $val != 'owl')
                    $params[$idx++] = $val;
            }
            for ($index1 = 0; $index1 < count($clstype); $index1++) {
                $chn = $clstype[$index1]->Label;
                foreach (extraireMotsDUnePhrase($chn) as $valeur) {
                    if (strlen($valeur) >= 3 && $valeur != 'http' && $valeur != '//xmlns' && $valeur != 'com/foaf/0' && $valeur != 'owl')
                        $params[$idx++] = $valeur;
                }
            }
            $clscatego = $person[0]->Categories->Category;
            for ($index = 0; $index < count($clscatego); $index++) {
                $chn = $clscatego[$index]->Label;
                foreach (extraireMotsDUnePhrase($chn) as $valeur) {
                    if (strlen($valeur) >= 3 && $valeur != 'http' && $valeur != '//xmlns' && $valeur != 'com/foaf/0' && $valeur != 'owl')
                        $params[$idx++] = $valeur;
                }
            }
        }
    }
}


//$params2=array_unique($params);
//ensemble des types et categories
$params2 = array_keys(array_flip($params));

//l'accé database des triplets triplets_db
$store = _config('triplets_db');

//consulter la base et extraire les triplet des key word filtrés
$row1et2 = array();
foreach ($keys as $val) {
    $row1et2 = gettriplets($row1et2, $val, $store);
    //extraire les tripelets chaque deux mots par mot clé
    if (empty($row1et2)) {
        foreach (extraireDeuxMotsDUnePhrase(extraireMotsDUnePhrase($val)) as $val1) {
            $row1et2 = gettriplets($row1et2, $val1, $store);
        }
    }
    //extraire les tripelets de chaque mots par mot clé
    if (empty($row1et2)) {
        foreach (extraireMotsDUnePhrase($val) as $val2) {
            if (strlen($val2) > 3) {
                $row1et2 = gettriplets($row1et2, $val2, $store);
            }
        }
    }
    if (isset($_REQUEST['synonym'])) {
        //introduise triplets of wodnik synonym
        $wordsynonym = array();
        foreach (extraireMotsDUnePhrase($val) as $value) {
            $wordsynonym = wordnik($value);
            if ($wordsynonym != null) {
                foreach ($wordsynonym as $syn) {
                    if (strlen($syn) > 3) {
                        $row1et2 = gettriplets($row1et2, $syn, $store);
                    }
                }
            }
        }
    }
}
//recherche des triplets qui se trouve dans les même fichiers .xml que le key word
$addons = array();
foreach ($row1et2 as $val) {
    if (strstr($val['s'], '.xml')) {
        $tripletsfichiers = array();
        $tripletsfichiers = gettriplets($tripletsfichiers, substr($val['s'], 0, -4), $store);
        //var_dump($tripletsfichiers);
        foreach ($tripletsfichiers as $trip) {
            foreach ($row1et2 as $mot) {
                if (strstr($trip['o'], $mot['o']) || strstr($trip['o'], $mot['s'])) {
                    $addons = array_merge($addons, array($trip));
                }
            }
        }
    }
}
//var_dump($row1et2);
//var_dump($addons);
$row1et2 = merge_filter($row1et2, $addons);

//to delete '"' from triplets to not damage balses of result xml file 
for ($index2 = 0; $index2 < count($row1et2); $index2++) {
    if (strstr($row1et2[$index2]['s'], '"') || strstr($row1et2[$index2]['o'], '"') || strstr($row1et2[$index2]['v'], '"')) {
        $row1et2[$index2]['s'] = str_replace('"', "'", $val['s']);
        $row1et2[$index2]['o'] = str_replace('"', "'", $val['o']);
        $row1et2[$index2]['v'] = str_replace('"', "'", $val['v']);
    }
}
//echo affiche_tableau_triplets($row1et2);

if (!empty($row1et2)) {
//building le fichier xml from table f triplets '$row1et2' et le fichier result.xml 
    include_once '../lib/combineXml.php';

//parser le fichier endresults.xml to result.json pour l'affichage des noeuds
    include_once '../lib/xmltojson.php';
} else {
    $fp = fopen('../data/results.json', 'w+');
    fwrite($fp, '');
    fclose($fp);
}
?>
