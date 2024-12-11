<?php
require('../Db_config/conn.php');


if(isset($_POST['email']) && isset($_POST['senha']) && !empty($_POST['email']) && !empty($_POST['senha'])){
    //Receber dados do post e limpa
    $email = limparPost($_POST['email']);
    $senha = limparPost($_POST['senha']);
    $senha_cript = sha1($senha);


    //Verificar se usuaro existe
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email=? AND senha=? LIMIT 1");
    $sql->execute(array($email,$senha_cript));
    $usuarios = $sql->fetch(PDO::FETCH_ASSOC);

    if($usuarios){
        //Existe usuario
        //Criar token (sequencia de numero e letra de usuario id)
        if($usuarios){
            $token = sha1(uniqid().date('d-m-Y-H-i-s'));

            //Actualizar o token no banco
            $sql = $pdo->prepare("UPDATE usuarios SET token=? WHERE email=? AND senha=?");
            if($sql->execute(array($token, $email, $senha_cript))){
                //Armazenar na SESSAO
                $_SESSION['TOKEN'] = $token;
                header('location: restrita.php');
            }
        }else{
            $erro_login = "Confirme o seu cadastro no email";
        } 
        
    }else{
        $erro_login = "Usuario ou senha invalida";
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

    <title>Login</title>
</head>

<body>
    <section class="geral-form">
        <div class="log-input">
            <h1>Sign in virtual library</h1>

            <!-- Mensagem de cadastrado com sucess0 -->
            <?php if(isset($_GET['result']) && ($_GET['result']=="ok")){ ?>
            <div class="sucesso">
                Cadastrado com sucesso!
            </div>
        <?php }?>


        <!-- Erro casso tenha dados errados -->
        <?php if(isset($login->erro["erro_geral"])) { ?>
        <div class="erro-geral animate__animated animate__rubberBand">
            <?php echo $login->erro["erro_geral"]; ?>
        </div>
    <?php } ?>

<!-- Renderizar erro -->
<?php if(isset($erro_login)) { ?>
            <div style="text-align: center; font-size: 14px;" class="erro-geral animate__animated animate__rubberBand">
                <?php echo $erro_login; ?>
            </div>
        <?php } ?> 
    
            <div class="form-infor">
                <form action="" method="post">
                    <div class="input-infor">
                        <div class="input-detail">
                            <input type="email" name="email" placeholder="Digite email valido">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div class="input-detail">
                            <input type="password" name="senha" placeholder=" Digite a senha ">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                    </div>

                    <p>
                        <a href="../views/recuperar_senha.php">Esqueceu a senha?</a>
                    </p>
                    <button type="submit">Sign In</button>
                </form>
            </div>
        </div>
        <div class="log-link">
            <h2>Hello and Welcome!</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit similique distinctio ad saepe odit in quia ullam ducimus</p>
            <span><a href="../views/recuperar_senha.php">Ainda nao tens conta</a></span>
            <div class="contact-us">
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-linkedin"></i></a>
                <a href="#"><i class="fa-solid fa-envelope"></i></a>
            </div>
            <button><a href="../views/recuperar_senha.php">Sign Up</a></button>
        </div>
    </section>
</body>

</html>