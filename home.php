<?php
  // validar acesso a aplicação
  require_once "validador_acesso.php";

  // pegar os dados dos inscritos no evento
  function pegarInscritos(){
    //criar array para os inscritos no evento
    $inscritos = array();
    //abrir o arquivo.hd
    $arquivo = fopen('arquivo.txt', 'r');
    //enquanto houver registros (linhas) a serem recuperados
    while(!feof($arquivo)){ //teste pelo fim de um arquivo
      //linhas
      $registro = fgets($arquivo);
      if( $registro != null){
        // se a linha não estiver nula, acrescentar dentro do array
        $inscritos[] = explode('#', $registro);
      }
    }
    //fechar o arquivo aberto
    fclose($arquivo);
    
    return $inscritos;
  }
  // pegar os dados dos inscritos no evento
  function pegarConvidados(){
    $inscritos = pegarInscritos();
    $convidados = array();

    foreach($inscritos as $inscrito){
      $convidado = array();
      if($inscrito[3]){
        $convidado[0] = $inscrito[4];
        $convidado[1] = $inscrito[5] ? 'Sim' : 'Não';
        $convidados[] = $convidado;
      }
    }
    return $convidados;
  }
  //função para validar se o usuario logado está inscrito no evento
  function seUsuarioInscrito($inscritos){
    // variavel inicial
    $esta_inscrito = false;
    // percorrer os dados dos inscritos
    foreach($inscritos as $index => $inscrito){
      // transformar a linha do arquivo em array
      if($inscrito[0] == $_SESSION['id']){
        $inscrito[6] = $index+1; //pegar linha do item no arquivo
        $esta_inscrito = $inscrito;
      }
    }
    return $esta_inscrito;
  }
  // função para calcular o valor que o inscrito deverá pagar
  function calcularValor($inscrito){
    $valor_final = 20;
    $valor_final = $inscrito[2] ? $valor_final : $valor_final / 2 ;
    if( $inscrito[3] && $inscrito[5] ){
      $valor_final = $valor_final + 20;
    }else if( $inscrito[3] && !$inscrito[5] ){
      $valor_final = $valor_final + 10;
    }
    return $valor_final;
  }
  // função para calcular o valor do todos os inscritos 
  function calcularValorFinal(){

    $inscritos = pegarInscritos();
    $valor_final = 0;
    foreach($inscritos as $inscrito){
      $valor = 20; //valor inicial
      $valor = $inscrito[2] ? $valor : $valor / 2 ; //calcular se o inscrito vai beber
      if( $inscrito[3] && $inscrito[5] ){ //se vai levar convidado e se ele vai beber
        $valor = $valor + 20;
      }else if( $inscrito[3] && !$inscrito[5] ){ //se vai levar convidado e se ele não vai beber
        $valor = $valor + 10;
      }
      $valor_final = $valor_final + $valor;
    }
    
    return $valor_final;
  }
  // função para calcular o total gasto com bebidas
  function totalGastoComBebida(){

    $inscritos = pegarInscritos();
    $valor_final = 0;
    foreach($inscritos as $inscrito){
      $valor = $inscrito[2] ? 10 : 0; //calcular se o inscrito vai beber
      if( $inscrito[3] && $inscrito[5] ){ //se vai levar convidado e se ele vai beber
        $valor = $valor + 10;
      }
      $valor_final = $valor_final + $valor;
    }
    
    return $valor_final;
  }
  // função para calcular o total gasto com comida
  function totalGastoComComida(){

    $inscritos = pegarInscritos();
    $valor_final = 0;
    foreach($inscritos as $inscrito){
      $valor = 10; //calcular se o inscrito vai beber
      if( $inscrito[3] ){ //se vai levar convidado e se ele vai beber
        $valor = $valor + 10;
      }
      $valor_final = $valor_final + $valor;
    }
    
    return $valor_final;
  }
  // se usuario logado é admin
  function seAdmin(){
    if($_SESSION['perfil_id'] == 1){
      return true;
    }else{
      return false;
    }
  } 



?>

<html>
  <head>

    <meta charset="utf-8" />
    <title>Churrasco com o Grupo WL</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    
    <style>
      body {
        font-family: 'Arial', sans-serif;
        background: #ededec;
      }

      .card-home {
        padding: 30px 0 0 0;
        width: 100%;
        margin: 0 auto;
      }
    </style>

  </head>

  <body>
    <!-- menu horizontal -->
    <nav class="navbar navbar-dark bg-dark navbar-expand-lg mb-4">
      <div class="container">
        <a class="navbar-brand" href="#">Churrasco com o Grupo WL</a>
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" style="pointer-events: none !important;"><i class="fa fa-user-circle-o" aria-hidden="true"></i> <?=$_SESSION['name']?></a>
          </li>
          <li class="nav-item">
            <a href="logoff.php" class="nav-link"><i class="fa fa-sign-out" aria-hidden="true"></i> Sair</a>
          </li>
        </ul>
      </div>
    </nav>

    <div class="container">  
      <!-- formulario de cadastro no evento - exibirá se o usuário não tiver se inscrito ainda -->
      <?php if( !seUsuarioInscrito(pegarInscritos()) ){ ?> 
        <div class="card">
          <div class="card-header">
            Formulário de cadastro no evento
          </div>
          <div class="card-body">
            <form method="post" class="mb-0" action="registrar_inscricao.php">
              <div class="row">
                <div class="form-group col-12 col-sm-6">
                  <label>Nome</label>
                  <input required name="nome" type="text" class="form-control" placeholder="Nome" value="<?=$_SESSION['name']?>" disabled>
                </div>
                <!-- select colaborador beber -->
                <div class="form-group col-12 col-sm-6">
                  <label for="selectColaboradorBeber">Você irá beber?</label>
                  <select required name="selectColaboradorBeber" class="form-control" id="selectColaboradorBeber">
                    <option value="0">Não</option>
                    <option value="1">Sim</option>
                  </select>
                </div>
                <!-- fim select colaborador beber -->
              </div>
              
              <!-- select convidado -->
              <div class="form-group">
                <label for="selectConvidado">Levará um(a) convidado(a)?</label>
                <select required name="select-convidado" class="form-control" id="select-convidado">
                  <option value="0">Não</option>
                  <option value="1">Sim</option>
                </select>
              </div>
              <!-- fim select convidado -->
              <div class="row" style="display: none;" id="tem-convidado">                     
                <!-- nome convidado -->
                <div class="form-group col-12 col-sm-6">
                  <label for="nomeConvidado">Qual o nome do(a) seu(sua) convidado(a)?</label>
                  <input name="nomeConvidado" id="nomeConvidado" type="text" class="form-control" placeholder="Nome do(a) convidado(a)">                      
                  </select>
                </div>
                <!-- fim nome convidado -->
                <!-- select convidado beber -->
                <div class="form-group col-12 col-sm-6">
                  <label for="selectConvidadoBeber">Seu convidado irá beber?</label>
                  <select name="selectConvidadoBeber" class="form-control" id="selectConvidadoBeber">
                    <option value="0">Não</option>
                    <option value="1">Sim</option>
                  </select>
                </div>
                <!-- fim select convidado beber -->
              </div>

              <div class="row">
                <div class="col-6">
                  <a class="btn btn-lg btn-warning btn-block" href="home.php">Voltar</a>
                </div>

                <div class="col-6">
                  <button class="btn btn-lg btn-info btn-block" type="submit">Confirmar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      <!-- se o usuário já se inscreveu, visualizar um card do seu cadastro -->
      <?php }else{ ?> 
        <div class="card">
          <div class="card-header">
            Você está inscrito no evento!
          </div>
          <div class="card-body">
            <p class="mb-0">Colaborador vai beber? <?= seUsuarioInscrito(pegarInscritos())[2] ? 'Sim' : 'Não' ?> <br />
            Convidado: <?= seUsuarioInscrito(pegarInscritos())[3] ? 'Sim' : 'Não' ?> <br />
            Nome do convidado: <?= seUsuarioInscrito(pegarInscritos())[4]?> <br />
            Convidado vai beber? <?= seUsuarioInscrito(pegarInscritos())[5] ? 'Sim' : 'Não' ?></p>
          </div>
          <div class="card-footer">
            <b>Valor final:</b> R$<?= calcularValor(seUsuarioInscrito(pegarInscritos())) ?>
            <br />
            <a class="text-danger" href="remover_inscricao.php?remover=<?=seUsuarioInscrito(pegarInscritos())[6]?>" rel="noopener noreferrer">Remover minha inscrição</a>
          </div>
        </div>
      <?php } ?> 
      <!-- se o usuário é admin, visualizará uma tabela com os inscritos -->
      <?php if( seAdmin() ){ ?>
        <br />
        <br />
        <h5><b>Lista dos inscritos no evento:</b></h5>
        <table class="table table-bordered">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Colaborador</th>
              <th scope="col">Irá beber?</th>
              <th scope="col">Convidado</th>
              <th scope="col">Convidado irá beber?</th>
              <th scope="col">R$</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <!-- foreach para percorrer os inscritos -->
            <?php foreach(pegarInscritos() as $index => $inscrito){ ?>
            <tr>
              <td><?= $inscrito[1] ?></td>
              <td><?= $inscrito[2] == 1 ? 'Sim' : 'Não' ?></td>
              <td><?= $inscrito[4] ? $inscrito[4] : 'Não' ?></td>
              <td><?= $inscrito[5] == 1 ? 'Sim' : 'Não' ?></td>
              <td>R$ <?= calcularValor($inscrito) ?></td>
              <td class="text-center"><a class="text-danger" href="remover_inscricao.php?remover=<?=$index+1?>" rel="noopener noreferrer"><i class="fa fa-times"></i></a></td>
            </tr>
            <?php } ?>
            <!-- se não houver inscritos, exibir esse texto -->
            <?php if(count(pegarInscritos()) == 0){ ?>
            <td colspan="6" class="text-center">Não há inscritos no evento</td>
            <?php } ?>
            <!-- total gasto -->
            <tr style="background: #dedede;">
              <td colspan="4">Total gasto</td>
              <td>R$ <?= calcularValorFinal() ?></td>
              <td></td>
            </tr>
          </tbody>
        </table>
        <p>Total gasto com bebida: R$ <?=totalGastoComBebida()?></p>
        <p>Total gasto com comida: R$ <?=totalGastoComComida()?></p>
        <br />
        <h5><b>Lista dos convidados</b></h5>
        <table class="table table-bordered">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Convidado</th>
              <th scope="col">Convidado irá beber?</th>
            </tr>
          </thead>
          <tbody>
            <!-- foreach para percorrer os inscritos -->
            <?php foreach(pegarConvidados() as $index => $convidado){ ?>
            <tr>
              <td><?= $convidado[0] ?></td>
              <td><?= $convidado[1] ?></td>
            </tr>
            <?php } ?>
            <!-- se não houver convidados, exibir esse texto -->
            <?php if(count(pegarConvidados()) == 0){ ?>
            <td colspan="6" class="text-center">Não há convidados no evento</td>
            <?php } ?>
            
          </tbody>
        </table>
        <br />
        <br />
      <?php } ?> 
    </div>
    


  <script>
    // função para exibir os dados do convidado quando o usuario selecionar que levará um
    $('#select-convidado').on('change', function() {
      select_convidado = $(this).val();
      if(select_convidado == 1){
        $("#tem-convidado").show();
      }else{
        $("#tem-convidado").hide();
        $("#nome-convidado").val('');
        $("#select-convidado-beber").val($("#select-convidado-beber option:first").val());
      }
    });
  </script>

  </body>
</html>