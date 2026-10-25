<?php
$confirmation = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && !empty($_SERVER['CONTENT_LENGTH'])) {
    $errors[] = "Sorry, the screenshot is too big.";
}

if (
    isset($_POST["submit"]) && !in_array("DL-SVCWeb-RW", $groups, true)
    && (in_array("DL-TicketingRDP-Allow", $groups, true) || in_array("DL-HRTicketing-RW", $groups, true))
) {
    $description = $_POST['description'] ?? '';
    $assistType = $_POST['assist'] ?? '';
    $username = $_SESSION['user'];
    $screenshotError = $_FILES["screenshotUpload"]["error"] ?? UPLOAD_ERR_NO_FILE;
    $uploadOk = 0;
    $name = "";

    if ($assistType == "onsite") {
        $name = "General";
        $confirmation[] = "Your ticket was successfully sent to the {$name} queue.";
    } elseif ($assistType == "remote") {
        $name = "Restricted";
        $confirmation[] = "Your ticket was successfully sent to the {$name} queue.";
    } else {
        $errors[] = "Please choose where we should assist you.";
    }

    if (empty(trim($description))) {
        $errors[] = "Sorry, the description cannot be empty.";
    }

    $uuid = bin2hex(random_bytes(16));
    $date = date("Ymd");
    $directory = __DIR__ . "/../../../Tickets/{$name}/{$date}_{$uuid}/";

    if ($screenshotError === UPLOAD_ERR_OK) {
        $screenshotFile = $directory . basename($_FILES["screenshotUpload"]["name"]);
        $imageFileType = strtolower(pathinfo($screenshotFile, PATHINFO_EXTENSION));
        $uploadOk = 1;

        if (getimagesize($_FILES["screenshotUpload"]["tmp_name"]) === false) {
            $errors[] = "Sorry, the screenshot is not an image.";
            $uploadOk = 0;
        }

        if ($_FILES["screenshotUpload"]["size"] > 500000) {
            $errors[] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Guys, we should really block more file types, but this works for now. 
        if (
            $imageFileType == "php" || $imageFileType == "phtml" || $imageFileType == "phar"
            || $imageFileType == "exe" || $imageFileType == "bat" || $imageFileType == "ps1"
            || $imageFileType == "sh" || $imageFileType == "js" || $imageFileType == "html"
        ) {
            $errors[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
    } elseif ($screenshotError === UPLOAD_ERR_INI_SIZE) {
        $errors[] = "Sorry, the screenshot is too big.";
    } elseif ($screenshotError === UPLOAD_ERR_PARTIAL) {
        $errors[] = "Sorry, the screenshot was partially uploaded. Try again.";
    } elseif ($screenshotError === UPLOAD_ERR_NO_TMP_DIR || $screenshotError === UPLOAD_ERR_CANT_WRITE) {
        $errors[] = "Sorry, you do not have permissions to upload a screenshot.";
    } elseif ($screenshotError !== UPLOAD_ERR_NO_FILE) {
        $errors[] = "Sorry, the screenshot could not be uploaded.";
    }

    if (empty($errors)) {
        mkdir($directory);

        if ($uploadOk) {
            $tmp = $_FILES['screenshotUpload']["tmp_name"];
            if (is_uploaded_file($tmp) && copy($tmp, $screenshotFile)) {
                unlink($tmp);
                $confirmation[] = "The file " . htmlspecialchars(basename($_FILES["screenshotUpload"]["name"])) . " has been uploaded.";
            } else {
                $errors[] = "Sorry, there was an error uploading your file.";
            }
        }

        if (empty($errors)) {
            $ticket = ["profile" => $username, "metadata" => ["description" => $description, "assist" => $assistType, "date" => $date, "screenshot" => $uploadOk]];
            file_put_contents($directory . "{$name}_{$uuid}_ticket", json_encode($ticket));
        } else {
            if ($uploadOk && is_file($screenshotFile)) {
                unlink($screenshotFile);
            }
            if (is_dir($directory)) {
                rmdir($directory);
            }
        }
    }
}
?>