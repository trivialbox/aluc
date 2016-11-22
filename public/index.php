<?php
session_start();
include_once '../app/init.php';

use Aluc\Service\AdministradorSrv;
use Aluc\Service\ErrorSrv;
use Aluc\Common\Urls;
use Aluc\Service\EscritorioSrv;

$_SESSION['id'] = '1234';
$_SESSION['type'] = 'admin';

function home() {
    echo 'Página de inicio';
    echo '</br>';
    echo 'No hay plata para un diseñador';
}

Urls::serveRequest([
    '/^$/' => 'home',
    '/^admin\//i' => AdministradorSrv::urls(),
    '/^escritorio\//i' => EscritorioSrv::urls(),
    '/^error\//i' => ErrorSrv::urls(),
    '/.*/' => ErrorSrv::url404()
]);
