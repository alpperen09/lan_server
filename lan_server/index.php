<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileToUpload'])) {
    $target_dir = "admin/uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowed_types = ["zip", "7z", "png", "jpg", "cs", "py", "bat", "exe"];
    if (!in_array($fileType, $allowed_types)) {
        echo "Üzgünüm, sadece belirli dosyalar yüklenebilir.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "Dosya " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " başarıyla yüklendi.<br>";
        } else {
            echo "Dosya yüklenirken bir hata oluştu.<br>";
        }
    }
}

if (isset($_GET['download'])) {
    $file = $_GET['download'];
    $file_path = 'admin/uploads/' . $file;

    if (file_exists($file_path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    }
}

if (isset($_GET['delete'])) {
    $fileToDelete = $_GET['delete'];
    $filePath = 'admin/uploads/' . $fileToDelete;

    if (file_exists($filePath)) {
        unlink($filePath);
        echo "Dosya başarıyla silindi.<br>";
    }
}

if (isset($_GET['file_count'])) {
    $uploaded_files = array_diff(scandir('admin/uploads'), array('.', '..'));
    echo count($uploaded_files);
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>!@n server</title>
    <link rel="icon" href="admin/images/ico.png" type="image/x-icon">
    <link rel="stylesheet" href="admin/style.css">
</head>

<body>
    <div class="container">
        <h1>Dosya Yükle ve İndir</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="fileToUpload">Dosya Seç (zip, png, jpg, cs, py, bat, exe):</label><br>
            <input type="file" name="fileToUpload" id="fileToUpload" required disabled>
            <button onclick="alert('admin değilsiniz');" type="submit">Dosyayı Yükle</button>
        </form>

        <h3>Yüklenen Dosyalar</h3>
        <ul id="file-list">
            <?php
            $uploaded_files = array_diff(scandir('admin/uploads'), array('.', '..'));
            if (count($uploaded_files) > 0) {
                foreach ($uploaded_files as $file) {
                    echo "<li>";
                    echo "<a href=\"?download=$file\">$file</a> ";
                    // echo "<a href=\"?delete=$file\" style=\"color: red; text-decoration: none;\">[Sil]</a>";
                    echo "</li>";
                }
            } else {
                echo "<li>Henüz dosya yüklenmedi.</li>";
            }
            ?>
        </ul>
    </div>
    <script src="admin/script.js"></script>
</body>
</html>