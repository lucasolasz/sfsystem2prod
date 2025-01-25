<?php 

class Redirecionamento{


    public static function redirecionar($url){
        header("Location:".DIRECTORY_SEPARATOR.$url);
    }
}