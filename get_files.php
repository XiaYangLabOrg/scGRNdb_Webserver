<?php
    // $directory = __DIR__ . '/include/networks'; // Replace with your actual directory path
    // $files = scandir($directory);

    // $output = '';
    // foreach($files as $file) {
    //     if($file != '.' && $file != '..' && (str_ends_with($file, ".csv") || str_ends_with($file,".txt"))) {
    //         $output .= "<option value='" . htmlspecialchars($file) . "'>" . htmlspecialchars($file) . "</option>";
    //     }
    // }

    // echo $output;
if ($_GET['run_func'] == "getTissues"){
    $celltype = $_GET['celltype'];
    echo getTissues($celltype);
}
elseif ($_GET['run_func'] == "getCellTypes"){
    $tissue = $_GET['tissue'];
    echo getCellTypes($tissue);
}
elseif ($_GET['run_func'] == "getPathways"){
    $species = $_GET['species'];
    echo getPathways($species);
}

// function to retrieve tissues from db
function getTissues($celltype){
    $conn = include_once("mysql_connect.php");
    $sql = "SELECT DISTINCT tissue FROM tissue_celltype_networks";
    if ($celltype != ''){
        $sql = $sql . " WHERE celltype = :celltype";
        $stmt = $conn -> prepare($sql);
        $stmt -> bindParam(":celltype", $celltype, PDO::PARAM_STR);
    } else{
        $stmt = $conn -> prepare($sql);
    }
    $stmt->execute();
    $tissues = $stmt->fetchAll(PDO::FETCH_COLUMN); // fetch all tissues as an associative array
    $conn = NULL;
    return json_encode($tissues); // return tissues in JSON format
};

// function to retrieve cell types from db
function getCellTypes($tissue){
    $conn = include_once("mysql_connect.php");
    $sql = "SELECT DISTINCT celltype FROM tissue_celltype_networks";
    if ($tissue != ''){
        $sql = $sql . " WHERE tissue = :tissue";
        $stmt = $conn -> prepare($sql);
        $stmt->bindParam(':tissue', $tissue, PDO::PARAM_STR);
    } else{
        $stmt = $conn -> prepare($sql);
    }
    $stmt->execute();

    $celltypes = $stmt->fetchALL(PDO::FETCH_COLUMN); // fetch all cell types as an associative array
    $conn = NULL;
    return json_encode($celltypes);
    
};

// function to get pathway files
function getPathways($species){
    $conn = include_once("mysql_connect.php");
    $sql = "SELECT DISTINCT pathway FROM pathway_files";
    if ($species != ''){
        $sql = $sql . " WHERE species = :species";
        $stmt = $conn -> prepare($sql);
        $stmt->bindParam(':species', $species, PDO::PARAM_STR);
    } else{
        $stmt = $conn -> prepare($sql);
    }
    $stmt->execute();

    $pathways = $stmt->fetchALL(PDO::FETCH_COLUMN); // fetch all pathways as an associative array
    $conn = NULL;
    return json_encode($pathways);
}