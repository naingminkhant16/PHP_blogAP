<?php
session_start();
require '../config/config.php';
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php?error=login");
}

$statement = $pdo->prepare("SELECT * FROM users");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_OBJ);
?>
<?php include 'header.html' ?>
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Manage Users</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-striped table-dark">
                            <thead>
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">E-mail</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result) :
                                    foreach ($result as $user) : ?>
                                        <tr>
                                            <th scope="row"><?= $user->id ?></th>
                                            <td><?= $user->name ?></td>
                                            <td><?= $user->email ?></td>
                                            <td><?php if ($user->role > 0) {
                                                    echo 'Admin';
                                                } else {
                                                    echo "User";
                                                }
                                                ?></td>
                                            <td><?php if($_SESSION['user_id']==$user->id){
                                                echo "###";
                                                }else{?>
                                                <a href="changeRole.php?id=<?= $user->id ?>& role=<?= $user->role ?>" class="btn btn-sm btn-primary">Change Role</a>
                                                <a href="deleteUser.php" class="btn btn-sm btn-danger">Delete</a>
                                                <?php }?>
                                            </td>
                                        </tr>
                                <?php endforeach;
                                endif; ?>
                            </tbody>
                        </table>

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