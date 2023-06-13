<?php 

    $db_name = "movie_estar";
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";

    $conexao = new PDO("mysql:dbname=". $db_name .";host=". $db_host, $db_user, $db_pass);

    // HABILITAR ERROS PDO

    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
?>