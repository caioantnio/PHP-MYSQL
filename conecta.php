<?php

$conexao = mysqli_connect("localhost:3306","root","","loja");
if(!$conexao) {
    die("Falhou! Erro: " . mysqli_connect_error());
}
echo "";
?>