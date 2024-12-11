<!-- Inicializar o projecto PHP -->

<?php

use PHPMailer\PHPMailer\PHPMailer;

require('../Db_config/conn.php');

//VERIFICAR SE EXISTE UMA POSSTAGEM NOS INPUT
if(isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['repete_senha'])){
    //VERIFICAR SE TODAS AS POSTAGEN FORAM PREENCHIDAS
    if(empty($_POST['email']) or empty($_POST['senha']) or empty($_POST['repete_senha'])){
        echo "Deves redifinir a senha";
    }else{
        //Receber do post e limpar
        $email = limparPost($_POST['email']);
        $senha = limparPost($_POST['senha']);
        //Senha criptogrfada
        $senha_Cript = sha1($senha);
        $repete_senha = limparPost($_POST['repete_senha']);
        $checkbox = limparPost($_POST['termos']);

        //Validacao de senha + de 6 disgitos
        if(strlen($senha) < 6 ){
            $erro_senha = "A senha deve ter 6 digitos ou mais";
        }
        //verificar se repete senha e igual
        if($senha !== $repete_senha){
            $erro_RepSenha = "Senha diferente";
        }
        
        //Inserir no Banco caso nao haja erros
        if(!isset($erro_geral) && !isset($erro_email) && !isset($erro_senha) && !isset($erro_RepSenha)){

            //Verificar se usuario esta cadastrado
            $sql = $pdo ->prepare("SELECT * FROM usuarios WHERE email=? LIMIT 1");
            $sql->execute(array($email));
            $usuarios = $sql->fetch();
            //caso nao exista usuario com email cadastrado - cadastrar
            if(!$usuarios){
                $recuperarSenha = "";
                $token = "";
                $confir_code= uniqid();
                $sql = $pdo->prepare("INSERT INTO usuarios VALUES (null, ?)");
                if($sql->execute(array($senha_Cript))){
                    //local
                    if($modo == "local"){
                        //caso estej tudo ok redicionar
                    header('location: ../views/login.php?result=ok');
                    }
                }
            }else{
                //Caaso exista apresentar erro
                $erro_geral = "Senha alterada com sucesso";
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

    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />

    <link rel="stylesheet" href="css/style.css">

    <title>Actualiar senha</title>
</head>

<body>

       <h1>Actualizar senha</h1>

      
       <?php
            $chave = filter_input(INPUT_GET, 'chave', FILTER_DEFAULT);
            //var_dump($chave);
            if(!empty($chave)){
                
            $update_usuario = "SELECT email
            FROM usuarios 
            WHERE recupera_senha =:recupera_senha
            LIMIT 1";

            $result_usuario = $pdo->prepare($update_usuario);
            $result_usuario->bindParam(':recupera_senha', $chave, PDO::PARAM_STR);
            $result_usuario->execute();

            if($result_usuario->execute() and ($result_usuario->rowCount() !=0)){ 
                $usuarios = $result_usuario->fetch(PDO::FETCH_ASSOC);
                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                //var_dump($dados);

                if(!empty($dados['senha'])){
                    $nova_senha = password_hash($dados['senha'],PASSWORD_DEFAULT);
                    $update_usuario = "UPDATE usuarios 
                        SET senha =:senha
                        WHERE email =:email
                        LIMIT 1";
        $result_usuario = $pdo->prepare($update_usuario);
        $result_usuario->bindParam(':senha', $nova_senha, PDO::PARAM_STR);
        $result_usuario->bindParam(':email', $usuarios['email'], PDO::PARAM_INT);

        //Validar
         //Validacao de senha + de 6 disgitos
         if(strlen($senha) < 6 ){
            $erro_senha = "A senha deve ter 6 digitos ou mais";
        }
        //verificar se repete senha e igual
        if($senha !== $repete_senha){
            $erro_RepSenha = "Senha diferente";
        }

        if($result_usuario->execute()){ 
            $erro_login = "Actualizado com sucesso";
            header("Location: ../views/login.php?");
        }else{
            echo "Erro tente  novamente";
        }
                }

            }else{
                $erro_login = "Link invalido, solicite novo";
                header("Location: ../views/recuperar_senha.php?");
            }

            }else{
                $erro_login = "Link invalido, solicite novo";
                header("Location: ../views/recuperar_senha.php?");
            }
        ?>

        <!-- Redifinir a senha -->
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
            <h1>Redifinir a senha</h1>
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
                   
                    <button type="submit">Sign Up</button>
                </form>
            </div>
        </div>

    </section>
</body>

</html>