<!-- Inicializar o projecto PHP -->

<?php

use PHPMailer\PHPMailer\PHPMailer;

require('../Db_config/conn.php');

//VERIFICAR SE EXISTE UMA POSSTAGEM NOS INPUT
if(isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['repete_senha'])){
    //VERIFICAR SE TODAS AS POSTAGEN FORAM PREENCHIDAS
    if(empty($_POST['nome']) or empty($_POST['email']) or empty($_POST['senha']) or empty($_POST['repete_senha']) or empty($_POST['termos'])){
        echo "Todos campos sao obrigarios";
    }else{
        //Receber do post e limpar
        $nome = limparPost($_POST['nome']);
        $email = limparPost($_POST['email']);
        $senha = limparPost($_POST['senha']);
        //Senha criptogrfada
        $senha_Cript = sha1($senha);
        $repete_senha = limparPost($_POST['repete_senha']);
        $checkbox = limparPost($_POST['termos']);

        //Valodar caracter de nomes para que seja nome valido
        if(!preg_match("/^[a-zA-Z- ']*$/",$nome)){
            $erro_nome = "Apenas letras e epacos em branco";
        }
        //Verificar se o email e valido
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $erro_email = "Email invalido";
        }
        //Validacao de senha + de 6 disgitos
        if(strlen($senha) < 6 ){
            $erro_senha = "A senha deve ter 6 digitos ou mais";
        }
        //verificar se repete senha e igual
        if($senha !== $repete_senha){
            $erro_RepSenha = "Senha diferente";
        }
        //Verificar se checkbox e igual
        if($checkbox !== "ok"){
            $erroCheckBox = "Desativado";
        }
        //Inserir no Banco caso nao haja erros
        if(!isset($erro_geral) && !isset($erro_nome) && !isset($erro_email) && !isset($erro_senha) && !isset($erro_RepSenha) && !isset($erroCheckBox)){

            //Verificar se usuario esta cadastrado
            $sql = $pdo ->prepare("SELECT * FROM usuarios WHERE email=? LIMIT 1");
            $sql->execute(array($email));
            $usuarios = $sql->fetch();
            //caso nao exista usuario com email cadastrado - cadastrar
            if(!$usuarios){
                $recuperarSenha = "";
                $token = "";
                $confir_code= uniqid();
                $status = "novo";
                $dataCadastro = date('d-m-Y');
                $sql = $pdo->prepare("INSERT INTO usuarios VALUES (null, ?,?,?,?,?,?,?,?)");
                if($sql->execute(array($nome, $email, $senha_Cript, $recuperarSenha, $token,$confir_code, $status, $dataCadastro))){
                    //local
                    if($modo == "local"){
                        //caso estej tudo ok redicionar
                    header('location: ../views/login.php?result=ok');
                    }
                }
            }else{
                //Caaso exista apresentar erro
                $erro_geral = "Usuario Cadastrado";
            }
        }

    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" />

    <!-- Animacoes -->
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />

    <link rel="stylesheet" href="css/style.css">

    <title>Login</title>
</head>

<body>
    <section class="geral-form">
        <div class="log-link">
            <h2>Hello and Welcome!</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit similique distinctio ad saepe odit in quia ullam ducimus</p>
            <span><a href="../views/login.php">Ja tens tens conta</a></span>
            <div class="contact-us">
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-linkedin"></i></a>
                <a href="#"><i class="fa-solid fa-envelope"></i></a>
            </div>
            <button><a href="../views/login.php">Log-In</a></button>
        </div>

        <div class="log-input">
            <h1>Sign Up in virtual library</h1>
<!-- Erro geral cas inputs estejam vasios -->
            <?php if(isset($usuario->error
            ["erro_geral"]) ){?>
            <div class="error-geral animate__animated animate__rubberBand">
                <?php echo $usuario->error
                ["error-geral"]; ?>
            </div>
        <?php } ?>
<?php
if(isset($erro_geral)){ ?>
<div class="erro-geral animate__aanimated animate__rubberBand">
    <?php echo $erro_geral; ?>
</div>

<?php  } ?>

<!-- Erro no login -->
  <?php if(isset($login->erro["erro-geral"])){ ?>
<div class="erro-geral animate__animated animate_rubberBand">
    <?php echo $login->error["erro-geral"]; ?>
</div>
<?php
} ?>
            <div class="form-infor">
                <form action="" method="post">
                    <div class="input-infor">
                        <div class="input-detail">

                        <!-- Caso exista erro no input -->
                            <input
                            <?php if(isset($erro_geral) or isset($erro_nome))
                            {echo "class = 'erro-input";} ?>

                            type="text" name="nome" placeholder="Digite o seu nome "
                            <?php
//Caso uma parte dos dados esteja incorrextos
//devem permanecer os correctos
if(isset($_POST['nome'])) echo "value = '".$_POST['nome']."'";?>>
                            <i class="fa-solid fa-user"></i>

                            <!-- Caso introduza dados incorrectps -->

<?php if(isset($erro_nome)){ ?>
<div class="erro">
    <?php echo $erro_nome; ?>
</div>
<?php } ?>
</div>

                        <div class="input-detail">
                            <input 
                        <?php
                        //Validar email
                    if(isset($erro_email)or isset($erro_geral)){
                        echo "class = 'erro-input'";
                    } ?>   
                            type="email" name="email" placeholder="Digite email valido"
                    <?php if(isset($_POST['email'])) echo "value '".$_POST['email']."'";?>>

                            <i class="fa-solid fa-envelope"></i>

            <?php if(isset($erro_email)){ ?>
            <div class="erro">
                <?php echo $erro_email; ?>
            </div>
            <?php } ?>
         </div>
<!-- Validar senha -->
                        <div class="input-detail">
                            <input 
                            <?php if(isset($erro_senha) or isset($erro_geral)){
                                echo "class = 'erro-input'";
                            } ?>
                            
                            type="password" name="senha" placeholder=" Digite a senha "
                            <?php if(isset($_POST['senha'])) echo "value = '".$_POST['senha']."'";?>>

                            <i class="fa-solid fa-lock"></i>
<?php if(isset($erro_senha)){
    ?>
    <div class="erro"><?php
echo $erro_senha; ?>
    </div>
<?php } ?>
</div>

                        <div class="input-detail">
                            <!-- Validar repete senha -->
                            <input 
                        <?php if(isset($repete_senha) or isset($erro_geral)){
                            echo "class = 'erro-input'";
                        }?>
                            type="password" name="repete_senha" placeholder=" Repete a senha "
                            
                            <?php if(isset($_POST['repete_senha'])) echo "value = '".$_POST['repete_senha']."'";?>>

                            <i class="fa-solid fa-lock-open"></i>

                            <?php if(isset($erro_RepSenha)){
                                ?>
                                <div class="erro">
                                    <?php echo $erro_RepSenha; ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Validar CheckBox -->
        <div class="privacity">
            <input type="checkbox" id="termos" name="termos" value="ok">


            <label for="termos">Ao se cadastrar voce concorda com a nossa <a href="#" class="link">Politica de Privacidade</a> e os <a href="#" class="link">Termos de uso</a></label>
        </div>
                   
                    <button type="submit">Sign Up</button>
                </form>
            </div>
        </div>

    </section>
</body>

</html>