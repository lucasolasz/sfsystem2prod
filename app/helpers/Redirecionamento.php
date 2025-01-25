<?php 

class Redirecionamento{


    public static function redirecionar($url){
        header("Location:".URL.$url);
    }
}