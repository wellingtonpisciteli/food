<?php

namespace sistema\Nucleo;

class Sessao
{
    public function __construct() {
        if (!session_id()){
            session_start();
        }
    }

    public function criar(string $chave, mixed $valor): Sessao
    {
        $_SESSION[$chave] = (is_array($valor) ? (object) $valor : $valor);
        return $this;
    }

    public function carregar(): ?object
    {
        return (object) $_SESSION;
    }

    public function checar(string $chave): bool
    {
        return isset($_SESSION[$chave]);
    }

    public function limpar(string $chave): Sessao
    {
        unset($_SESSION[$chave]);
        return $this;
    }

    public function deletar(): Sessao
    {
        session_destroy();
        return $this;
    }

    public function __get($atributo)
    {
        if (!empty($_SESSION[$atributo])){
            return $_SESSION[$atributo];
        }
    }

    public function flash(): ?Mensagem
    {
        if ($this->checar('flash')){
            $flash = $this->flash;
            $this->limpar('flash');
            return $flash;
        }
        return null;
    }
}