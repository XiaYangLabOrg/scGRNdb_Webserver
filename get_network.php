<?php
    $env=parse_ini_file(".env");
    $username = $env["NEO4J_USERNAME"];
    $password = $env["NEO4J_PASSWORD"];
    $db_uri = $env["NEO4J_DB_URI"];
    $db_name = $env["NEO4J_NETWORK_DB_NAME"];
    $tissue = $_POST['tissue'];
    $celltype = $_POST['celltype'];
    $genes = stripslashes($_POST['genes']);
    $nhops = $_POST['nNodeHop'];
    
    $python_cmd = "/opt/miniconda3/envs/networks/bin/python";

    $command = 
        $python_cmd . " ./python_scripts/network_query.py" . 
        " --tissue " . escapeshellarg($tissue) . 
        " --celltype " . escapeshellarg($celltype) . 
        " --genes " . escapeshellarg($genes) . 
        " --nhops " . escapeshellarg($nhops) .
        " --db_user " . escapeshellarg($username) . 
        " --db_pwd " . escapeshellarg($password) . 
        " --db_uri " . escapeshellarg($db_uri) . 
        " --db_name " . escapeshellarg($db_name);
    
    if (isset($_POST['existingNodes'])) {
        $existingNodes = stripslashes($_POST['existingNodes']);
        $command = $command . " --existing_node_ids " . escapeshellarg($existingNodes);
    }
    if (isset($_POST['existingEdges'])) {
        $existingEdges = stripslashes($_POST['existingEdges']);
        $command = $command . " --existing_edge_ids " . escapeshellarg($existingEdges);
    }
    // echo $command . "\n";
    $start = microtime(true);
    $queryResArray = json_decode(exec($command), true);
    $exec_time = (microtime(true) - $start);
    $queryResArray["exec_time"] = $exec_time;
    // $queryResArray["genes"] = $genes;
    $queryResArray["command"] = $command;
    echo json_encode($queryResArray);
    // if (empty($_POST['existingNodes'])){
    // }
    // else{
    //     echo $command;
    // }
    