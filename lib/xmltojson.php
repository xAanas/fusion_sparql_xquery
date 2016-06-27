<?php

//class XmlToJson {
// public 
function Parse($url) {
  $fileContentss = file_get_contents($url);
  $fileContentss = str_replace(array("\n", "\r", "\t"), '', $fileContentss);
  $fileContentss = trim(str_replace('"', "'", $fileContentss));
  $simpleXml = simplexml_load_string($fileContentss);
  $json = json_encode($simpleXml);

  return $json;
}

$json_a = Parse('../data/endresult.xml');
$jsonIterator = new RecursiveIteratorIterator(
    new RecursiveArrayIterator(json_decode($json_a, TRUE)), RecursiveIteratorIterator::LEAVES_ONLY);
$nodes = array();
$edges = array();
$n = 0;

$perfect_json = '{"nodes":[';
$edges_chaine = '],"edges":[';
$pere = NULL;
$link = NULL;
$prev_key = null;
$prev_val = NULL;
$ligne = NULL;
foreach ($jsonIterator as $key => $val) {
//    if (is_array($val)) {
//          echo count($val)." $key:\n";
//    } else {
  //var_dump("$key => $val\n");
  //collecter t les noeuds
  if ($key == "id") {
    $trouve = false;
    for ($index = 0; $index < count($nodes); $index++) {
      if ($nodes[$index] == $val) {
        $trouve = TRUE;
        break;
      }
    }
    if ($trouve == false) {
      $nodes[$n++] = $val;
      $perfect_json = $perfect_json . '"' . $nodes[$n - 1] . '",';
    }
    //comment différencier le noeud pere des autres noeuds?
    if ($pere != null) {
      $ligne = '["' . $pere . '","' . $val . '",{"color": "#00A0B0","label": "' . $link . '"}],';
      $edges_chaine = $edges_chaine . $ligne;
    }
  }
  elseif ($key == "name") {
    //fixer le noeud pere president si le key actuelle et un link "name" 
    $prev_key = $key;
    $pere = $prev_val;
    $link = $val;
  }
  elseif ($key == "parent") {
    $prev_val = $val;
    //supprimer l'edge du pere le suivant pere (déjà ajouter)
    if ($ligne != null) {
      $tailel_dernier = strlen($ligne);
      $edges_chaine = substr($edges_chaine, 0, strlen($edges_chaine) - $tailel_dernier);
    }
  }

  // }
}
//supprimer la virgule de fin ","
$perfect_json = substr($perfect_json, 0, -1);
$edges_chaine = substr($edges_chaine, 0, -1);
//concatiné les 2 chaines de noeuds et edges
$perfect_json = $perfect_json . $edges_chaine;
//fermeture de la chaine json
$perfect_json = $perfect_json . ']}';

// fusionner les resultat de query et sparql
if (isset($_REQUEST['xquery_method'])) {
  // get the result of xquery work
  $xfp = fopen('../../view/json/xquery_json_result.json', 'r+');
  $xquery_json = fgets($xfp);
  fclose($xfp);
  //convert to php array
  $sparql_arrayphp = json_decode($perfect_json);
  $xquery_arrayphp = json_decode($xquery_json);
  // merge the two array in one array
  $fusion = array();
  $fusion['nodes'] = array_merge($sparql_arrayphp->nodes, $xquery_arrayphp->nodes);
  $fusion['edges'] = array_merge($sparql_arrayphp->edges, $xquery_arrayphp->edges);
  
  // put the result in $perfect_json again
  $perfect_json = json_encode($fusion);
}
$fp = fopen('../data/results.json', 'w+');
fwrite($fp, $perfect_json); //Parse('xmldocs/result.xml')); //json_encode($response)
fclose($fp);
?>