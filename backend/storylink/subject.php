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
                settopic($file, $file2);
            }
        }
    }
}

function settopic($file1, $file2) {
    
    $fileContents = file_get_contents('../data/xmldocs/' . $file1);
    $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
    $fileContents = trim(str_replace('"', "'", $fileContents));
    $resultXml = simplexml_load_string($fileContents);
    $tab1=array();
    $i=0;
    for ($index = 0; $index < count($resultXml->contentMeta->subject); $index++) {
        if($resultXml->contentMeta->subject[$index]->attributes()->type=='cpnat:person' || $resultXml->contentMeta->subject[$index]->attributes()->type=='cpnat:poi'){
            $tab1[$i++]=$resultXml->contentMeta->subject[$index]->name;
        }
    }
    
    $fileContents = file_get_contents('../data/xmldocs/' . $file2);
    $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
    $fileContents = trim(str_replace('"', "'", $fileContents));
    $resultXml = simplexml_load_string($fileContents);
    $tab2=array();
    $i=0;
    for ($index = 0; $index < count($resultXml->contentMeta->subject); $index++) {
        if($resultXml->contentMeta->subject[$index]->attributes()->type=='cpnat:person' || $resultXml->contentMeta->subject[$index]->attributes()->type=='cpnat:poi'){
            $tab2[$i++]=$resultXml->contentMeta->subject[$index]->name;
        }
    }
    $score = count(array_intersect(array_map('strtolower', $tab1),array_map('strtolower', $tab2)));

    $fetchbyfilename = "select * from triplets_db.storylink_subject where file1='$file1' and file2='$file2'";
    $fetchbyfilenameInvers = "select * from triplets_db.storylink_subject where file1='$file2' and file2='$file1'";
    $res = mysql_query($fetchbyfilename);
    $res2 = mysql_query($fetchbyfilenameInvers);
    if (mysql_fetch_array($res) == false && mysql_fetch_array($res2) == false) {
        $insertscorefiles = "insert into triplets_db.storylink_subject (file1,file2,subject) value('$file1','$file2',$score)";
        $r = mysql_query($insertscorefiles);
    } else {
        $updatescorefiles = "update triplets_db.storylink_subject set subject=$score where file1='$file1' and file2='$file2'";
        $r = mysql_query($updatescorefiles);
    }
}

?>
