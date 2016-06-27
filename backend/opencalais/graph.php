<?php

//creer le graph qui lie tous les fichiers ensemble 
require_once 'WebSemObject.php';
class Graph {
  var $tabSem = array();
  public function __construct($tab) {
     $this->tabSem = array_merge($this->tabSem , $tab);
  }
  
  public function elementVide() {
     $resemty ="";
	 foreach($this->tabSem as $webSem ) {
	    if(empty($webSem->relation)) {
		    $resemty .= "<ressource id =\"".$webSem->fileName."\" type =\"".$webSem->type."\"/>\n";
			unset($webSem);
		}
	 }
	 return $resemty;
	  
  
  }
  // parler
  public function speechRelation ($webSemObject) {
           $res= null;  
           $array = null;
		              if(!empty($webSemObject->relation)) {
					     
						    foreach($this->tabSem as $webSem){
										
							          if(!empty($webSem->relation)) {
									           foreach($webSemObject->relation as $k=>$v){
										     $tab = explode(":",$v);
											 $array = array(); 
											if($tab[1]== "person" || $tab[1] == "organisation" ) {
											array_push($array , $v);
											} else {
										   break ;
											}
									        if($webSem->fileName !=$webSemObject->fileName) {
											     
											       
											    $a = array_intersect($webSem->relation , $array );
										        if(!empty( $a )) {
											        
													
													      $str = "<ressource id =\"".$webSem->fileName."\" type =\"".$webSem->type."\"/>\n";
														  $pos = strpos($res, $str);
														  if($pos === false)
													      $res.=$str;
											     
												
											   } // end if $webSem->fileName != $webSemObject->fileName)
											   
											   } // end if($webSem->fileName !=$webSemObject->fileName)
										}	   
											   
									  } // end if(!empty($webSem->relation)
									  
							} // end for each ($this->tabSem as $webSem)
						
                           
						
		            }//  if(!empty($webSemObject->relation)) 
		   
		  return $res; 
  
  }
    // entrain de parler
   function talkAboutRelation($webSemObject) {
        
		 $res= null;  
		 $array = null ;
		              if(!empty($webSemObject->relation)) {
					         foreach($this->tabSem as $webSem){
							        if(!empty($webSem->relation) && ($webSem->type == "video" || $webSem->type == "audio" )) {
									       foreach($webSemObject->relation as $k=>$v){
										    $tab = explode(":",$v);
											$array = array();
											if($tab[1]== "person" || $tab[1] == "organisation" ) {
											array_push($array , $v);
											} else {
										   break ;
											}
									    if($webSem->fileName !=$webSemObject->fileName) {
										   
										  $a = array_intersect($webSem->relation , $array );
										        if(!empty( $a )) {
											        
													
													      $str = "<ressource id =\"".$webSem->fileName."\" type =\"".$webSem->type."\"/>\n";
														  $pos = strpos($res, $str);
														  if($pos === false)
													      $res.=$str;
											     
												
											   } // end if $webSem->fileName != $webSemObject->fileName)
											   
									    } //    if($webSem->fileName !=$webSemObject->fileName) 
									}
									} // end if !empty($webSem->relation) && ($webSem->type == "video" || $webSem->type = "audio" ))
							 } // end for each ($this->tabSem as $webSem)

							 
			        } // end if  if(!empty($webSemObject->relation)) 
		   
		   
		  return $res; 
   
   }
   // parle de 
   function speechAboutRelation($webSemObject) {
       
	          $res = null;  
			  $array = null;
          
		              if(!empty($webSemObject->relation)) {
					         
							foreach($this->tabSem as $webSem){
							
							    if(!empty($webSem->relation)) {
								    foreach ($webSemObject->relation as $element){
									    $array = array(); 
										array_push($array , $element);
									    if($webSem->type != $webSemObject->type) {
										      if($webSem->fileName != $webSemObject->fileName) {
											     $a = array_intersect($webSem->relation , $array );
										        if(!empty( $a )) {
											        
													      $str = "<ressource id =\"".$webSem->fileName."\" type =\"".$webSem->type."\"/>\n";
														  $pos = strpos($res, $str);
														  if($pos === false)
													      $res.=$str;
											     
												
											   } // end if $webSem->fileName != $webSemObject->fileName)
											  }// end if  if($webSem->fileName != $webSemObject->fileName)
											  
											  
											  
										} // end  if($webSem->type != $webSemObject->type)
										
									}// end for each 
									
									
								}// end if empty($webSem->relation
							} // foreach($this->tabSem as $webSem)
							
							
			        }// end if (!empty($webSemObject->relation)) 
		   
	     
          return $res;
   }
   // discours de 
   function discoursDeRelation($webSemObject) {
       
	          $res= null;  
			  $array = null;
         
		              if(!empty($webSemObject->relation)) {
					        
							foreach($this->tabSem as $webSem){
                                   if(!empty($webSem->relation)) {							     
								 foreach ($webSemObject->relation as $element){
											$array = array();
											array_push($array , $element);
			                              if($webSem->fileName != $webSemObject->fileName) {
									          $a = array_intersect($webSem->relation , $array );
										        if(!empty( $a )) {
											        
													
													      $str = "<ressource id =\"".$webSem->fileName."\" type =\"".$webSem->type."\"/>\n";
														  $pos = strpos($res, $str);
														  if($pos === false)
													      $res.=$str;
											     
													 } 
											   
										  
										  
									     } // end  if($webSem->fileName != $webSemObject->fileName)
								   }	//  foreach ($arrkey as $key )
							} // if(!empty($webSem->relation))
						} // end foreach($this->tabSem as $webSem)
			        }
		   
	     
          return $res;
   }
   // montrer 
   function showRelation($webSemobject) {
        
		 	 $res = null;  
             $array = null;
		              if(!empty($webSemObject->relation)) {
						
							        
									  foreach($this->tabSem as $webSem){
									       if(!empty($webSem->relation)) {
										         if($webSem->type == "video" || $webSem->type == "image" ){
										      foreach ($webSemObject->relation as $element){
											            $array = array();
														array_push($array , $element);
											        if($webSem->fileName != $webSemObject->fileName) {
													      $a = array_intersect($webSem->relation , $array );
										        if(!empty( $a )) {
											         
													      
															 foreach ($webSemObject->topic as $elementopic){
																$arraytop = array();
																array_push($arraytop,$elementopic);
															    $r = array_intersect($webSem->topic , $arraytop);
														 if(!empty($r)){		
													      $str = "<ressource id =\"".$webSem->fileName."\" type =\"".$webSem->type."\"/>\n";
														  $pos = strpos($res, $str);
														  if($pos === false)
													      $res.=$str;
														}
												}
													 
											   } // end  if(array_key_exists($key , $this->tabSem->relation))
													}// end  if($webSem->fileName != $webSemObject->fileName)
											  } // end foreach ($arrkey as $key ) 
											  } // end if($webSem->type == "video" || $webSem->type = "image" ))
										   } // end if(!empty($webSem->relation)) 
									  } // end foreach($this->tabSem as $webSem)
									  
							  
			        } // end  if(!empty($webSemObject->relation)) 
		   
		   
		  return $res; 
      
   }
   // apparaitre 
   function apparaitreRelation($webSemobject) {
        
		 	 $res= null;  
			 $array = null ;
       
		              if(!empty($webSemObject->relation)) {
							if(!empty($webSem->relation) ) {
							     
								  foreach($this->tabSem as $webSem){
								      if(!empty($webSem->relation)) {
									          if(($webSem->type == "video" || $webSem->type == "image" )){
									     foreach ($webSemObject->relation as $element){
										           $array = array();
												   array_push($array , $element);
										      if($webSem->fileName != $webSemObject->fileName) {
											     $a = array_intersect($webSem->relation , $array );
										        if(!empty( $a )) {
											        
													
													      $str = "<ressource id =\"".$webSem->fileName."\" type =\"".$webSem->type."\"/>\n";
														  $pos = strpos($res, $str);
														  if($pos === false)
													      $res.=$str;
											     
													 
											   } // end  if(array_key_exists($key , $this->tabSem->relation)) 
											  } // end  if($webSem->fileName != $webSemObject->fileName) 
										 }// end  foreach ($arrkey as $key )
										 } //   if(($webSem->type == "video" || $webSem->type = "image" ))
									  }// end   if(!empty($webSem->relation))
								  } // end foreach($this->tabSem as $webSem)
							} // if(!empty($webSem->relation) && ($webSem->type == "video" || $webSem->type = "image" ))
							
			        } // (!empty($webSemObject->relation))
		   
		   
		  return $res; 
      
   }
   
   
  
}


