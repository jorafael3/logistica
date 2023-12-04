<?php
// Crear un flujo
$opciones = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "Cookie: foo=bar\r\n"
  )
);

$contexto = stream_context_create($opciones);

// Abre el fichero usando las cabeceras HTTP establecidas arriba
$fichero = file_get_contents('http://www.cartimex.com/', false, $contexto);
?>