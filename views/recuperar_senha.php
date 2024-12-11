<?php
require('../Db_config/conn.php');


if(isset($_POST['email']) && !empty($_POST['email'])){
    //Receber dados do post e limpa
    $email = limparPost($_POST['email']);


    //Verificar se usuaro existe
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email=? LIMIT 1");
    $sql->execute(array($email));
    $usuarios = $sql->fetch(PDO::FETCH_ASSOC);

    if($usuarios){
        //Existe usuario
        //Criar token (sequencia de numero e letra de usuario id)
    
        $recuper_senha = password_hash($usuarios['email'], PASSWORD_DEFAULT);
        echo "chave $recuper_senha";

        $update_usuario = "UPDATE usuarios 
                        SET recupera_senha =:recupera_senha
                        WHERE email =:email
                        LIMIT 1";
        $result_usuario = $pdo->prepare($update_usuario);
        $result_usuario->bindParam(':recupera_senha', $recuper_senha, PDO::PARAM_STR);
        $result_usuario->bindParam(':email', $usuarios['email'], PDO::PARAM_INT);

        if($result_usuario->execute()){ 
            echo "http://localhost/Cadastro_online/views/atualizar_senha.php?chave=$recuper_senha";
        }else{
            echo "Erro tente  novamente";
        }
    }else{
        $erro_login = "Usuario nao cadastrado!";
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
            <h1>Recuperar senha</h1>

<!-- Renderizar erro -->
 <!-- Erro casso tenha dados errados -->
 <?php if(isset($erro_login)) { ?>
            <div style="text-align: center; font-size: 14px;" class="erro-geral animate__animated animate__rubberBand">
                <?php echo $erro_login; ?>
            </div>
        <?php } ?>
    
            <div class="form-infor">

            <!-- Errodo atualizar -->

        
                <form action="" method="post">
                    <div class="input-infor">
                        <div class="input-detail">
                            <input type="email" name="email" placeholder="Digite email valido">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        
                    </div>
                    <button type="submit">Recuperar_senha</button>
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