<?php

use Pecee\SimpleRouter\SimpleRouter;
use sistema\Nucleo\Helpers;

try {
    SimpleRouter::setDefaultNamespace('sistema\Controlador');

    SimpleRouter::get(URL_SITE . 'comanda', 'SiteControlador@comanda');
    SimpleRouter::get(URL_SITE . 'adicionar', 'SiteControlador@adicionar');
    SimpleRouter::get(URL_SITE . 'pedidosAbertos', 'SiteControlador@pedidosAbertos');

    SimpleRouter::get(URL_SITE . 'busca/{id}', 'SiteControlador@busca');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'cadastrar', 'SiteControlador@cadastrar');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'atualizar/{id}', 'SiteControlador@atualizar');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'atualizarAdicional/{chave}', 'SiteControlador@atualizarAdicional');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'editarPedido/{id}', 'SiteControlador@editarPedido');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'editarAdicionais/{chave}', 'SiteControlador@editarAdicionais');
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
