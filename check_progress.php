<?php

if (isset($_POST['filename'])) {
    if(file_exists(stripslashes($_POST['filename']))){
        echo json_encode(['status' => 'done']);
    }
    else{
        echo json_encode(['status' => 'not done', 'filename' => $_POST['filename']]);
    }
}
else{
    echo json_encode(['status' => 'not done', 'filename' => $_POST['filename']]);
}