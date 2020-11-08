<?php

if (!empty($_FILES['files']['name'][0])) {
    $files = $_FILES['files'];

    $uploaded = [];
    $failed = [];

    $allowed = ['jpg', 'png', 'gif'];

    foreach ($files['name'] as $position => $fileName) {
        $fileTmp = $files['tmp_name'][$position];
        $fileSize = $files['size'][$position];
        $fileError = $files['error'][$position];
        $fileExt = explode('.', $fileName);
        $fileExt = end($fileExt);

        if (in_array($fileExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize <= 1048576) {
                    $fileNameNew = uniqid() . '.' . $fileExt;
                    $fileDestination = 'uploads/' . $fileNameNew;

                    if (move_uploaded_file($fileTmp, $fileDestination)) {
                        $uploaded[$position] = $fileDestination;
                        header('Location: /upload.php');
                    } else {
                        $failed[$position] = $fileName . ' n\'a pas pu être téléchargé';
                    }
                } else {
                    $failed[$position] = $fileName . ' est trop volumineux, il ne doit pas dépasser 1Mo';
                }
            } else {
                $failed[$position] = $fileName . ': Echec de l\'envoi. Code erreur : ' . $fileError;
            }
        } else {
            $failed[$position] = $fileName . ' : les fichiers de type .' . $fileExt . ' ne sont pas autorisés';
        }
    }
}
?>

<form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="files[]" multiple>
    <input type="submit" value="Upload">
</form>

<?php
if (!empty($failed)) {
?> <ul> <?php
    foreach ($failed as $fail) {
    ?> <li> <?= $fail ?> </li> <?php
    }
?> </ul> <?php
}

$pictures = new FilesystemIterator('uploads/');
foreach ($pictures as $picture) {
    ?><figure>
        <img src="<?=$picture;?>">
        <figcaption><?=$picture;?></figcaption>
    </figure>
    <?php }

?>







