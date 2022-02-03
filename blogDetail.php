<?php
session_start();
require 'config/config.php';
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php");
}
//fetch blog
$id = $_GET['id'];
$statement = $pdo->prepare("SELECT * FROM posts WHERE id=:id");
$statement->execute([':id' => $id]);
$result = $statement->fetch(PDO::FETCH_ASSOC);
//fetch comments
$cmtstatement = $pdo->prepare("SELECT * FROM comments WHERE post_id=:post_id");
$cmtstatement->execute([
    ':post_id' => $id
]);
$comments = $cmtstatement->fetchAll(PDO::FETCH_OBJ);

//insert comments
if ($_POST) {
    $comment = $_POST['comment'];
    $statement = $pdo->prepare("INSERT INTO comments(content,author_id,post_id) VALUES (:content,:author_id,:post_id)");
    $result = $statement->execute([
        ':content' => $comment,
        ':author_id' => $_SESSION['user_id'],
        ':post_id' => $id
    ]);
    if ($result) {
        header("location: blogDetail.php?id=" . $id);
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 3 | Widgets</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini" style="background-color: #eee;">
    <div class="">
        <!-- Navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="">
            <!-- Content Header (Page header) -->

            <section class="content container mt-4 w-75">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Box Comment -->
                        <div class="card card-widget">
                            <!-- /.card-header -->
                            <div class="card-header text-center">
                                <h2><?= $result['title'] ?></h2>
                            </div>
                            <div class="card-body">
                                <img class="img-fluid pad" src="admin/images/<?= $result['image'] ?>" alt="Photo">
                                <br><br>
                                <p><?= $result['content'] ?></p>
                                <h2>Comments</h2>
                                <hr>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer card-comments">
                                <?php foreach ($comments as $comment) :
                                    //fetch comment's auth
                                    $authId = $comment->author_id;
                                    $authstatement = $pdo->prepare("SELECT name FROM users WHERE id=:auth_id");
                                    $authstatement->execute([
                                        ":auth_id" => $authId
                                    ]);
                                    $authorName = $authstatement->fetch(PDO::FETCH_OBJ);
                                ?>
                                    <div class="card-comment">
                                        <div class="comment-text" style="margin-left: 0 !important;">
                                            <span class="username">
                                                <?= $authorName->name ?>
                                                <span class="text-muted float-right"><?= $comment->created_at ?></span>
                                            </span><!-- /.username -->
                                            <?= $comment->content ?>
                                        </div>
                                        <!-- /.comment-text -->
                                    </div>
                                <?php endforeach; ?>
                                <!-- /.card-comment -->
                            </div>
                            <!-- /.card-footer -->
                            <div class="card-footer">
                                <form action="" method="post">
                                    <div class="img-push">
                                        <input type="text" class="form-control form-control-sm" name="comment" placeholder="Press enter to post comment">
                                    </div>
                                </form>
                            </div>
                            <!-- /.card-footer -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
                <a href="index.php" class="btn btn-default">Back</a><br><br>
            </section>
            <!-- /.content -->

            <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
                <i class="fas fa-chevron-up"></i>
            </a>
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer text-center" style="margin-left: 0 !important;">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 3.0.5
            </div>
            <strong>Copyright &copy; 2022 <a href="https://github.com/naingminkhant16">naingminkhant16</a>.</strong> All
            rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
</body>

</html>