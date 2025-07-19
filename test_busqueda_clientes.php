<?php
// Test simple para verificar búsqueda de clientes
session_start();

echo "<h1>🔍 TEST BÚSQUEDA DE CLIENTES</h1>";

// Simular POST para ajax
$_POST['accion'] = 'buscar_clientes';
$_POST['busqueda'] = 'a'; // Buscar clientes que contengan 'a'

// Headers para JSON
header('Content-Type: application/json');

// Incluir el archivo AJAX
include 'ajax/clientes_ajax.php';
?> 