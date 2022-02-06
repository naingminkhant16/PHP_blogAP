<?php
session_start();
require '../config/config.php';
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php");
}
if ($_SESSION['user_role'] != 1) {
    header("location: login.php?error=password");
}

if ($_GET) {
    $statement = $pdo->prepare("SELECT * FROM users WHERE id=:id");
    $statement->execute([':id' => $_GET['id']]);
    $result = $statement->fetch(PDO::FETCH_OBJ);
}

if ($_POST) {
    if (isset($_POST['admin'])) {
        $role = 1;
    } else {
        $role = 0;
    }
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $statement = $pdo->prepare("SELECT * FROM users WHERE email=:email and id!=:id");
    $statement->execute([':email' => $email, ':id' => $id]);
    $user = $statement->fetchAll();

    if ($user) {
        echo "<script>alert('Email duplicated! Try again.');window.location.href='manageUsers.php'</script>";
    } else {
        $statement = $pdo->prepare("UPDATE users SET name=:name,email=:email,role=:role WHERE id=:id");
        $result = $statement->execute([
            ':name' => $name,
            ':email' => $email,
            ':role' => $role,
            ':id' => $id
        ]);
        if ($result) {
            echo "<script>alert('Successfully Updated UserData');window.location.href='manageUsers.php'</script>";
        }
    }
}
?>

<?php include 'header.php'; ?>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit User</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="editUser.php" class="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" class="form-control" name='id' value="<?= $result->id ?>">
                            <div class="form-group">
                                <label type="text" class="form-label">Name</label>
                                <input type="text" class="form-control" name='name' value="<?= $result->name ?>" required>
                            </div>
                            <div class="form-group">
                                <label type="text" class="form-label">E-mail</label>
                                <input type="email" class="form-control" name='email' value="<?= $result->email ?>" required>
                            </div>
                            <div class="form-check form-group">
                                <input type="checkbox" class="form-check-input" name="admin" value="1">
                                <label class="form-check-label">Admin</label>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="SUBMIT" class="btn btn-primary">
                                <a href="manageUsers.php" type="button" class="btn btn-default">Back</a>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include 'footer.html' ?>