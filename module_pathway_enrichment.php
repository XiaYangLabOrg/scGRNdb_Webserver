<?php
include "functions.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$env=parse_ini_file(".env");
$conn = include_once("mysql_connect.php");
if (is_null($conn)){
    echo "closing";
    die();
}

$output_array = [];
debug_to_console($_FILES, $output_array);

$networkInput = $_FILES['networkInputFile'];
$moduleMinSize = $_POST['moduleMinSize'];
$moduleMaxSize = $_POST['moduleMaxSize'];
$pathwayName = $_POST['pathwayDB'];
$species = $_POST['species'];
$sessionID = $_POST['sessionID'];
$errors = [];

// check form inputs and prepare config file
// $hoffmanDir = "/u/scratch/m/mergeome/scgrndb/" . $sessionID . "/" . "module_pathways/";
// $hoffmanDataDir = "Data"; // where to store network
$python_cmd = "/opt/miniconda3/envs/networks/bin/python";
$server_session_dir = "Data/session/" . $sessionID . "/module_pathways";
$server_network_dir = $server_session_dir . "/networks";
$server_config_out = $server_session_dir . "/config.py";
$server_run_command_out = $server_session_dir . "/run_commands.sh";

// prepare config file
$config_run = $python_cmd . " ./python_scripts/make_scing_config.py --step module_pathway --main_branch_path ~/Desktop/Yang_Lab/scNetworkAtlas" . " --base_dir " . $server_session_dir . "/";
$config_run = $config_run . " --config_outfile " . $server_config_out;
$config_run = $config_run . " --run_pipeline_commands " . $server_run_command_out;

// module detection arguments
if (isset($networkInput)) {
    if (isTextFile($networkInput)){
        $networkFilename="network.txt";
        if (!file_exists($server_network_dir)) {
            mkdir($server_network_dir, 0777, true);
        }
        $networkCurrFilepath = $networkInput['tmp_name']; 
        move_uploaded_file($networkCurrFilepath, $server_network_dir . "/" . $networkFilename);
        $config_run = $config_run . " --module_network_dir networks" . " --module_outdir gene_memberships";
    }
    else {
        $errors['networkInputFile'] = 'networkInputFile is not txt';
    }
} 
else {
    $errors['networkInputFile'] = 'networkInputFile is required.';
}
if (isset($moduleMinSize)) {
    $config_run = $config_run . " --min_module_size " . $moduleMinSize;
} else{
    $errors['moduleMinSize'] = 'moduleMinSize is required.';
}

if (isset($moduleMaxSize)){
    $config_run = $config_run . " --max_module_size " . $moduleMaxSize;
} else {
    $errors['moduleMaxSize'] = 'moduleMaxSize is required.';
}

// pathway enrichment arguments
if (isset($pathwayName) & isset($species)){
    $sql = "SELECT pathwayFile, abbrev FROM pathway_files WHERE species = :species AND pathway = :pathway";
    $stmt = $conn -> prepare($sql);
    $stmt -> bindParam(':species', $species, PDO::PARAM_STR);
    $stmt -> bindParam(':pathway', $pathwayName, PDO::PARAM_STR);
    $stmt -> execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC); // fetch first row as associative array
    if ($result) {
        // add to config run
        $config_run = $config_run . " --enrichment_pathway_file " . $result['pathwayFile'];
        $config_run = $config_run . " --enrichment_pathway_db " . $result['abbrev'];
        $pathway_outfile = pathinfo($networkFilename, PATHINFO_FILENAME) . ".gene_membership." . $result['abbrev'] . ".enrichment.txt";
    } else {
        $errors['pathwayDB_species'] = 'pathwayDB ' . $pathwayName . ' not found. Select another database.';
    }
} else {
    $errors['pathwayDB_species'] = 'both pathwayDB and species are required.';
}

// kill if any errors
if (count(value: $errors) > 0){
    debug_to_console($errors, $output_array);
    die();
}

// make config file
$config_run = escapeshellcmd($config_run);
// debug_to_console($config_run, $output_array);
$output = [];
$returnVar = 0;
// run command
exec($config_run, $output, $returnVar);
if ($returnVar === 0) {
    debug_to_console("Command executed successfully. Output: " . implode("\n", $output), $output_array);
} else {
    debug_to_console("Command failed with exit status $returnVar. Output: " . implode("\n", $output), $output_array);
}
// $output = shell_exec($config_run);
// debug_to_console($output);

// // copy files to hoffman
// try {
//     $connection = ssh2_connect($env["MERGEOMICS_SERVER_IP"], 22);
//     ssh2_auth_password($connection, $env["MERGEOMICS_SERVER_USERNAME"], $env["MERGEOMICS_SERVER_PASSWORD"]);
//     ssh2_exec($connection, "mkdir -p " . $hoffmanDir . $hoffmanDataDir . "/");
//     ssh2_scp_send($connection, $networkCurrFilepath, $hoffmanDir . $hoffmanDataDir . "/" . basename($networkFilename), 0644);
//     ssh2_scp_send($connection, $server_config_out, $hoffmanDir . "config.py", 0644);
//     ssh2_scp_send($connection, $server_run_command_out, $hoffmanDir . "run_commands.sh", 0644);
//     debug_to_console("success copied to hoffman");
// } catch(Error $e){
//     debug_to_console("ssh2 error: " . $e -> getMessage());
//     echo "Aborting";
//     die();
// }

// execute module script
chdir($server_session_dir);
$scriptOutput = [];
$scriptReturnVar = 0;
exec("mkdir -p jobout; nohup bash run_commands.sh > jobout/output.log 2>&1 & ", $scriptOutput, $scriptReturnVar);
if ($returnVar === 0) {
    debug_to_console("bash run_commands.sh. Output: " . implode("\n", $scriptOutput), $output_array);
} else {
    debug_to_console("bash run_commands.sh failed with exit status $returnVar. Output: " . implode("\n", $scriptOutput), $output_array);
}

// return output file name so php can check its progress
echo json_encode([
    "debug" => $output_array,
    "module_outfile" => $server_session_dir . "/gene_memberships" . "/" . pathinfo($networkFilename, PATHINFO_FILENAME) . ".gene_membership.txt",
    "pathway_outfile" => $server_session_dir . "/enrichment" . "/" . $pathway_outfile,
]);


