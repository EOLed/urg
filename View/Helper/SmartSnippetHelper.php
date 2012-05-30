<?php
class SmartSnippetHelper extends AppHelper {
    function snippet($string, $strlen = 300, $leeway = 100) {
        if (strlen($string) <= ($strlen + $leeway))
            return $string;

        $split_pos = min(strlen($string), $strlen);
        $pos = strpos($string, ' ', $split_pos);

        $snippet = "";
        if ($pos === false) {
             $snippet = substr($string, 0, $strlen);
        } else {
            $snippet = substr($string, 0, $pos);
            $newline_pos = strrpos($snippet, "\n");

            if ($newline_pos === false)
                return $snippet  . "...";

            if (($pos - $newline_pos) < 20) {
                $pos = $newline_pos;
                $snippet = substr($string, 0, $newline_pos - 3);
            }
        }

        return $snippet . "...";
    }
}
