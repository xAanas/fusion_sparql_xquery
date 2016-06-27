<?php
/**
liste le contenu d'une repertoire 
**/
class listDirectory {
      /**
	     chemin de dossier 
	  **/
      var $path ;
	  
	  public function __construct($path) {
	     $this->path = $path ;
	  }
	  
	  public function listdir() {
	      return scandir($this->path); 
	  }

}
