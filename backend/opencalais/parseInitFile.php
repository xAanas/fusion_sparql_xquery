<?php

/**
  extraire le contenu de balise description a partir de fichier xml
 * */
require_once 'openCalaisWS.php';
require_once 'webSemObject.php';
require_once 'parseResponse.php';

class parseInitFile {

    var $webSem;
 var $fil="";
    /**
     * *  répare le parseur XML (évenement)
     * */
    public function __construct($file) {
        $simple_l1_tag = null;

        $this->webSem = new WebSemObject();
        $fileName = explode("/", $file);
        
        $this->webSem->setFileName($fileName[4]);
        $xmlp = xml_parser_create();
        xml_parser_set_option($xmlp, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($xmlp, XML_OPTION_SKIP_WHITE, 0);
       
        xml_set_element_handler($xmlp, array(&$this, "simple_start"), array(&$this, "simple_stop"));
        xml_set_character_data_handler($xmlp, array($this, "simple_char"));

        if (xml_parse($xmlp, file_get_contents($file), 1) == 0) {
            echo "Parse error";
        }
        xml_parser_free($xmlp);
    }

    /**
      début de balise
     * */
    function simple_start($parser, $element_name, $element_atts) {
        global $simple_l1_tag;
        $simple_l1_tag = $element_name;
        /**
          type de média
         * */
        if (strtolower($element_name) == "itemclass") {
            foreach ($element_atts as $name => $value) {
                if ($name == "qcode") {
                    $type = explode(":", $value);
                    switch (strtolower($type[1])) {
                        case "picture" : $this->webSem->setType("image");
                            break;
                        case "audio" : $this->webSem->setType("audio");
                            break;
                        case "video" : $this->webSem->setType("video");
                            break;
                    }
                }
            }
        }
        /**
         * prédicat
         **/
        if (strtolower($element_name) == "genre") {
            foreach ($element_atts as $name => $value) {
                if ($name == "qcode") {
                    $genre = explode(":", $value);
                    $this->webSem->genre = $genre[1];
                }
            }
        }
    }

    /**
      fin de balise
     * */
    function simple_stop($parser, $element_name) {
        
    }

    /**
      contenu du balise
     * */
    function simple_char($parser, $data) {
        global $simple_l1_tag;

        /*
         * Data within <tag> </tag> - set the name of the current element
         * Percer la balise description et l'envoi vers open calais 
         */
        if ($simple_l1_tag != null && strtolower($simple_l1_tag) == "description" && $this->fil!=$this->webSem->getFileName()) {
            $conn = new Connexion(htmlentities($data));
            $parser = new Parser($conn->responseXML());

            $parser->parseXML($this->webSem->getFileName());

            /**
              les relation dans le flux XML
             * */
            
            $this->webSem->setRelation($parser->getRelation());
            $this->fil=$this->webSem->getFileName();
        }
        /**
          remplir les topics
         **/
        if ($simple_l1_tag != null && strtolower($simple_l1_tag) == "keyword") {
            if ($this->webSem->type == "image" || $this->webSem->type == "video") {
                array_push($this->webSem->topic, htmlentities($data));
            }
        }
    }

    public function getWebSemObject() {

        return $this->webSem;
    }

}
