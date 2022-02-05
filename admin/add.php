<?php
session_start();
require '../config/config.php';
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php");
}
if ($_POST) {
    $filePath = 'images/' . $_FILES['image']['name'];
    $imageType = pathinfo($filePath, PATHINFO_EXTENSION);
    if ($imageType !== 'png' && $imageType !== 'jpg' && $imageType !== 'jpeg') {
        echo "<script>alert('Image type must be png,jpg or jpeg')</script>";
    } else {
        move_uploaded_file($_FILES['image']['tmp_name'], $filePath);
        $statement = $pdo->prepare("INSERT INTO posts(title,content,image,author_id) VALUES (:title,:content,:image,:author_id)");
        $result = $statement->execute([
            ':title' => $_POST['title'],
            ':content' => $_POST['content'],
            ':image' => $_FILES['image']['name'],
            ':author_id' => $_SESSION['user_id']
        ]);
        if ($result) {
            echo "<script>alert('Successfully Uploaded Post');window.location.href='index.php'</script>";
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
                        <h3 class="card-title">Create New Blogs or Posts</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="add.php" class="" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label type="text" class="form-label">Title</label>
                                <input type="text" class="form-control" name='title' required>
                            </div>
                            <div class="form-group">
                                <label type="text" class="form-label">Content</label>
                                <textarea name="content" rows="5" class="form-control" required></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Image</label>
                                <input type="file" name="image" class="form-control" required>
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