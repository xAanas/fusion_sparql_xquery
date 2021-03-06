<?php
/*
homepage: http://arc.semsol.org/
license:  http://arc.semsol.org/license

class:    ARC2 RDF Store Query Handler
author:   Benjamin Nowack
version:  2009-02-13 (Tweak: removed cid parameter in getTermID() method)
*/

ARC2::inc('Class');

class ARC2_StoreQueryHandler extends ARC2_Class {

  function __construct($a = '', $caller) {
    parent::__construct($a, $caller);
  }
  
  function ARC2_StoreQueryHandler($a = '', $caller) {
    $this->__construct($a, $caller);
  }

  function __init() {/* db_con */
    parent::__init();
    $this->xsd = 'http://www.w3.org/2001/XMLSchema#';
    $this->allow_extension_functions = $this->v('store_allow_extension_functions', 1, $this->a);    
    $this->handler_type = '';
  }

  /*  */

  function getTermID($val, $term = '', $tbl) {
    return $this->store->getTermID($val, $term,$tbl);
  }
  
  /*  */

  function getTripleTable() {
    $r = $this->store->getTablePrefix() . 'triple';
    return $r;
  }
  
  /*  */

  function createMergeTable() {
    $split_ps = $this->store->getSetting('split_predicates', array());
    if (!$split_ps) return 1;
    $this->mrg_table_id = 'MRG_' . $this->store->getTablePrefix() . crc32(uniqid(rand()));
    $con = $this->store->getDBCon();
    mysql_query("FLUSH TABLES", $con);
    $indexes = $this->v('store_indexes', array('sp (s,p)', 'os (o,s)', 'po (p,o)'), $this->a);
    $index_code = $indexes ? 'KEY ' . join(', KEY ',  $indexes) . ', ' : '';
    $prefix = $this->store->getTablePrefix();
    $sql = "
      CREATE TEMPORARY TABLE IF NOT EXISTS " . $prefix . "triple_all (
        t mediumint UNSIGNED NOT NULL,
        s mediumint UNSIGNED NOT NULL,
        p mediumint UNSIGNED NOT NULL,
        o mediumint UNSIGNED NOT NULL,
        o_lang_dt mediumint UNSIGNED NOT NULL,
        o_comp char(35) NOT NULL,                   /* normalized value for ORDER BY operations */
        s_type tinyint(1) NOT NULL default 0,       /* uri/bnode => 0/1 */
        o_type tinyint(1) NOT NULL default 0,       /* uri/bnode/literal => 0/1/2 */
        misc tinyint(1) NOT NULL default 0,         /* temporary flags */
        UNIQUE KEY (t), " . $index_code . " KEY (misc)
      ) 
    ";
    $v = $this->store->getDBVersion();
    $sql .= (($v < '04-01-00') && ($v >= '04-00-18')) ? 'ENGINE' : (($v >= '04-01-02') ? 'ENGINE' : 'TYPE');
    $sql .= "=MERGE UNION=(" . $prefix . "triple" ;
    foreach ($split_ps as $pos => $p) {
      $sql .= ',' . $prefix . 'triple_' . abs(crc32($p));
    }
    $sql .= ")";
    //$sql .= ($v >= '04-00-00') ? " CHARACTER SET utf8" : "";
    //$sql .= ($v >= '04-01-00') ? " COLLATE utf8_unicode_ci" : "";
    //echo $sql;
    return mysql_query($sql, $con);
  }

  function dropMergeTable() {
    $sql = "DROP TABLE IF EXISTS " . $this->store->getTablePrefix() . "triple_all";
    //echo $sql;
    return mysql_query($sql, $this->store->getDBCon());
  }
  
}
