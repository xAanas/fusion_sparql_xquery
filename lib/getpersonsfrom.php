<?php

function get_persons_from_pershash($url) {
//Gets RDF of the person URI
    @$person_html = file_get_contents($url);

    if (!empty($person_html)) {
//Get position of name tag and extract the name
        $strpos_start = strpos($person_html, '<c:name>') + 8;
        $strpos_end = strpos($person_html, '</c:name>');
        $str_name_length = $strpos_end - $strpos_start;
        $extracted_name = trim(substr($person_html, $strpos_start, $str_name_length));

        return $extracted_name;
    }
    return '';
}

function updatenamelink($listsubj, $arc__2val) {
    while ($arraysubj = mysql_fetch_array($listsubj)) {
        if (strstr($arraysubj[2], 'http://d') != false) {
            $name = get_persons_from_pershash("$arraysubj[2].rdf");
            if ($name != '') {
                $updatename = "update arc2_opclaisdb4_db.$arc__2val set val='$name' where id=$arraysubj[0]";
                mysql_query($updatename);
            }
        }
    }
}

?>
