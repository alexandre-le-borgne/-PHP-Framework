<?php 

function time_to_date($time, $debut = "", $fin = "", $format = "Y-n-j | G:i:s") {   
    if(!$time)
        return;
    return $debut.date($format, $time).$fin;
}

function time_to_delay($time, $verbose = true) {
    $base = time();
	$time = $base - $time;
    $years=intval($time / (3600 * 24 * 30 * 12));
    $time -= $years * 3600 * 24 * 30 * 12;
    $months=intval($time / (3600 * 24 * 30));
    $time -= $months * 3600 * 24 * 30;
    $jours=intval($time / (3600 * 24));
    $time -= $jours * 3600 * 24;
    $heures=intval($time / 3600);
    $time -= $heures * 3600;
    $minutes=intval($time / 60);
    $time -= $minutes * 60;
    $secondes=$time;
	$return = '';
    if($years > 0) {
        $return .= $years;
        if($verbose) {
            $return .= " an";
            if($years > 1)
                $return .= "s";
        }
        else {
            $return .= "a";
        }
	}
    elseif($months > 0) {
        $return .= $months;
        if($verbose)
            $return .= " mois";
        else
            $return .= "m";
	}
	elseif($jours > 0) {
        $return .= $jours;
        if($verbose) {
            $return .= " jour";
            if($jours > 1)
                $return .= "s";
        }
        else {
            $return .= "j";
        }
	}
	elseif($heures > 0) {
        $return .= $heures;
		if($verbose) {
            $return .= " heure";
            if($heures > 1)
                $return .= "s";
        }
        else {
            $return .= "h";
        }
	}
	elseif($minutes > 0) {
        $return .= $minutes;
		if($verbose) {
            $return .= " minute";
            if($minutes > 1)
                $return .= "s";
        }
        else {
            $return .= "min";
        }
	}
	else {
        $return .= $secondes;
		if($verbose) {
            $return .= " seconde";
            if($secondes > 1)
                $return .= "s";
        }
        else {
            $return .= "sec";
        }
	}
	return $return;
}