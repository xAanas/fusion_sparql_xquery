<?php

/*
 * Copyright (c) 2008, ClearForest Ltd.
 * 
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without 
 * modification, are permitted provided that the following conditions 
 * are met:
 * 
 * 		- 	Redistributions of source code must retain the above 
 * 			copyright notice, this list of conditions and the 
 * 			following disclaimer.
 * 
 * 		- 	Redistributions in binary form must reproduce the above 
 * 			copyright notice, this list of conditions and the 
 * 			following disclaimer in the documentation and/or other 
 * 			materials provided with the distribution. 
 * 
 * 		- 	Neither the name of ClearForest Ltd. nor the names of 
 * 			its contributors may be used to endorse or promote 
 * 			products derived from this software without specific prior 
 * 			written permission. 
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS 
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT 
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS 
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE 
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, 
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, 
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; 
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER 
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, 
 * STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) 
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF 
 * ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * 
 * Calais REST Interface sample
 * 
 * This class invokes the OpenCalais REST interface via HTTP POST
 * Envoi vers open calais 
 */

class Connexion {

    var $data = null;
    var $response = null;
    var $content = "Microsoft has made several bids to purchase Yahoo!";
    var $paramsXML = "";

// Your license key (obatined from api.opencalais.com)

    const apiKey = "sranbj8eem8ghznn6zrdjdax";
// Url web Service Rest
    const restURL = "http://api.opencalais.com/enlighten/rest/";
// Content and input/output formats
    const contentType = "text/html"; //"application/x-www-form-urlencoded"; // simple text - try also text/html
    const outputFormat = "xml/rdf";// "text/simple"; // simple output format - try also xml/rdf and text/microformats

    function __construct($content) {
        $this->paramsXML = "<c:params xmlns:c=\"http://s.opencalais.com/1/pred/\" " .
                "xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"> " .
                "<c:processingDirectives c:enableMetadataType=\"GenericRelations\" c:contentType=\"" . self::contentType . "\" " .
                "c:outputFormat=\"" . self::outputFormat . "\"" .
                "></c:processingDirectives> " .
                "<c:userDirectives c:allowDistribution=\"true\" " .
                "c:allowSearch=\"true\" c:externalID=\"17cabs901\" " .
                "c:submitter=\"Calais REST Sample\"></c:userDirectives> " .
                "<c:externalMetadata><c:Caller>Calais REST Sample</c:Caller>" .
                "</c:externalMetadata></c:params>";
/*$this->paramsXML="<c:params xmlns:c=\"http://s.opencalais.com/1/pred/\" xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\">
<c:processingDirectives c:contentType=\"text/html\" c:enableMetadataType=\"GenericRelations\" c:outputFormat=\"text/simple\">
</c:processingDirectives><c:userDirectives c:allowDistribution=\"true\" c:allowSearch=\"true\" c:externalID=\"17cabs901\" c:submitter=\"ABC\">
</c:userDirectives><c:externalMetadata>";*/
        $this->data = "licenseID=" . urlencode(self::apiKey);
        $this->data .= "&content=" . urlencode($content);
        $this->data .= "&paramsXML=" . urlencode($this->paramsXML);
    }

    function responseXML() {
// Invoke the Web service via HTTP POST
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::restURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);        
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $this->response = curl_exec($ch);        
        curl_close($ch);
        return $this->response;
    }
}

?>