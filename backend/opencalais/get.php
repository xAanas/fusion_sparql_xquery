<?php
function lanchsparqlrequest($file) {
    
//l'accé database des triplets arc2_opclaisdb4_db
$store= _config('arc2_opclaisdb4_db');
/**
 * Comment configurer le magasin de triplets ?
 * Pour utiliser le magasin de triplets natif d'ARC2, il faut créer une base de données MySQL 
 * (ARC2 en utilise des fonctionnalités avancées), ARC2 peut se charger de créer le schéma nécessaire.
 * Pour l'utilisation du magasin, on utilise un tableau de configuration :
 */

//$selectsubj="select * from arc2_opclaisdb4_db.arc__s2val";
//$listsubj=mysql_query($selectsubj);
//$arc__2val="arc__s2val";
//updatenamelink($listsubj,$arc__2val);

$selectobj="select * from arc2_opclaisdb4_db.arc__o2val";
$listobj=mysql_query($selectobj);
$arc__2val="arc__o2val";
updatenamelink($listobj,$arc__2val);

$q = 'PREFIX ent: <http://s.opencalais.com/1/type/em/e/>
PREFIX t: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX evn: <http://s.opencalais.com/1/type/em/r/> 
PREFIX pred: <http://s.opencalais.com/1/pred/>
SELECT  distinct  ?s ?v ?o WHERE {
        ?s1 pred:relationsubject ?s .
        ?s1 pred:relationobject ?o .
        ?s1 pred:verb ?v 
}';
$q2 = 'PREFIX ent: <http://s.opencalais.com/1/type/em/e/>
PREFIX t: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX evn: <http://s.opencalais.com/1/type/em/r/> 
PREFIX pred: <http://s.opencalais.com/1/pred/>
SELECT  distinct  ?s ?v ?o  WHERE {
        ?s1 pred:relationsubject ?s .
        ?s1 pred:relationobject ?o_uri.
        ?o_uri pred:name ?o .
        ?s1 pred:verb ?v 
  }';

/**
 * ** relation subject how has a name : [?s1 pred:relationsubject ?s.?s pred:name ?h . ] 
 * ** relation object how has a name with a relation object with a verb : 
 * [?s1 pred:relationsubject ?s.
 * ?s pred:name ?h . 
 * ?s1 pred:relationobject ?d.
 * ?s1 pred:verb ?v .]
 * ** relation subject with relationobject how has a name with a verb : [
 * ?s1 pred:relationsubject ?s.
 * ?s1 pred:relationobject ?d.
 * ?d pred:name ?o .
 * ?s1 pred:verb ?v .
 * ] 
 */
/* 'PREFIX ta: <http://www.owl-ontologies.com/vit2.owl#> SELECT * WHERE { ?s1 ta:HasName ?s.  ?s1 ta:HasVitaminAinIU ?p.}';
 */

$rows = $store->query($q, 'rows');
//$rows2 = $store->query($q2, 'rows');

/* error handling */
if ($errors = $store->getErrors()) {
    error_log("arc2sparql error:\n" . join("\n", $errors));
     exit(10);
}
$link = mysql_connect("localhost", "root", "")
    or die("Impossible de se connecter : " . mysql_error());

$r = '';
if ($rows){

    $i = 1;
    $r = '<table border=1> 
 <th>N°</th><th>Subject</th><th>Verbe</th><th>Object</th>' . "\n";
//***sava file name xxx.xml **//
        $ligrow_subject_filename = "INSERT INTO triplets_db.arc__s2val (misc,val) VALUES (0,'$file')";
        $ligrow_obj_filename = "INSERT INTO triplets_db.arc__o2val (misc,val) VALUES (0,'$file')";          
        mysql_query ($ligrow_subject_filename);$s_file=mysql_insert_id();
        mysql_query ($ligrow_obj_filename);$o_s_file=mysql_insert_id();

        $ligrowtriplet1 = "INSERT INTO triplets_db.arc__triple(s,p,o,o_lang_dt,o_comp,s_type,o_type,misc) VALUES ($s_file,101,$o_s_file,5,'',0,0,2)";
        $ligrowtriplet2 = "INSERT INTO triplets_db.arc__triple(s,p,o,o_lang_dt,o_comp,s_type,o_type,misc) VALUES ($s_file,104,1,5,'',0,0,2)";
        mysql_query ($ligrowtriplet1);
        mysql_query ($ligrowtriplet2);
            
        foreach ($rows as $row) {
        $subject=$row['s'];
        $object=$row['o'];
        $verb=$row['v'];
        
        $ligrow_subject = "INSERT INTO triplets_db.arc__s2val (misc,val) VALUES (0,'$subject')";        
        $ligrow_obj_sub = "INSERT INTO triplets_db.arc__o2val (misc,val) VALUES (0,'$object')"; 
        $ligrow_obj_obj = "INSERT INTO triplets_db.arc__o2val (misc,val) VALUES (0,'$subject')"; 
        $ligrow_obj_verb = "INSERT INTO triplets_db.arc__o2val(misc,val) VALUES (0,'$verb')";
        mysql_query ($ligrow_subject);$s=mysql_insert_id();
        $o_s=mysql_query($ligrow_obj_sub);$o_s=mysql_insert_id();
        $o_v=mysql_query($ligrow_obj_verb);$o_v=mysql_insert_id();
        $o_o=mysql_query($ligrow_obj_obj);$o_o=mysql_insert_id();

        $ligrowtriplet1 = "INSERT INTO triplets_db.arc__triple(s,p,o,o_lang_dt,o_comp,s_type,o_type,misc) VALUES ($s,101,$o_s,5,'',0,0,2)";
        $ligrowtriplet2 = "INSERT INTO triplets_db.arc__triple(s,p,o,o_lang_dt,o_comp,s_type,o_type,misc) VALUES ($s,103,$o_o,5,'',0,0,2)"; 
        $ligrowtriplet3 = "INSERT INTO triplets_db.arc__triple(s,p,o,o_lang_dt,o_comp,s_type,o_type,misc) VALUES ($s,104,$o_v,5,'',0,0,2)"; 
        mysql_query ($ligrowtriplet1);
        mysql_query ($ligrowtriplet2);
        mysql_query ($ligrowtriplet3);
        
 
        $ligrowtriplet3 = "INSERT INTO triplets_db.arc__triple(s,p,o,o_lang_dt,o_comp,s_type,o_type,misc) VALUES ($s_file,103,$o_s,5,'',0,0,2)"; 
        $ligrowtriplet4 = "INSERT INTO triplets_db.arc__triple(s,p,o,o_lang_dt,o_comp,s_type,o_type,misc) VALUES ($s_file,103,$o_o,5,'',0,0,2)"; 
        $ligrowtriplet5 = "INSERT INTO triplets_db.arc__triple(s,p,o,o_lang_dt,o_comp,s_type,o_type,misc) VALUES ($s_file,103,$o_v,5,'',0,0,2)"; 

        mysql_query ($ligrowtriplet3);
        mysql_query ($ligrowtriplet4);
        mysql_query ($ligrowtriplet5);
        
        $r .= '<tr><td>' . $i++ . '</td><td>' . $row['s'] .
                '</td><td>' . $row['v'] . '</td>
                 <td>' . $row['o'] . '</td></tr>' . "\n";
         $r .= '<tr><td>' . $i++ . '</td><td>' . $file .
                '</td><td>Contient</td>
                 <td>' . $row['s'] . '</td></tr>' . "\n";
          $r .= '<tr><td>' . $i++ . '</td><td>' . $file .
                '</td><td>Contient</td>
                 <td>' . $row['v'] . '</td></tr>' . "\n";
           $r .= '<tr><td>' . $i++ . '</td><td>' . $file .
                '</td><td>Contient</td>
                 <td>' . $row['o'] . '</td></tr>' . "\n";
    }
//    foreach ($rows2 as $row) {
//        $subject=$row['s'];
//        $object=$row['o'];
//        $verb=$row['v'];
//        $ligrow_subject = "INSERT INTO triplets_db.arc__s2val (misc,val) VALUES (0,'$subject')";
//        $ligrow_obj_sub = "INSERT INTO triplets_db.arc__o2val (misc,val) VALUES (0,'$object')"; 
//        $ligrow_obj_obj = "INSERT INTO triplets_db.arc__o2val (misc,val) VALUES (0,'$subject')"; 
//        $ligrow_obj_verb = "INSERT INTO triplets_db.arc__o2val(misc,val) VALUES (0,'$verb')";
//        mysql_query ($ligrow_subject);$s=mysql_insert_id();
//        $o_s=mysql_query ($ligrow_obj_sub);$o_s=mysql_insert_id();
//        $o_v=mysql_query ($ligrow_obj_verb);$o_v=mysql_insert_id();
//        $o_o=mysql_query ($ligrow_obj_obj);$o_o=mysql_insert_id();
//
//        $ligrowtriplet1 = "INSERT INTO triplets_db.arc__triple(s,p,o,o_lang_dt,o_comp,s_type,o_type,misc) VALUES ($s,101,$o_s,5,'',0,2,0)";
//        $ligrowtriplet2 = "INSERT INTO triplets_db.arc__triple(s,p,o,o_lang_dt,o_comp,s_type,o_type,misc) VALUES ($s,103,$o_o,5,'',0,2,0)"; 
//        $ligrowtriplet3 = "INSERT INTO triplets_db.arc__triple(s,p,o,o_lang_dt,o_comp,s_type,o_type,misc) VALUES ($s,104,$o_v,5,'',0,2,0)"; 
//        mysql_query ($ligrowtriplet1);
//        mysql_query ($ligrowtriplet2);
//        mysql_query ($ligrowtriplet3);
//        
//        $ligrowtriplet3 = "INSERT INTO triplets_db.arc__triple(s,p,o,o_lang_dt,o_comp,s_type,o_type,misc) VALUES ($s_file,103,$o_s,5,'',0,0,2)"; 
//        $ligrowtriplet4 = "INSERT INTO triplets_db.arc__triple(s,p,o,o_lang_dt,o_comp,s_type,o_type,misc) VALUES ($s_file,103,$o_o,5,'',0,0,2)"; 
//        $ligrowtriplet5 = "INSERT INTO triplets_db.arc__triple(s,p,o,o_lang_dt,o_comp,s_type,o_type,misc) VALUES ($s_file,103,$o_v,5,'',0,0,2)"; 
//
//        mysql_query ($ligrowtriplet3);
//        mysql_query ($ligrowtriplet4);
//        mysql_query ($ligrowtriplet5);
//        $r .= '<tr><td>' . $i++ . '</td><td>' . $row['s'] .
//                '</td><td>' . $row['v'] . '</td>
//                 <td>' . $row['o'] . '</td></tr>' . "\n";
//         $r .= '<tr><td>' . $i++ . '</td><td>' . $file .
//                '</td><td>Contient</td>
//                 <td>' . $row['s'] . '</td></tr>' . "\n";
//          $r .= '<tr><td>' . $i++ . '</td><td>' . $file .
//                '</td><td>Contient</td>
//                 <td>' . $row['v'] . '</td></tr>' . "\n";
//           $r .= '<tr><td>' . $i++ . '</td><td>' . $file .
//                '</td><td>Contient</td>
//                 <td>' . $row['o'] . '</td></tr>' . "\n";
//    }
    $r .='</table>' . "\n";
} Else {
    $r = '<em>No data returned</em>';
}

echo $r;
}