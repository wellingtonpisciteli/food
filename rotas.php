<?php

use Pecee\SimpleRouter\SimpleRouter;
use sistema\Nucleo\Helpers;

try {
    SimpleRouter::setDefaultNamespace('sistema\Controlador');

    SimpleRouter::get(URL_SITE . 'comanda', 'SiteControlador@comanda');
    SimpleRouter::get(URL_SITE . 'adicionar', 'SiteControlador@adicionar');
    SimpleRouter::get(URL_SITE . 'pedidosAbertos', 'SiteControlador@pedidosAbertos');
    SimpleRouter::get(URL_SITE . 'pedidosFechados', 'SiteControlador@pedidosFechados');
    SimpleRouter::get(URL_SITE . 'entregasAbertas', 'SiteControlador@entregasAbertas');
    SimpleRouter::get(URL_SITE . 'entregasFechadas', 'SiteControlador@entregasFechadas');
    SimpleRouter::get(URL_SITE . 'retiradasAbertas', 'SiteControlador@retiradasAbertas');
    SimpleRouter::get(URL_SITE . 'retiradasFechadas', 'SiteControlador@retiradasFechadas');


    SimpleRouter::get(URL_SITE . 'busca/{id}', 'SiteControlador@busca');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'cadastrar', 'ComandaControlador@cadastrar');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'atualizar/{id}', 'ComandaControlador@atualizar');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'excluirMesa/{id_mesa}/{status}', 'ComandaControlador@excluirMesa');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'editarMesa/{id_mesa}/{mesaNova}', 'ComandaControlador@editarMesa');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'abrirMesa/{id_mesa}', 'comandaControlador@abrirMesa');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'fecharMesa/{id_mesa}', 'ComandaControlador@fecharMesa');

    SimpleRouter::match(['get', 'post'], URL_SITE . 'excluirEntrega/{id_pedido}/{status}', 'ComandaControlador@excluirEntrega');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'despachar/{id_mesa}', 'comandaControlador@despachar');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'abrirEntrega/{id_mesa}', 'comandaControlador@abrirEntrega');


    SimpleRouter::match(['get', 'post'], URL_SITE . 'editarLanche/{id}', 'SiteControlador@editarLanche');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'editarAdicional/{chave}', 'SiteControlador@editarAdicional');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'editarBebida/{chave}', 'SiteControlador@editarBebida');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'adicionarNaMesa/{id_mesa}', 'SiteControlador@adicionarNaMesa');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'adicionarAdicional/{id_mesa}/{id_lanche}', 'SiteControlador@adicionarAdicional');
    SimpleRouter::get(URL_SITE . 'caixa/{id_mesa}', 'SiteControlador@caixa');
    SimpleRouter::get(URL_SITE . 'imprimir/{id_mesa}', 'SiteControlador@imprimir');

    SimpleRouter::get(URL_SITE . '404', 'SiteControlador@erro404');

    SimpleRouter::get(URL_SITE . 'sair', 'SiteControlador@sair');

    SimpleRouter::start();
} catch (Pecee\SimpleRouter\Exceptions\NotFoundHttpException $ex) {
    if (Helpers::localhost()) {
        echo ($ex);
    } else {
        // Helpers::redirecionar('404');
    }
}
