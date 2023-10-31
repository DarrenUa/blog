<?php
    require_once '../config/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?php echo INCLUDE_PATH_ADMIN; ?>assets/css/style.css" rel="stylesheet">
    <title>Entre | My Blog</title>
</head>
<body>
    <?php
        // login verification
        $error = false;
        if(isset($_POST['register'])) {
            if($_POST['user'] == '' || $_POST['password'] == '') {
                $error = true;
            } else {
                $sql = $pdo->prepare("SELECT password FROM `tb_admin_users` WHERE user = ? OR email = ?");
                $sql->execute(array($_POST['user'], $_POST['user']));
                $hash = $sql->fetchColumn();

                $sql = $pdo->prepare("SELECT * FROM `tb_admin_users` WHERE (user = ? OR email = ?) AND password = ?");
                $sql->execute(array($_POST['user'], $_POST['user'], $hash));
                if($sql->rowCount() == 1) {
                    $info = $sql->fetch();
                    $_SESSION['myblog-login'] = true;
                    $_SESSION['myblog-user'] = $info['user'];
                    $_SESSION['myblog-email'] = $info['email'];
                    $_SESSION['myblog-password'] = $info['password'];
                    $_SESSION['myblog-name'] = $info['name'];
                    $_SESSION['myblog-profile-photo'] = $info['profile_photo'];
                    $_SESSION['myblog-role'] = $info['role'];
                    $_SESSION['myblog-role-name'] = Panel::getRole($info['role']);
                    header('Location: '.INCLUDE_PATH_ADMIN);
                    die();
                } else {
                    $error = true;
                }
            }
        }
    ?>

    <!-- include path --> 
    <input type="hidden" name="include_path" value="<?php echo INCLUDE_PATH; ?>" />

    <!-- login container -->
    <div class="register-container">
        <div class="register-box">
            <?php if($error) echo '<p class="form-message">Erro ao enviar o formulário</p>'; ?>
            <div class="title">
                <h3>Entre</h3>
            </div>
            <form action="" method="post">
                <label for="user">Usuário ou email</label>
                <input type="text" name="user" id="user" required />
                <label for="password">Senha</label>
                <input type="password" name="password" id="password" required />
                <div class="remember">
                    <input type="checkbox" name="remember" id="remember" />
                    <label for="remember">Lembrar Senha</label>
                </div>
                <div class="change-register">Não tem uma conta? <a href="<?php echo INCLUDE_PATH_ADMIN; ?>signup">Cadastre-se</a></div>
                <input type="submit" name="register" value="Login" />
            </form>
        </div>
    </div>
</body>
</html>
