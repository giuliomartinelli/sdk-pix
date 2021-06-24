<?php
/**
 * @author Giulio Augusto Martinelli
 * @version 0.0.1
 */

namespace App;


/**
 * Class Utils
 * @package App
 */
class Utils
{
    /**
     * prepara a string tirando acentuações e deixando todas as letras maiúsculas
     * @param $string
     * @return string
     */
    public function prepareString(string $string){
        $string = preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
        return mb_strtoupper($string, 'UTF-8');
    }
}