<?php

require_once 'listdir.php';
require_once 'parseInitFile.php';
require_once 'graph.php';
include_once '../../lib/arc/ARC2.php';
include_once '../../config/config.php';
include_once 'loadrdf.php';
include_once '../../lib/getpersonsfrom.php';
include_once 'get.php';

//header('Content-type: application/xml');
class contOpcalais {

    var $tabWebObject = array();

    public function contenuOpCalais() {
        $dir = new listDirectory('../../data/xmldocs');
        $cont = $dir->listdir();
        foreach ($cont as $file) {
            if (!is_dir($file)) {
                $parse = new parseInitFile('../../data/xmldocs/' . $file);
                array_push($this->tabWebObject, $parse->getWebSemObject());
            }
        }
        //var_dump($this->tabWebObject);exit();
    }

    public function lien() {
        
        $xml = "<?xml version=\"1.0\"?>\n<ressources>";
        $graph = new Graph($this->tabWebObject);
        foreach ($this->tabWebObject as $webobj) {

            $xml.="<ressource id =\"" . $webobj->fileName . "\" type =\"" . $webobj->type . "\">\n";

            if ($webobj->type == "image") {

                if (is_null($webobj->genre)) {
                    $speech = "<link name=\"s\">\n";
                } else {
                    $speech = "<link name=\"" . $webobj->genre . "\">\n";
                }

                $ln_speech = $graph->speechRelation($webobj);
                if (!empty($ln_speech))
                    $speech.= $ln_speech . "</link>\n";
                else
                    $speech = "";
                $xml.=$speech;

                if (is_null($webobj->genre)) {
                    $ta = "<link name=\"t_a\">\n";
                } else {
                    $ta = "<link name=\"" . $webobj->genre . "\">\n";
                }

                $ln_ta = $graph->talkAboutRelation($webobj);
                if (!empty($ln_ta))
                    $ta.= $ln_ta . "</link>\n";
                else
                    $ta = "";
                $xml.=$ta;


                if (is_null($webobj->genre)) {
                    $sp = "<link name=\"s_a\">\n";
                } else {
                    $sp = "<link name=\"" . $webobj->genre . "\">\n";
                }
                $ln_sp = $graph->speechAboutRelation($webobj);
                if (!empty($ln_sp))
                    $sp.= $ln_sp . "</link>\n";
                else
                    $sp = "";
                $xml.=$sp;

                if (is_null($webobj->genre)) {
                    $show = "<link name=\"sh\">\n";
                } else {
                    $show = "<link name=\"" . $webobj->genre . "\">\n";
                }

                $ln_show = $graph->showRelation($webobj);
                if (!empty($ln_show))
                    $show.= $ln_show . "</link>\n";
                else
                    $show = "";
                $xml.=$show;

                if (is_null($webobj->genre)) {
                    $app = "<link name=\"a\">\n";
                } else {
                    $app = "<link name=\"" . $webobj->genre . "\">\n";
                }


                $ln_app = $graph->apparaitreRelation($webobj);
                if (!empty($ln_app))
                    $app.= $ln_app . "</link>\n";
                else
                    $app = "";
                $xml.=$app;
            }

            if ($webobj->type == "video" || $webobj->type == "audio") {

                if (is_null($webobj->genre)) {
                    $ds = "<link name=\"a_of\">\n";
                } else {
                    $ds = "<link name=\"" . $webobj->genre . "\">\n";
                }
                $ln_ds = $graph->discoursDeRelation($webobj);
                if (!empty($ln_ds))
                    $ds.= $ln_ds . "</link>\n";
                else
                    $ds = "";
                $xml.=$ds;
            }

            if ($webobj->type == "video") {

                if (is_null($webobj->genre)) {
                    $show = "<link name=\"sh\">\n";
                } else {
                    $show = "<link name=\"" . $webobj->genre . "\">\n";
                }

                $ln_show = $graph->showRelation($webobj);
                if (!empty($ln_show))
                    $show.= $ln_show . "</link>\n";
                else
                    $show = "";
                $xml.=$show;
            }




            $xml.="</ressource>\n";
        }
        $xml.="</ressources>";
        print $xml;
    }

}

set_time_limit(120);
$opCal = new contOpcalais();
$opCal->contenuOpCalais();
$opCal->lien();
?>