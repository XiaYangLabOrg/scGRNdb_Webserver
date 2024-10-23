<?php
$env=parse_ini_file(".env");
$conn = include_once("mysql_connect.php");
if (is_null($conn)){
    echo "closing";
    die();
}
// if ($_SERVER['REQUEST_METHOD']=='POST'){
//     echo "Running SCING";
//     // echo var_dump($_POST);
// } else{
//     echo "Error no post request";
// }
?>

<?php
if (isset($_POST['sessionID'])){
    $sessionID = $_POST['sessionID'];
}
$errors = [];
$data = [];
// check form inputs and prepare config file
$hoffmanDir = "/u/scratch/m/mergeome/scgrndb/" . $sessionID . "/" . "run_scing/";
$hoffmanDataDir = "Data";
$python_cmd = "/opt/miniconda3/bin/python";
$server_config_out = "Data/session/" . $sessionID . "/run_scing/config.py";
$server_run_command_out = "Data/session/" . $sessionID ."/run_scing/run_commands.sh";

$config_run = $python_cmd . " ./python_scripts/make_scing_config.py --step scing --main_branch_path ~/scGRNdb_project/scNetworkAtlas" . " --base_dir " . $hoffmanDir . $hoffmanDataDir . "/";
$config_run = $config_run . " --config_outfile " . $server_config_out;
$config_run = $config_run . " --run_pipeline_commands " . $server_run_command_out;

if (empty($_FILES['supercellInputDir'])) {
    $errors['supercellInputDir'] = 'supercellInputDir is required.';
} 
else {
    $adataFilename=$_FILES['supercellInputDir']['name'];
    $adataCurrFilepath = $_FILES['supercellInputDir']['tmp_name']; 
    $config_run = $config_run . " --adata_dir " . $hoffmanDataDir . " --tissue_celltype_file tissue_celltype_file.txt --supercell_dir supercells --supercell_file supercells.txt";
}

if (empty($_POST['supercellCelltypeColumn'])) {
    $errors['supercellCelltypeColumn'] = 'supercellCelltypeColumn is required.';
} else {
    $config_run = $config_run . " --celltype_column " . $_POST['supercellCelltypeColumn'];

}
if (isset($_POST['cellMappingToggle'])){
    if (empty($_FILES['cellMappingNamingFile'])) {
        $errors['cellMappingNamingFile'] = 'cellMappingNamingFile is required.';
    } 
    else {
        $cellMappingFilename="cellmapping.txt";
        $cellMappingCurrFilepath = $_FILES['cellMappingNamingFile']['tmp_name'];
        $config_run = $config_run . " --cell_mapping_mapping_file " . $cellMappingFilename;

    }

}
if (empty($_POST['numIntNetworks'])) {
    $errors['numIntNetworks'] = 'numIntNetworks is required.';
}
else{
    $config_run = $config_run . " --num_networks " . $_POST['numIntNetworks'] . " --intermediate_dir saved_networks/intermediate_networks --build_ncore 1 --build_mem_per_core 16";
}
if (empty($_POST['consensus'])) {
    $errors['consensus'] = 'consensus is required.';
}
else {
    $config_run = $config_run . " --consensus " . $_POST['consensus'] . " --final_outdir saved_networks/final_networks --merge_ncore 12 --merge_mem_per_core 4";
}
if (empty($_POST['moduleMinSize'])) {
    $errors['moduleMinSize'] = 'moduleMinSize is required.';
}
if (empty($_POST['moduleMaxSize'])) {
    $errors['moduleMaxSize'] = 'moduleMaxSize is required.';
}
if (empty($_POST['pathwayDB'])) {
    $errors['pathwayDB'] = 'pathwayDB is required.';
}

if (!empty($errors)) {
    $data['success'] = false;
    $data['errors'] = $errors;
    echo json_encode($data) . var_dump($_POST) . var_dump($_FILES);
    die();
} 

// make config file
$config_run = escapeshellcmd($config_run);
echo $config_run . "\n";
$output = shell_exec($config_run);
echo $output . "\n";


// copy files to hoffman
try {
    $connection = ssh2_connect($env["HOFFMAN2_SERVER_IP"], 22);
    ssh2_auth_password($connection, $env["SCING_USERNAME"], $env["SCING_PASSWORD"]);
    ssh2_exec($connection, "mkdir -p " . $hoffmanDir . $hoffmanDataDir . "/");
    ssh2_scp_send($connection, $adataCurrFilepath, $hoffmanDir . $hoffmanDataDir . "/" . basename($adataFilename), 0644);
    if (isset($_POST['cellMappingToggle'])){
        ssh2_scp_send($connection, $cellMappingCurrFilepath, $hoffmanDir . $hoffmanDataDir . "/" . basename($cellMappingFilename), 0644);
    }
    ssh2_scp_send($connection, $server_config_out, $hoffmanDir . "config.py", 0644);
    ssh2_scp_send($connection, $server_run_command_out, $hoffmanDir . "run_commands.sh", 0644);
} catch(Error $e){
    echo "ssh2 error: " . $e -> getMessage();
    echo "Aborting";
    die();
}


// add session to database
$sql = "INSERT INTO scing_pipeline (sessionID, configFilepath) VALUES (\"" . $sessionID . "\", \"Data/session/". $sessionID . "/config.py\")";
echo $sql;
$conn -> exec($sql);

// else {
//     $data['success'] = true;
//     $data['message'] = 'Success!';
// }



echo "success";