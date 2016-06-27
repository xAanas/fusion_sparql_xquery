<?php
function getSearchResults($keyword) {
    $prefixSearchURLBase = "http://lookup.dbpedia.org/api/search.asmx/PrefixSearch?QueryClass=&MaxHits=5&QueryString=";
    $ch = curl_init($prefixSearchURLBase . urlencode($keyword));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Shoot the request
    $result = curl_exec($ch); // Gotcha!
    $sxml = simplexml_load_string($result);
    return $sxml->Result;
}
?>
