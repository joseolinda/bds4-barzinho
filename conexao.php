<?php
// --- CONFIGURAÇÃO DO BANCO DE DADOS ---
$host = "localhost"; 
$usuario = "root";   
$senha = "";         
$banco = "barzinho"; 

// --- CONEXÃO COM O BANCO DE DADOS ---
$conexao = mysqli_connect($host, $usuario, $senha, $banco);

// Verifica a conexão
if (!$conexao) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}
?>