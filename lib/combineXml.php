<?php

$fileContents = file_get_contents('../data/result.xml');
$fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
$fileContents = trim(str_replace('"', "'", $fileContents));
$resultXml = simplexml_load_string($fileContents);
$perfect_xml = '<?xml version="1.0" encoding="utf-8"?><ressources>';
$ressources = $resultXml->ressource;
$noeudobjbackup = '';
$noeudWordobjbackup = '';
//boucle de file xml
for ($index = 0; $index < count($ressources); $index++) {
    $linkDraw = '';

    $trouve = false;
    //boucle du table des triplets
    $offset = 0;
    foreach ($row1et2 as $row) {
        if ($row['s'] == $ressources[$index]->attributes()->id) {

            for ($lnk = 0; $lnk < count($ressources[$index]->link); $lnk++) {
                $linkDraw = $linkDraw . '<link name="' . $ressources[$index]->link[$lnk]->attributes()->name . '">';
                $rsrcDraw = '';
                for ($rsrc = 0; $rsrc < count($ressources[$index]->link[$lnk]->ressource); $rsrc++) {
                    $rsrcDraw = $rsrcDraw . '<ressource id="' . $ressources[$index]->link[$lnk]->ressource[$rsrc]->attributes()->id . '" type="' . $ressources[$index]->link[$lnk]->ressource[$rsrc]->attributes()->type . '"/>';
                }
                $linkDraw = $linkDraw . $rsrcDraw;
                $linkDraw = $linkDraw . '</link>';
            }
            //draw link of row verbe and ressource of object triplet
            $linkextentionDraw = '';
            $rsrcextentionDraw = '';
            $linkextentionDraw = $linkextentionDraw . '<link name="' . $row['v'] . '">';
            $rsrcextentionDraw = $rsrcextentionDraw . '<ressource id="' . $row['o'] . '" type="image"/>';
            $linkextentionDraw = $linkextentionDraw . $rsrcextentionDraw;
            $linkextentionDraw = $linkextentionDraw . '</link>';
            $linkDraw = $linkDraw . $linkextentionDraw;
            //end drawing
            $ressourceDraw = '<ressource id="' . $ressources[$index]->attributes()->id . '" type="' . $ressources[$index]->attributes()->type . '" parent="' . $ressources[$index]->attributes()->parent . '">';
            $ressourceDraw = $ressourceDraw . $linkDraw;
            $ressourceDraw = $ressourceDraw . '</ressource>';
            $perfect_xml = $perfect_xml . $ressourceDraw;
            array_splice($row1et2, $offset, 1);
            
            $linkObjDraw = '';
            $exist = -1;
            $offset_tow = 0;
            foreach ($row1et2 as $rows) {
                if ($row['o'] == $rows['o']) {

                    $linkObjDraw = $linkObjDraw . '<link name="' . $rows['v'] . '">';
                    $rsrcObjDraw = '';
                    $rsrcObjDraw = $rsrcObjDraw . '<ressource id="' . $rows['s'] . '" type="image"/>';
                    $linkObjDraw = $linkObjDraw . $rsrcObjDraw;
                    $linkObjDraw = $linkObjDraw . '</link>';
                    $exist =$offset_tow; 
                    
                    //break;
                }
                if ($row['o'] == $rows['s']) {

                    $linkObjDraw = $linkObjDraw . '<link name="' . $rows['v'] . '">';
                    $rsrcObjDraw = '';
                    $rsrcObjDraw = $rsrcObjDraw . '<ressource id="' . $rows['o'] . '" type="image"/>';
                    $linkObjDraw = $linkObjDraw . $rsrcObjDraw;
                    $linkObjDraw = $linkObjDraw . '</link>';
                    $exist = $offset_tow;
                    //break;
                }
                $offset_tow++;
            }
            if ($exist != -1 && $noeudobjbackup != $row['o']) {
                $noeudobjbackup = $row['o'];
                $ressourceObjDraw = '<ressource id="' . $row['o'] . '" type="image" parent="' . $row['o'] . '">';
                $ressourceObjDraw = $ressourceObjDraw . $linkObjDraw;
                $ressourceObjDraw = $ressourceObjDraw . '</ressource>';
                $perfect_xml = $perfect_xml . $ressourceObjDraw;
                //supprimer une ligne de $row1et2 et incr
                array_splice($row1et2, $exist, 1);
            }
            $trouve = true;
            break;
        }
        $offset++;
    }
    if ($trouve == false) {
        //draw links of files result
        for ($lnk = 0; $lnk < count($ressources[$index]->link); $lnk++) {
            $linkDraw = $linkDraw . '<link name="' . $ressources[$index]->link[$lnk]->attributes()->name . '">';
            $rsrcDraw = '';
            for ($rsrc = 0; $rsrc < count($ressources[$index]->link[$lnk]->ressource); $rsrc++) {
                $rsrcDraw = $rsrcDraw . '<ressource id="' . $ressources[$index]->link[$lnk]->ressource[$rsrc]->attributes()->id . '" type="' . $ressources[$index]->link[$lnk]->ressource[$rsrc]->attributes()->type . '"/>';
            }
            $linkDraw = $linkDraw . $rsrcDraw;
            $linkDraw = $linkDraw . '</link>';
        }
        $ressourceDraw = '<ressource id="' . $ressources[$index]->attributes()->id . '" type="' . $ressources[$index]->attributes()->type . '" parent="' . $ressources[$index]->attributes()->parent . '">';
        $ressourceDraw = $ressourceDraw . $linkDraw;
        $ressourceDraw = $ressourceDraw . '</ressource>';
        $perfect_xml = $perfect_xml . $ressourceDraw;

        //faut supprimer la ligne from $row1et2
        $linkWordDraw = '';
        //draw links of words

        $linkWordDraw = $linkWordDraw . '<link name="' . $row['v'] . '">';
        $rsrcWordDraw = '';
        $rsrcWordDraw = $rsrcWordDraw . '<ressource id="' . $row['o'] . '" type="image"/>';
        $linkWordDraw = $linkWordDraw . $rsrcWordDraw;
        $linkWordDraw = $linkWordDraw . '</link>';
        $ressourceWordDraw = '<ressource id="' . $row['s'] . '" type="image" parent="' . $row['o'] . '">';
        $ressourceWordDraw = $ressourceWordDraw . $linkWordDraw;
        $ressourceWordDraw = $ressourceWordDraw . '</ressource>';
        $perfect_xml = $perfect_xml . $ressourceWordDraw;

        $linkWordObjDraw = '';
        $existt = -1;
        $offset_three=0;
        foreach ($row1et2 as $rows) {
            if ($row['o'] == $rows['o']) {

                $linkWordObjDraw = $linkWordObjDraw . '<link name="' . $rows['v'] . '">';
                $rsrcWordObjDraw = '';
                $rsrcWordObjDraw = $rsrcWordObjDraw . '<ressource id="' . $rows['s'] . '" type="image"/>';
                $linkWordObjDraw = $linkWordObjDraw . $rsrcWordObjDraw;
                $linkWordObjDraw = $linkWordObjDraw . '</link>';
                $existt = $offset_three;
            }
            if ($row['o'] == $rows['s']) {

                $linkWordObjDraw = $linkWordObjDraw . '<link name="' . $rows['v'] . '">';
                $rsrcWordObjDraw = '';
                $rsrcWordObjDraw = $rsrcWordObjDraw . '<ressource id="' . $rows['o'] . '" type="image"/>';
                $linkWordObjDraw = $linkWordObjDraw . $rsrcWordObjDraw;
                $linkWordObjDraw = $linkWordObjDraw . '</link>';
                $existt = $offset_three;
            }
            $offset_three++;
        }
        if ($existt !=-1 && $noeudWordobjbackup != $row['o']) {
            $noeudWordobjbackup = $row['o'];
            $ressourceWordObjDraw = '<ressource id="' . $row['o'] . '" type="image" parent="' . $row['o'] . '">';
            $ressourceWordObjDraw = $ressourceWordObjDraw . $linkWordObjDraw;
            $ressourceWordObjDraw = $ressourceWordObjDraw . '</ressource>';
            $perfect_xml = $perfect_xml . $ressourceWordObjDraw;
            array_splice($row1et2, $existt, 1);
        }
    }
}
//var_dump($row1et2);
$perfect_xml = $perfect_xml . '</ressources>';
$f = fopen('../data/endresult.xml', 'w');
fwrite($f, $perfect_xml);
fclose($f);
?>

