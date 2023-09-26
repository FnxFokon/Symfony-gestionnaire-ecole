<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class AppExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    //méthode pour enlever tous les accents d'une chaine de caractères

    public function removeAccents($str)

    {

        $str = htmlentities($str, ENT_NOQUOTES, 'utf-8');

        $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);

        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);

        $str = preg_replace('#&[^;]+;#', '', $str);

        return $str;
    }



    public function colorLabel($value)
    {
        switch ($value) {
            case 'Français':
                return 'background-color: blue !important;';
            case 'Mathématiques':
                return 'background-color: red !important;';
            case 'Histoire-Géographie':
                return 'background-color: brown !important;';
            case 'Anglais':
                return 'background-color: yellowgreen !important;';
            case 'Espagnol':
                return 'background-color: orange !important;';
            case 'Physique-Chimie':
                return 'background-color: purple !important;';
            case 'Allemand':
                return 'background-color: indigo !important;';
            case 'SVT':
                return 'background-color: green !important;';
            case 'Technologie':
                return 'background-color: cyan !important;';
            case 'Arts Plastiques':
                return 'background-color: pink !important;';
            case 'Musique':
                return 'background-color: magenta !important;';
            case 'EPS':
                return 'background-color: navy !important;';
            case 'Latin':
                return 'background-color: grey !important;';
            case 'Grec':
                return 'background-color: olive !important;';
            default:
                return 'background-color: black !important;';
        };
    }

    public function colorClass($value)
    {
        switch ($value) {
            case '601':
                return 'background-color: aquamarine !important;';
            case '602':
                return 'background-color: aqua !important;';
            case '603':
                return 'background-color: blue !important;';
            case '501':
                return 'background-color: yellow !important;';
            case '502':
                return 'background-color: greenyellow !important;';
            case '503':
                return 'background-color: green !important;';
            case '401':
                return 'background-color: orange !important;';
            case '402':
                return 'background-color: orangered !important;';
            case '403':
                return 'background-color: red !important;';
            case '301':
                return 'background-color: pink !important;';
            case '302':
                return 'background-color: purple !important;';
            case '303':
                return 'background-color: indigo !important;';
            default:
                return 'background-color: black !important;';
        };
    }
}
