<?php
class SequenceId extends UrgAppModel {
	var $name = 'SequenceId';

    function next($table) {
        $sequence = $this->findByTable($table);

        if (!$sequence) {
            $sequence["SequenceId"] = array("table" => $table, "next" => 0);
        }

        $next = $sequence["SequenceId"]["next"];
        $sequence["SequenceId"]["next"] = $next + 1;

        $this->save($sequence);

        return $next;
    }
}
