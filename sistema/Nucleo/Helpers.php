<?php

namespace sistema\Nucleo;

use Exception;
use sistema\Nucleo\Sessao;

class Helpers{ 
    public static function localhost():bool
    {
        $servidor=filter_input(INPUT_SERVER, 'SERVER_NAME');

        if($servidor=='127.0.0.1'){
            return true;
        }else{
            return false;
        }
    }

    public static function flash(): ?string
    {
        $sessao = new Sessao();

        if ($flash = $sessao->flash()){
            echo($flash);
        }
        return null;
    }

    public static function url(?string $url=null):string
    {
        $servidor=filter_input(INPUT_SERVER, 'SERVER_NAME');
        if($servidor=='127.0.0.1'){
            $ambiente=URL_DESENVOLVIMENTO;
        }
        if(str_starts_with($url, '/')){
            return $ambiente.$url;
        }
        return $ambiente.$url;
    }

    public static function redirecionar(?string $url=Null):void
    {
        header('HTTP/1.1 302 Found');
        $local=($url?self::url($url):self::url());
        header("Location: {$local}");
        exit();
    }

    public static function dataAtual($formato):string
    {
        return date($formato);
    }

    public static function controleDestino(?string $destino=Null):void
    {
        if (!empty($destino == "entrega")){
            Helpers::redirecionar('entregasAbertas');
        }elseif (!empty($destino == "retirada")){
            Helpers::redirecionar('retiradasAbertas');
        }else{
            Helpers::redirecionar('pedidosAbertos');
        }
    }

    public static function gerarSenha(string $senha): string
    {
        return password_hash($senha, PASSWORD_DEFAULT, ['const' => 10]);
    }

    public static function checarSenha(string $senha, string $hash): bool
    {
        return password_verify($senha, $hash);
    }
}