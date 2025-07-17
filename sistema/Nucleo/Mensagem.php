<?php

namespace sistema\Nucleo;


class Mensagem
{

    private $texto;
    private $css;
    
    public function __toString()
    {
        return $this->renderizar();
    }

    /**
     * Método responsável pelas mensagens de sucesso
     * @param string $mensagem
     * @return Mensagem
     */
    public function sucesso(string $mensagem): Mensagem
    {
        $this->css = 'alert alert-success alert-dismissible fade show';
        $this->texto = $this->filtrar($mensagem);
        return $this;
    }

    /**
     * Método responsável pelas mensagens de erro
     * @param string $mensagem
     * @return Mensagem
     */
    public function erro(string $mensagem): Mensagem
    {
        $this->css = 'alert alert-danger alert-dismissible fade show';
        $this->texto = $this->filtrar($mensagem);
        return $this;
    }

    /**
     * Método responsável pelas mensagens de alerta
     * @param string $mensagem
     * @return Mensagem
     */
    public function alerta(string $mensagem): Mensagem
    {
        $this->css = 'alert alert-warning alert-dismissible fade show';
        $this->texto = $this->filtrar($mensagem);
        return $this;
    }

    /**
     * Método responsável pelas mensagens de informações
     * @param string $mensagem
     * @return Mensagem
     */
    public function informa(string $mensagem): Mensagem
    {
        $this->css = 'alert alert-primary alert-dismissible fade show';
        $this->texto = $this->filtrar($mensagem);
        return $this;
    }

    /**
     * Método responsável pela renderização das mensagens
     * @return string
     */
    public function renderizar(): string
    {
         
        return "
        <div class='{$this->css}' role='alert' style='border-radius: 0; width: 97%; margin: auto; text-align: center; font-weight: bold;'>
            {$this->texto}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Fechar'></button>
        </div>";
    }

    /**
     * Método responsável por filtrar as mensagens
     * @param string $mensagem
     * @return string
     */
    private function filtrar(string $mensagem): string
    {
        return filter_var($mensagem, FILTER_SANITIZE_SPECIAL_CHARS);
    }
    
    public function flash():void
    {
        (new Sessao())->criar('flash', $this);
    }

}
