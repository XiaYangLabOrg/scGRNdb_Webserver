<?php

function validateInt($int){
    return filter_var($int, FILTER_VALIDATE_INT);
}

function validateEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isTextFile($file){
    // 1. Check for upload errors
    if ($file['error'] === UPLOAD_ERR_OK) {
        // 2. Check file extension
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (strtolower($fileExtension) !== 'txt') {
            echo "Invalid file extension. Only .txt files are allowed.";
            return NULL;
        }
        
        // 3. Check MIME type (optional but recommended)
        $fileTmpName = $file['tmp_name'];
        $fileMimeType = mime_content_type($fileTmpName);  // Get the MIME type
        
        if ($fileMimeType !== 'text/plain') {
            echo "Invalid MIME type. The file must be a plain text file.";
            return NULL;
        }
    } else {
        echo "Error during file upload: " . $file['error'];
        return NULL;
    }
    return true;
}


function debug_to_console($data, &$console_list=NULL) {
    $output = $data;
    if (is_array($output)) {
        $output = json_encode($output);
    } elseif (is_string($output)){
        // $output = json_encode(explode("\n",$output));
        $output = str_replace("\n","\\n", $output);
    }
    if (isset($console_list)){
        $console_list[] = $output;
        return $console_list;
    } else{
        echo "<script>console.log(" . $output . ");</script>\n";
        return NULL;
    }
    

}