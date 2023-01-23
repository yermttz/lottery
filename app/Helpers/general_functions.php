<?php

function dateEs($string){
    $string = str_replace('Monday', 'Lunes', $string);
    $string = str_replace('Tuesday', 'Martes', $string);
    $string = str_replace('Wednesday', 'Miércoles', $string);
    $string = str_replace('Thursday', 'Jueves', $string);
    $string = str_replace('Friday', 'Viernes', $string);
    $string = str_replace('Saturday', 'Sábado', $string);
    $string = str_replace('Sunday', 'Domingo', $string);
    return $string;
}