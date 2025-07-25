<?php

namespace sistema\Suporte;

use Twig\Lexer;
use sistema\Nucleo\Helpers;
use sistema\Controlador\UsuarioControlador;

class Template{
    private \Twig\Environment $twig;

    public function __construct(string $diretorio) 
    {

        // Cria um loader para carregar os templates do diretório fornecido
        $loader=new \Twig\Loader\FilesystemLoader($diretorio);

        // Inicializa o ambiente Twig com o loader criado
        $this->twig=new \Twig\Environment($loader);

    
        $lexer=new Lexer($this->twig, array(
            $this->helpers()
        ));
    }

    public function renderizar(string $view, array $dados):string
    {
        return $this->twig->render($view, $dados);
    }

    private function helpers():void
    {
        array(
            $this->twig->addFunction(
                new \Twig\TwigFunction('url', function(?string $url=null){
                    return Helpers::url($url);
                })
            ),
            $this->twig->addFunction(
                new \Twig\TwigFunction('dataAtual', function(?string $dataAtual='d/m'){
                    return Helpers::dataAtual($dataAtual);
                })
            ),
            $this->twig->addFunction(
                new \Twig\TwigFunction('flash', function(){
                    return Helpers::flash();
                })
            )
        );
    }
}