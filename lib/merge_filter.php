<?php

function merge_filter($row1et2,$rows){
           if (count($rows) > 0) {
            foreach ($rows as $ro) {
                $rr = array($ro);
                if (count($row1et2) > 0) {
                    $trouve = false;
                    foreach ($row1et2 as $recap) {
                        $result1 = array_diff_uassoc($ro, $recap, "key_compare_func");
                        if (count($result1) == 0) {//trouve true si ro et recap sont similaire
                            $trouve = true;
                            break;
                        }
                    }
                    if ($trouve == false)
                        $row1et2 = array_merge($row1et2, $rr);
                }else {
                    $row1et2 = array_merge($row1et2, $rr);
                }
            }
        }
        return $row1et2;
}
?>
