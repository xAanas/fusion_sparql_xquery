<?php
/**
transforme le fichier xml to objet avec ses different attributs 
**/
class WebSemObject {
   var $fileName ;
   var $rdf ;
   var $type; 
   var $genre = null;
   var $relation = array();
   var $topic = array();
   public function getFileName(){
     return $this->fileName;
   }
   public function getRdf(){
     return $this->rdf;
   }
   
   public function getType(){
     return $this->type;
   }
   
   public function getRelation(){
     return $this->relation;
   }
   
   public function setFileName($fileName){
     $this->fileName = $fileName;
   }
   
    public function setRdf($rdf){
     $this->rdf = $rdf;
   }
   public function setType($type){
     $this->type = $type;
   }
   
   public function setRelation($relation){
     $this->relation = array_merge($this->relation , $relation );
   }
   

   
   
}
?>