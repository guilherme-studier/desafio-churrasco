<?php
  session_start();
  if(isset($_SESSION['autenticado']) && $_SESSION['autenticado'] == 'SIM'){
    header('Location: home.php');
  }
?>

<html>
  <head>
    <meta charset="utf-8" />
    <title>Grupo WL</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
      .card-login {
        padding: 30px 0 0 0;
        width: 350px;
        margin: 0 auto;
      }
    </style>
  </head>

  <body>

    <nav class="navbar navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="#">
          Grupo WL
        </a>
      </div>
    </nav>

    <div class="container">    
      <div class="row">

        <div class="card-login">
          <div class="card">
            <div class="card-header">
              Login
            </div>
            <div class="card-body">
              <form action="valida_login.php" method="post">
                <div class="form-group">
                  <input name="email" type="email" class="form-control" placeholder="E-mail">
                </div>
                <div class="form-group">
                  <input name="senha" type="password" class="form-control" placeholder="Senha">
                </div>
                <!-- php -->
                <? if(isset($_GET['login']) && $_GET['login'] == 'erro'){ ?>
                  
                  <div class="text-danger">
                      Usuário ou senha inválido(s)
                  </div>

                <? } ?> 
                <? if(isset($_GET['login']) && $_GET['login'] == 'erro2'){ ?>
                  
                  <div class="text-danger mb-3">
                  Faça login antes de acessar as páginas protegidas
                  </div>

                <? } ?> 
                <button class="btn btn-lg btn-info btn-block" type="submit">Entrar</button>
              </form>
            </div>
          </div>
        </div>
    </div>
  </body>
</html>