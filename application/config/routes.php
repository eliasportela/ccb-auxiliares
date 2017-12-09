<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//views
$route['default_controller'] = 'Api/Home';
$route['gerar'] = 'Api/GerarServico';

//apis
$route['api/login'] = 'Api/Login';
$route['api/gerar'] = 'Api/Gerar';
$route['api/listar'] = 'Api/ListarServicos';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
