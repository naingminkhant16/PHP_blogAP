<?php
session_start();
require 'config/config.php';
require 'config/common.php';
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php?error=login");
}
if (empty($_GET['pageNo'])) {
    $pageNo = 1;
} else {
    $pageNo = $_GET['pageNo'];
}
$numsOfPosts = 2;
$offset = ($pageNo - 1) * $numsOfPosts;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Blogs</title>
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
    <div class="wrapper">
        <!-- Navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="margin-left: 0 !important;">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid text-center">
                    <h1>Blogs</h1>
                </div>
            </section>

            <section class="content container">
                <div class="row">
                    <?php
                    $statement = $pdo->prepare("SELECT * FROM posts");
                    $statement->execute();
                    $TotalResult = $statement->fetchAll(PDO::FETCH_ASSOC);
                    $totalPages = ceil(count($TotalResult) / $numsOfPosts);

                    $statement = $pdo->prepare("SELECT * FROM posts LIMIT $offset,$numsOfPosts");
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                    if ($result) :
                        foreach ($result as $value) :
                    ?>
                            <div class="col-md-6">
                                <!-- Box Comment -->
                                <div class="card card-widget">
                                    <div class="card-header">
                                        <div class="card-title" style="float: none;">
                                            <h4 class="text-center"><a style="color:#272727" href="blogDetail.php?id=<?= $value['id'] ?>&pageNo=<?= $pageNo ?>"><?= escape($value['title']) ?></a></h4>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <a href="blogDetail.php?id=<?= $value['id'] ?>&pageNo=<?= $pageNo ?>"><img class="img-fluid pad" src="admin/images/<?= $value['image'] ?>" alt="Photo"></a>
                                        <p><?= escape(substr($value['content'], 0, 200)) ?>&nbsp;&nbsp;<a href="blogDetail.php?id=<?= $value['id'] ?>&pageNo=<?= $pageNo ?>">See more...</a></p>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
                <!-- pagination  -->
                <nav aria-label="Page navigation example" style="float: right;">
                    <ul class="pagination">
                        <li class="page-item <?php if ($pageNo <= 1) echo 'disabled' ?>"><a class="page-link" href="index.php?pageNo=<?= 1 ?>">First</a></li>
                        <li class="page-item <?php if ($pageNo <= 1) echo 'disabled' ?>"><a class="page-link" href="index.php?pageNo=<?= $pageNo - 1 ?>">&laquo;</a></li>
                        <li class="page-item"><a class="page-link" href="#"><?= $pageNo . ' of ' . $totalPages ?></a></li>
                        <li class="page-item <?php if ($pageNo >= $totalPages) echo 'disabled' ?>"><a class="page-link" href="index.php?pageNo=<?= $pageNo + 1 ?>">&raquo;</a></li>
                        <li class="page-item <?php if ($pageNo >= $totalPages) echo 'disabled' ?>"><a class="page-link" href="index.php?pageNo=<?= $totalPages ?>">Last</a></li>
                    </ul>
                </nav>
            </section>
            <!-- /.content -->

            <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
                <i class="fas fa-chevron-up"></i>
            </a>
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer text-center" style="margin-left: 0 !important;clear:both">
            <div class="float-right d-none d-sm-block ">
                <a href="logout.php" type="button" class="btn btn-default">Logout</a>
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