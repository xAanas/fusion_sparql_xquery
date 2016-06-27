<?php

include_once '../../lib/frequence_des_mots.php';
include_once '../opencalais/listdir.php';
include_once '../../lib/gettriplets.php';
include_once '../../lib/merge_filter.php';
include_once '../../lib/arc/ARC2.php';
include '../../config/config.php';

//l'accé database des triplets triplets_db
$store = _config('triplets_db');

$link = mysql_connect("localhost", "root", "")
        or die("Impossible de se connecter : " . mysql_error());
allscorefiles($store);

//function allscorefiles($store) {
//    $dir = new listDirectory('../../data/xmldocs');
//    $cont = $dir->listdir();
//
//    foreach ($cont as $file) {
//        if (!is_dir($file)) {
//            $tab1 = array();
//            $tab1 = gettriplets($tab1, $file, $store);
//            $score = 0;
//            for ($index = 0; $index < count($tab1); $index + 3) {
//                foreach ($cont as $file2) {
//                    if ($file != $file2 && !is_dir($file2)) {
//                        $tab2 = array();
//                        $tab2 = gettriplets($tab2, $file2, $store);
//                        var_dump($tab2);exit();
//                        for ($index1 = 0; $index1 < count($tab2); $index1 + 3) {
//                            if ($tab1[$index]['o'] == $tab2[$index1]['o'] && $tab1[$index + 2]['o'] == $tab2[$index1 + 2]['o'])
//                                $score++;
//                        }
//                    }
//                }
//            }
//        }
//    }
//}
function allscorefiles($store) {
    $dir = new listDirectory('../../data/xmldocs');
    $cont = $dir->listdir();

  //  foreach ($cont as $file) {
  //      if (!is_dir($file)) {
            $o = '
    PREFIX ent: <http://s.opencalais.com/1/type/em/e/>
    PREFIX t: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
    PREFIX evn: <http://s.opencalais.com/1/type/em/r/> 
    PREFIX pred: <http://s.opencalais.com/1/pred/>
    SELECT  ?o ?oo WHERE {
            ?s1 pred:relationsubject ?s.
            ?s1 pred:relationobject ?o.
            ?o pred:relationsubject ?oo.
            ?s1 pred:verb ?v
            FILTER regex(?s, "1.xml","i")
    }';
                        $s = '
    PREFIX ent: <http://s.opencalais.com/1/type/em/e/>
    PREFIX t: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
    PREFIX evn: <http://s.opencalais.com/1/type/em/r/> 
    PREFIX pred: <http://s.opencalais.com/1/pred/>
    SELECT  ?o ?oo WHERE {
            ?s1 pred:relationsubject ?s.
            ?s1 pred:relationobject ?o.
            ?o pred:relationobject ?oo.
            ?s1 pred:verb ?v
            FILTER regex(?s, "1.xml","i")
    }';
            $rows1 = $store->query($s, 'rows');
            $rows2 = $store->query($o, 'rows');
            var_dump($rows1);
            var_dump($rows2);
//            exit();
//            foreach ($cont as $file2) {
//                if ($file != $file2 && !is_dir($file2)) {
//                    // settopic($file, $file2);
//                }
//            }
    //    }
   // }
}

function settriplets($tab1, $tab2) {


    $score = 0;

    if ($tab1[$index]['o'] == $tab2[$index1]['o'] && $tab1[$index + 2]['o'] == $tab2[$index1 + 2]['o'])
        $score++;


    //var_dump($score);
//    if ($score > 0) {
//        $fileContents = file_get_contents('../../data/xmldocs/' . $file1);
//        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
//        $fileContents = trim(str_replace('"', "'", $fileContents));
//        $resultXml = simplexml_load_string($fileContents);
//        $date1 = $resultXml->contentMeta->contentModified;
//        $fileContents = file_get_contents('../../data/xmldocs/' . $file2);
//        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
//        $fileContents = trim(str_replace('"', "'", $fileContents));
//        $resultXml = simplexml_load_string($fileContents);
//        $date2 = $resultXml->contentMeta->contentModified;
//        
//        $fetchbyfilename = "select * from triplets_db.storylink_triplets where file1='$file1' and file2='$file2'";
//        $fetchbyfilenameInvers = "select * from triplets_db.storylink_triplets where file1='$file2' and file2='$file1'";
//        $res = mysql_query($fetchbyfilename);
//        $res2 = mysql_query($fetchbyfilenameInvers);
//        if (mysql_fetch_array($res) == false && mysql_fetch_array($res2) == false) {
//            $insertscorefiles = "insert into triplets_db.storylink_triplets (file1,file2,triplets,datefile1,datefile2) value('$file1','$file2',$score,$date1,$date2)";
//            $r = mysql_query($insertscorefiles);
//        } else {
//            $updatescorefiles = "update triplets_db.storylink_triplets set triplets=$score where file1='$file1' and file2='$file2'";
//            $r = mysql_query($updatescorefiles);
//        }
//    }
}

?>
