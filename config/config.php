<?php

function _config($db){
$config = array(
    'db_host' => 'localhost',
    'db_name' => $db,
    'db_user' => 'root',
    'db_pwd' => '',
    'store_name' => 'arc_',
    //"remote_store_endpoint" => "http://dbpedia.org/sparql",
    'endpoint_features' => array(
        'select', 'construct', 'ask', 'describe', 'load', 'insert', 'delete', 'dump'
    ),
    /* Préfixe pour les nœuds blancs */
    'bnode_prefix' => 'bn',
    /* Formats supportés par l'extracteur de données structurées dans les pages Web */
    'sem_html_formats' => 'rdfa microformats',
    'endpoint_timeout' => 120, /* not implemented in ARC2 preview */
    'endpoint_read_key' => '', /* optional */
    'endpoint_write_key' => '', /* optional */
    'endpoint_max_limit' => 120, /* optional */
);
$store = ARC2::getStore($config);
/**
 * Pour créer le schéma s'il n'existe pas : 
 */
if (!$store->isSetUp()) {
    $store->setUp();
}
return $store;
}
?>
