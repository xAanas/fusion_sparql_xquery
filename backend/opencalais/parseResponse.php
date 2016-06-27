<?php

// Now, the Web service's response is in the $response variable.
// You can now parse the response and use it ...
//echo $response;
// SIMPLE FORMAT Parsing sample
//
// The code below will only work for text/simple output format (it
// parses the response and displays the entities/events/facts it founds)
//  
// parser la relation retourner par open calais 
class Parser {

    var $xmlParser;
    var $elements = array();
    var $fichier='';

    function __construct($xmlParser) {
        $this->xmlParser = $xmlParser;
    }

    /**
      extraire les relation à partir des flux de open calais
     * */
    function parseXML($file){
        
        $doc = new DOMDocument();
        $arr = explode("</OpenCalaisSimple>", $this->xmlParser);
       
                $f = fopen('../../data/schemacontent.xml', 'w');
                fwrite($f, $arr[0]);
                fclose($f);
                
                getloadrdf();
                lanchsparqlrequest($file);
                
                $deletetables1="TRUNCATE TABLE  arc2_opclaisdb4_db.arc__triple";mysql_query($deletetables1);
                $deletetables2="TRUNCATE TABLE  arc2_opclaisdb4_db.arc__s2val";mysql_query($deletetables2);
                $deletetables3="TRUNCATE TABLE  arc2_opclaisdb4_db.arc__o2val";mysql_query($deletetables3);
                $deletetables4="TRUNCATE TABLE  arc2_opclaisdb4_db.arc__id2val";mysql_query($deletetables4);
                $deletetables5="TRUNCATE TABLE  arc2_opclaisdb4_db.arc__g2t";mysql_query($deletetables5);
                
                $f = fopen('../../data/schemacontent.xml', 'w');
                fwrite($f, '');
                fclose($f);
    }

    public function getRelation() {

        $arrayfinal = array();
        foreach ($this->elements as $array) {
            array_push($arrayfinal, $array);
        }


        return $arrayfinal;
    }

}

?>