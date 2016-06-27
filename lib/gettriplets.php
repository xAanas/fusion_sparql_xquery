<?php

function key_compare_func($a, $b) {
    if ($a === $b) {
        return 0;
    }
    return ($a > $b) ? 1 : -1;
}

function gettriplets($row1et2,$val, $store) {
    //$row1et2 = array();
//for ($index2 = 0; $index2 < count($params2); $index2++) {

    //foreach ($param as $val) {
        //foreach (extraireMotsDUnePhrase($valeur) as $val) {

            $q1 = '
    PREFIX ent: <http://s.opencalais.com/1/type/em/e/>
    PREFIX t: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
    PREFIX evn: <http://s.opencalais.com/1/type/em/r/> 
    PREFIX pred: <http://s.opencalais.com/1/pred/>
    SELECT Distinct ?s ?v ?o WHERE {
            ?s1 pred:relationsubject ?s.
            ?s1 pred:relationobject ?o .
            ?s1 pred:verb ?v.
            FILTER regex(?o,"' . $val . '","i")
    }';
            $q2 = '
    PREFIX ent: <http://s.opencalais.com/1/type/em/e/>
    PREFIX t: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
    PREFIX evn: <http://s.opencalais.com/1/type/em/r/> 
    PREFIX pred: <http://s.opencalais.com/1/pred/>
    SELECT distinct ?s ?v ?o WHERE {
            ?s1 pred:relationsubject ?s.
            ?s1 pred:relationobject ?o.
            ?s1 pred:verb ?v.
            FILTER regex(?s, "' . $val . '","i")
    }';
            $rows2 = $store->query($q2, 'rows');
            $rows = $store->query($q1, 'rows');
            //bouclé rows1 avec $row1et2 pour detecter les redandances
            $row1et2 = merge_filter($row1et2, $rows);

            //bouclé rows2 avec $row1et2 pour detecter les redandances
            $row1et2 = merge_filter($row1et2, $rows2);
       // }
    //}

    return $row1et2;
}

function affiche_tableau_triplets($row1et2) {
    $r = '';
    if ($row1et2) {
        $i = 1;
        $r = '<table border=1> 
 <th>N°</th><th>Subject</th><th>Verbe</th><th>Object</th>' . "\n";

        foreach ($row1et2 as $rowsss) {
            $r .= '<tr><td>' . $i++ . '</td><td>' . $rowsss['s'] .
                    '</td><td>' . $rowsss['v'] . '</td>
                 <td>' . $rowsss['o'] . '</td></tr>' . "\n";
        }
        $r .='</table>' . "\n";
    }
    return $r;
}

?>
