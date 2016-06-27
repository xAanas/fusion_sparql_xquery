<?php
require('../lib/wordnik/wordnik/Swagger.php');
function wordnik($term){
$myAPIKey = '00144cdc1140c4192780f08c3f608399234240dcb560d3e4b';
$client = new APIClient($myAPIKey, 'http://api.wordnik.com/v4');
$wordApi = new WordApi($client);
$example = $wordApi->getRelatedWords("$term",'synonym',true,7);
if(!empty($example))
    return $example[0]->words;
else return null;
}
?>
