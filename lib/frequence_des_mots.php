<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function getWordsCount($txt)
{
	//$txt = clean($txt);
	$words = array();
	if(preg_match_all('~\p{L}+~',$txt,$matches) > 0)
	{
		foreach ($matches[0] as $w)
		{
                    if(strlen($w) > 4)
			$words[$w] = isset($words[$w]) === false ? 1 : $words[$w] + 1;
		}
	}
	return $words;
}
?>
