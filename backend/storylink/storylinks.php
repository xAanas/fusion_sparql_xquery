<?php

include_once '../../lib/frequence_des_mots.php';
include_once '../opencalais/listdir.php';

$link = mysql_connect("localhost", "root", "")
        or die("Impossible de se connecter : " . mysql_error());
allscorefiles();
function allscorefiles() {
    $dir = new listDirectory('../data/xmldocs');
    $cont = $dir->listdir();
    
    foreach ($cont as $file) {
        foreach ($cont as $file2) {
            if ($file != $file2 && !is_dir($file) && !is_dir($file2)){
                setscore($file, $file2);
            }
        }
    }
}
function key_compare_func($key1, $key2) {
        if ($key1 == $key2)
            return 0;
        else if ($key1 > $key2)
            return 1;
        else
            return -1;
    }

function setscore($file1, $file2) {
    $fileContents = file_get_contents('../data/xmldocs/' . $file1);
    $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
    $fileContents = trim(str_replace('"', "'", $fileContents));
    $resultXml = simplexml_load_string($fileContents);
    $tab1 = array();
    $tab1 = getWordsCount($resultXml->contentMeta->description.''.$resultXml->contentMeta->headline);

    $fileContents = file_get_contents('../data/xmldocs/' . $file2);
    $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
    $fileContents = trim(str_replace('"', "'", $fileContents));
    $resultXml = simplexml_load_string($fileContents);
    $tab2 = array();
    $tab2 = getWordsCount($resultXml->contentMeta->description.''.$resultXml->contentMeta->headline);

    $score = count(array_intersect_ukey(array_map('strtolower', $tab1), array_map('strtolower', $tab2), 'key_compare_func'));

    $fetchbyfilename = "select * from triplets_db.storylink_score where file1='$file1' and file2='$file2'";
    $fetchbyfilenameInvers = "select * from triplets_db.storylink_score where file1='$file2' and file2='$file1'";
    $res = mysql_query($fetchbyfilename);
    $res2 = mysql_query($fetchbyfilenameInvers);
    if (mysql_fetch_array($res) == false && mysql_fetch_array($res2) == false) {
        $insertscorefiles = "insert into triplets_db.storylink_score (file1,file2,score) value('$file1','$file2',$score)";
        $r = mysql_query($insertscorefiles);
    } else {
        $updatescorefiles = "update triplets_db.storylink_score set score=$score where file1='$file1' and file2='$file2'";
        $r = mysql_query($updatescorefiles);
    }
}

?>
