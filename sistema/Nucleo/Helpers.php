<?php

namespace sistema\Nucleo;


class Helpers{ 
    public static function localhost():bool{
        $servidor=filter_input(INPUT_SERVER, 'SERVER_NAME');

        if($servidor=='127.0.0.1'){
            return true;
        }else{
            return false;
        }
    }

    public static function url(string $url=null):string{
        $servidor=filter_input(INPUT_SERVER, 'SERVER_NAME');
        if($servidor=='127.0.0.1'){
            $ambiente=URL_DESENVOLVIMENTO;
        }
        if(str_starts_with($url, '/')){
            return $ambiente.$url;
        }
        return $ambiente.$url;
    }

    public static function redirecionar(string $url=Null):void{
        header('HTTP/1.1 302 Found');
        $local=($url?self::url($url):self::url());
        header("Location: {$local}");
        exit();
    }
}