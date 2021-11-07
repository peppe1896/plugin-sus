<?php

function suscale_get_grade($sus_score) {
    if ($sus_score >= 92.5) {
        return "A";
    }
    else if (82.5 <= $sus_score && $sus_score < 92.5) {
        return "B";
    }
    else if (70 <= $sus_score && $sus_score < 82.5) {
        return "C";
    }
    else if (52.5 <= $sus_score && $sus_score < 70) {
        return "D";
    }
    else if (37.5 <= $sus_score && $sus_score < 52.5) {
        return "E";
    }
    else if (20 <= $sus_score && $sus_score < 37.5) {
        return "F";
    }
    else return "G";
}

function suscale_get_info($sus_score) {
    $final_string = " - Best: A / Worst: G";
    $string = "Grade " . suscale_get_grade($sus_score) . " system" . $final_string;
    return $string;
}

function suscale_sanitize_validate_questionnaire($json_received, $userID, $systemID){
    if(is_numeric($userID) && is_numeric($systemID)){
        $actual_systems = get_option('suscale_pages');
        if(in_array($systemID, $actual_systems)){
            foreach ($json_received as $key => $value) {
                if( is_numeric($json_received[$key])){
                    if((int)$json_received[$key] > 6 || (int)$json_received[$key] < 1){
                        return false;
                    }
                }
            }
            return true;
        }else{
            return false;
        }
    }
    return false;
}

function suscale_sanitize_validate_queue_names($array){
    $temp = array();
    foreach ($array as $key => $value) {
        $temp[$key] = preg_replace( '/[^A-Za-z0-9_\-]/', '', $value);
    }
    return $temp;
}

function suscale_sanitize_search($name, $macro, $only_unregistered){
    if($only_unregistered === "false" || $only_unregistered === "true"){
        $data = array();
        $data["name"]               = sanitize_user($name);
        $data["macro"]              = sanitize_text_field($macro);
        $data["only_unregistered"]  = $only_unregistered;
        return $data;
    }
    return false;
}

function suscale_remove_shortcode($text_of_page){
    echo "old content: " . $text_of_page;
    $new_text =  preg_replace('/\[sus_here[^\]]*\]/', '', $text_of_page);
    echo "new_text " . $new_text; 
    return $new_text;
}

function suscale_esc_js_array($array){
    $temp = $array;
    foreach ($array as $key => $value) {
        if(is_array($value)){
            foreach ($value as $key_inside => $value_inside) {
                $temp[$key][$key_inside] = esc_js($value_inside);
            }
        }else{
            $temp[esc_js($key)] = esc_js($value);
        }
    }
    return $temp;
}