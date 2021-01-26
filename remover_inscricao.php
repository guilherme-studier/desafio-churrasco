<?php

    session_start();

    $num = $_GET["remover"];

	// lê todo o conteudo do arquivo para o vetor linhas
	$linhas = file("arquivo.txt");

	//retira do vetor a linha excluida o -1 é para a linha anterior
	unset($linhas[$num-1]);

	//criar o arquivo novamente
	$arq = fopen("arquivo.txt", "w");

	// insere todos os elementos do vetor sem o excluido
	foreach ($linhas as $conteudo){
		fwrite($arq, $conteudo);
	}
	fclose($arq);
    
    //retornar
    header('Location: home.php');

?>