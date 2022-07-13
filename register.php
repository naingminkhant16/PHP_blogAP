<?php
session_start();
require 'config/config.php';
require 'config/common.php';
if ($_POST) {
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 6) {
        if (empty($_POST['name'])) {
            $nameError = "Name is required";
        }
        if (empty($_POST['email'])) {
            $emailError = "Email is required";
        }
        if (empty($_POST['password'])) {
            $passwordError = "Password is required";
        } elseif (strlen($_POST['password']) < 6) {
            $passwordError = "Password must have atleast 6 characters";
        }
    } else {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $statement = $pdo->prepare('SELECT * FROM users WHERE email=:email');
        $statement->bindValue(':email', $email);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            echo "<script>alert('Your account is already created! Please Login.')</script>";
        } else {
            $statement = $pdo->prepare("INSERT INTO users(name,email,password) VALUES (:name,:email,:password)");
            $result = $statement->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $password
            ]);
            if ($result) {
                echo "<script>alert('Successfully created account');window.location.href='login.php'</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Blog | Register</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b>BLog</b></a>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Create New Account</p>

                <form action="register.php" method="POST">
                    <input type="hidden" name="_token" class="form-control" value="<?= $_SESSION['_token'] ?>">
                    <p style="color:red"><?= isset($nameError) ? '*' . $nameError : '' ?></p>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="name" placeholder="Name">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <p style="color:red"><?= isset($emailError) ? '*' . $emailError : '' ?></p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <p style="color:red"><?= isset($passwordError) ? '*' . $passwordError : '' ?></p>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div><br>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col">
                            <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                            <a href="login.php" class="btn btn-default btn-block">Login</a>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <!-- <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>

</body>

</html>