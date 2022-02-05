<?php
session_start();
require '../config/config.php';
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php");
}

$statement = $pdo->prepare("SELECT * FROM posts WHERE id=:id");
$statement->execute([':id' => $_GET['id']]);
$result = $statement->fetch(PDO::FETCH_OBJ);

if ($_POST) {
    if (!$_FILES['image']['error']) {

        $filePath = 'images/' . $_FILES['image']['name'];
        $imageType = pathinfo($filePath, PATHINFO_EXTENSION);
        if ($imageType !== 'png' && $imageType !== 'jpg' && $imageType !== 'jpeg') {
            echo "<script>alert('Image type must be png,jpg or jpeg')</script>";
        } else {
            move_uploaded_file($_FILES['image']['tmp_name'], $filePath);
            $updateStatement = $pdo->prepare("UPDATE posts SET title=:title,content=:content,image=:image WHERE id=:id");
            $response = $updateStatement->execute([
                ':title' => $_POST['title'],
                ':content' => $_POST['content'],
                ':image' => $_FILES['image']['name'],
                ':id' => $_POST['id']
            ]);
            if ($response) {
                echo "<script>alert('Successfully Updated Post');window.location.href='index.php'</script>";
            }
        }
    } else {
        $updateStatement = $pdo->prepare("UPDATE posts SET title=:title,content=:content WHERE id=:id");
        $response = $updateStatement->execute([
            ':title' => $_POST['title'],
            ':content' => $_POST['content'],
            ':id' => $_POST['id']
        ]);
        if ($response) {
            echo "<script>alert('Successfully Updated Post');window.location.href='index.php'</script>";
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
                        <h3 class="card-title">Edit Posts</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="edit.php" class="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" class="form-control" name='id' value="<?= $result->id ?>">
                            <div class="form-group">
                                <label type="text" class="form-label">Title</label>
                                <input type="text" class="form-control" name='title' value="<?= $result->title ?>" required>
                            </div>
                            <div class="form-group">
                                <label type="text" class="form-label">Content</label>
                                <textarea name="content" rows="5" class="form-control" required><?= $result->content ?></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Image</label><br>
                                <img src="images/<?= $result->image ?>" width="400" height="200"><br><br>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="submit" value="SUBMIT" class="btn btn-primary">
                                <a href="index.php" type="button" class="btn btn-default">Back</a>
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