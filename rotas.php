<?php

use Pecee\SimpleRouter\SimpleRouter;
use sistema\Nucleo\Helpers;

try {
    SimpleRouter::setDefaultNamespace('sistema\Controlador');

    SimpleRouter::match(['get', 'post'], URL_SITE . 'login', 'LoginControlador@login');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'logout', 'LoginControlador@logout');


    SimpleRouter::get(URL_SITE . 'comanda', 'ComandaViewControlador@comanda');
    SimpleRouter::get(URL_SITE . 'cardapio', 'ComandaViewControlador@cardapio');
    SimpleRouter::get(URL_SITE . 'adicionar', 'ComandaViewControlador@adicionar');
    SimpleRouter::get(URL_SITE . 'pedidosAbertos', 'ComandaViewControlador@pedidosAbertos');
    SimpleRouter::get(URL_SITE . 'pedidosFechados', 'ComandaViewControlador@pedidosFechados');
    SimpleRouter::get(URL_SITE . 'entregasAbertas', 'ComandaViewControlador@entregasAbertas');
    SimpleRouter::get(URL_SITE . 'entregasFechadas', 'ComandaViewControlador@entregasFechadas');
    SimpleRouter::get(URL_SITE . 'retiradasAbertas', 'ComandaViewControlador@retiradasAbertas');
    SimpleRouter::get(URL_SITE . 'retiradasFechadas', 'ComandaViewControlador@retiradasFechadas');
    SimpleRouter::get(URL_SITE . 'editarLanche/{id}', 'ComandaViewControlador@editarLanche');
    SimpleRouter::get(URL_SITE . 'editarAdicional/{chave}', 'ComandaViewControlador@editarAdicional');
    SimpleRouter::get(URL_SITE . 'editarBebida/{chave}', 'ComandaViewControlador@editarBebida');
    SimpleRouter::get(URL_SITE . 'adicionarNaMesa/{id_mesa}', 'ComandaViewControlador@adicionarNaMesa');
    SimpleRouter::get(URL_SITE . 'adicionarAdicional/{id_mesa}/{id_lanche}', 'ComandaViewControlador@adicionarAdicional');
    SimpleRouter::get(URL_SITE . 'caixa/{id_mesa}', 'ComandaViewControlador@caixa');
    SimpleRouter::get(URL_SITE . 'imprimir/{id_mesa}', 'ComandaViewControlador@imprimir');


    SimpleRouter::get(URL_SITE . 'cadastrarItem', 'AdminViewControlador@cadastrarItem');
    SimpleRouter::get(URL_SITE . 'cadastrarUsuario', 'AdminViewControlador@cadastrarUsuario');
    SimpleRouter::get(URL_SITE . 'editarLanches', 'AdminViewControlador@editarLanches');
    SimpleRouter::get(URL_SITE . 'editarAdicionais', 'AdminViewControlador@editarAdicionais');
    SimpleRouter::get(URL_SITE . 'editarBebidas', 'AdminViewControlador@editarBebidas');
    SimpleRouter::get(URL_SITE . 'editarUsuarios', 'AdminViewControlador@editarUsuarios');


    SimpleRouter::match(['get', 'post'], URL_SITE . 'novoItem', 'AdminControlador@novoItem');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'editarItem/{id}', 'AdminControlador@editarItem');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'excluirItem/{id}/{controle}', 'AdminControlador@excluirItem');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'novoUsuario', 'AdminControlador@novoUsuario');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'editarUsuario/{id}', 'AdminControlador@editarUsuario');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'excluirUsuario/{id}', 'AdminControlador@excluirUsuario');


    SimpleRouter::match(['get', 'post'], URL_SITE . 'cadastrar', 'ComandaControlador@cadastrar');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'atualizar/{id}', 'ComandaControlador@atualizar');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'excluirMesa/{id_mesa}/{status}', 'ComandaControlador@excluirMesa');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'editarMesa/{id_mesa}/{mesaNova}', 'ComandaControlador@editarMesa');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'abrirMesa/{id_mesa}/{param}', 'ComandaControlador@abrirMesa');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'fecharMesa/{id_mesa}', 'ComandaControlador@fecharMesa');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'em_preparo/{id_mesa}', 'ComandaControlador@em_preparo');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'excluirEntrega/{id_pedido}/{status}', 'ComandaControlador@excluirEntrega');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'despachar/{id_mesa}', 'ComandaControlador@despachar');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'abrirEntrega/{id_mesa}', 'ComandaControlador@abrirEntrega');

    SimpleRouter::get(URL_SITE . '404', 'ComandaViewControlador@erro404');

    SimpleRouter::get(URL_SITE . 'sair', 'ComandaViewControlador@sair');

    SimpleRouter::start();
} catch (Pecee\SimpleRouter\Exceptions\NotFoundHttpException $ex) {
    if (Helpers::localhost()) {
        echo ($ex);
    } else {
        // Helpers::redirecionar('404');
    }
}
