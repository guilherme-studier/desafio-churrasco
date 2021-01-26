<?php

    session_start();

    //estamos trabalhando na montagem no texto
    $nome = str_replace('#', '-', $_SESSION['name']);
    $selectColaboradorBeber = str_replace('#', '-', $_POST['selectColaboradorBeber']);
    $selectConvidado = str_replace('#', '-', $_POST['select-convidado']);
    $nomeConvidado = str_replace('#', '-', $_POST['nomeConvidado']);
    $selectConvidadoBeber = str_replace('#', '-', $_POST['selectConvidadoBeber']);
    $id_usuario = str_replace('#', '-', $_SESSION['id']);

    $texto = $_SESSION['id'] . '#' . $nome . "#" . $selectColaboradorBeber . "#" . $selectConvidado . '#' .  $nomeConvidado . '#' .  $selectConvidadoBeber . PHP_EOL;

    //abrindo o arquivo
    $arquivo = fopen('arquivo.txt', 'a');

    //escrevendo o texto
    fwrite($arquivo, $texto);
    
    //fechando o arquivo
    fclose($arquivo);  
    
    //retornar
    header('Location: home.php');

?>