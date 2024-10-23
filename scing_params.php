<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>scGRNdb</title>
  <link rel="stylesheet" href="include/bootstrap-5.3.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="include/css/style.css">
</head>
<?php //include_once("analyticstracking.php") ?>
<?php include_once("head.php")?>
<?php include_once("header.php")?>
<?php include_once("session_funcs.php");
    // $sessionID = generateRandomString(10);
    $sessionID = "cR4m6g5C1T";
?>
<body>
    <h1 class="text-center">SCING</h1>
    <!-- tutorial navigation bar -->
    <ul class="nav nav-tabs justify-content-center" id="scing-nav" role="tablist">
        <li class="nav-item" role="presentation">
            <!-- <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#overview" type="button" role="tab" aria-controls="home" aria-selected="true">Overview</a></li> -->
            <button class="nav-link" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">Overview</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="configTab" data-bs-toggle="tab" data-bs-target="#configScingInputs" type="button" role="tab" aria-controls="configScingInputs" aria-selected="false">Configure Inputs</button>
        </li>
        <!-- <li class="nav-item" role="presentation">
            <button class="nav-link" id="cellmapping-tab" data-bs-toggle="tab" data-bs-target="#cellmapping" type="button" role="tab" aria-controls="cellmapping" aria-selected="false">Cell Mapping</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="supercells-tab" data-bs-toggle="tab" data-bs-target="#supercells" type="button" role="tab" aria-controls="supercells" aria-selected="false">Supercells</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="buildnetwork-tab" data-bs-toggle="tab" data-bs-target="#buildnetwork" type="button" role="tab" aria-controls="buildnetwork" aria-selected="false">Build Intermediate Networks</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="mergenetwork-tab" data-bs-toggle="tab" data-bs-target="#mergenetwork" type="button" role="tab" aria-controls="mergenetwork" aria-selected="false">Merge Intermediate Networks</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="modules-tab" data-bs-toggle="tab" data-bs-target="#modules" type="button" role="tab" aria-controls="modules" aria-selected="false">Network Modules</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pathwayenrichment-tab" data-bs-toggle="tab" data-bs-target="#pathwayenrichment" type="button" role="tab" aria-controls="pathwayenrichment" aria-selected="false">Module Pathway Enrichment</button>
        </li> -->
    </ul>
    <!-- navigation content -->
    <div class="tab-content" id="scing-nav-content">
        <div class="tab-pane fade" id="overview" role="tabpanel" aria-labelledby="overview-tab">
            SCING is a gene regulatory network (GRN) construction using a boostrapped gradient boosting approach.
            <div class="accordion accordion-flush" id="overviewAccordion">
                <h2>Steps</h2>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-heading-cellmapping">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-cellmapping" aria-expanded="false" aria-controls="flush-collapse-cellmapping">
                            Cell Mapping
                        </button>
                    </h2>
                    <div id="flush-collapse-cellmapping" class="accordion-collapse collapse" aria-labelledby="flush-heading-cellmapping" data-bs-parent="#overviewAccordion">
                        <div class="accordion-body">This is the description for Cell Mapping Step.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-heading-supercells">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-supercells" aria-expanded="false" aria-controls="flush-collapse-supercells">
                            Supercells
                        </button>
                    </h2>
                    <div id="flush-collapse-supercells" class="accordion-collapse collapse" aria-labelledby="flush-heading-supercells" data-bs-parent="#overviewAccordion">
                        <div class="accordion-body">
                            This is the description for Supercells Step
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-heading-buildnetwork">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-buildnetwork" aria-expanded="false" aria-controls="flush-collapse-buildnetwork">
                            Build Intermediate Network
                        </button>
                    </h2>
                    <div id="flush-collapse-buildnetwork" class="accordion-collapse collapse" aria-labelledby="flush-heading-buildnetwork" data-bs-parent="#overviewAccordion">
                        <div class="accordion-body">
                            This is the description for Build Network Step
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-heading-mergenetwork">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-mergenetwork" aria-expanded="false" aria-controls="flush-collapse-mergenetwork">
                            Merge Networks
                        </button>
                    </h2>
                    <div id="flush-collapse-mergenetwork" class="accordion-collapse collapse" aria-labelledby="flush-heading-mergenetwork" data-bs-parent="#overviewAccordion">
                        <div class="accordion-body">
                            This is the description for Merge Network Step
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-heading-modules">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-modules" aria-expanded="false" aria-controls="flush-collapse-modules">
                            Network Modules
                        </button>
                    </h2>
                    <div id="flush-collapse-modules" class="accordion-collapse collapse" aria-labelledby="flush-heading-modules" data-bs-parent="#overviewAccordion">
                        <div class="accordion-body">
                            This is the description for Modules Step
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-heading-pathwayenrichment">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-pathwayenrichment" aria-expanded="false" aria-controls="flush-collapse-pathwayenrichment">
                            Pathway Enrichment
                        </button>
                    </h2>
                    <div id="flush-collapse-pathwayenrichment" class="accordion-collapse collapse" aria-labelledby="flush-heading-pathwayenrichment" data-bs-parent="#overviewAccordion">
                        <div class="accordion-body">
                            This is the description for Pathway Enrichment Step
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="tab-pane fade" id="configScingInputs" role="tabpanel" aria-labelledby="config">
            This is the Configure Inputs Tab
            <div class="container">
                <form id=configScingForm action='' method="post">
                <!-- cell mapping input 
                base_dir will be defined on server side -->
                
                <!-- <div class="row" id="cellMappingFormSection">
                    <h2>Cell Mapping</h2>
                                        
                    <div class="col-auto mb-1">
                        <label for="cellMappingh5adFile" class="form-label">scRNAseq h5ad file</label>
                        <input type="file" id="cellMappingh5adFile" name="cellMappingh5adFile" class="form-control" required>
                    </div>
                    
                    <div class="col-auto mb-1">
                        <label for="cellMappingCelltypeColumn">Cell Type Column</label>
                        <input type="text" id="cellMappingCelltypeColumn" name="cellMappingCelltypeColumn" class="form-control" placeholder="cell_type" aria-describedby="cellMappingCelltypeColumnHelpBlock" required>
                        <div class="form-text"> Name of Cell Type Column in h5ad file. This will be used to separate cell types for GRN construction.</div> 
                    </div>
                </div> -->
                <!-- Supercell Inputs
                output directory, filetype, and tissue_celltype_file will be defined on server side -->
                <div class="row" id="supercellFormSection">
                    <h2>Supercells</h2>
                    
                    <div class="col-auto mb-1">
                        <label for="supercellInputDir" class="form-label">scRNAseq h5ad file</label> <br>
                        <input type="file" id="supercellInputDir" name="supercellInputDir" class="form-control" required><br>
                    </div>
                    <div class="mb-1 form-check">
                        <input type="checkbox" class="form-check-input" id="cellMappingToggle" name="cellMappingToggle">
                        <label for="cellMappingToggle">Combine any cell types?</label>
                    </div>
                    <div class="col-auto mb-1">
                        <label for="cellMappingNamingFile" class="form-label">Cell Mapping File</label>
                        <input type="file" name="cellMappingNamingFile" id="cellMappingNamingFile" class="form-control" required disabled>
                        <div class="form-text">Add a file with columns ("OLD" "NEW") defining which cell types should be combined for network construction (e.g. excitatory -> neuron, inhibitory -> neuron)</div> 
                    </div>
                    <div class="col-auto mb-1">
                        <label for="supercellCelltypeColumn" class="form-label">Cell Type Column</label><br>
                        <input type="text" id="supercellCelltypeColumn" name="supercellCelltypeColumn" class="form-control" placeholder="cell_type" required><br>
                    </div>
                </div>
                <!-- GRN Inputs
                output directory, supercell_file, ncore, mem_per_core will be defined on server side -->
                <div class="row" id="buildGRNFormSection">
                    <h2>Build Networks</h2>
                    <div class="col-auto mb-1">
                        <label for="numIntNetworks" class="form-label">Number of Intermediate Networks</label>
                        <input type="number" id="numIntNetworks" name="numIntNetworks" class="form-control" value="100">
                    </div>
                    <div class="col-auto mb-1">
                        <label for="consensus" class="form-label">Consensus Threshold</label>
                        <input type="number" class="form-control" name="consensus" id="consensus" max="1" min="0" step="0.1" value=0.5>
                    </div>
                </div>
                <!-- Module Inputs
                output directory will be defined on server side -->
                <div class="row" id="moduleFormSection">
                    <h2>Modules</h2>
                    <div class="col-auto mb-1">
                        <label for="moduleMinSize" class="form-label">Module Minimum Size</label>
                        <input type="number" name="moduleMinSize" id="moduleMinSize" class="form-control" value="10">
                        <label for="moduleMaxSize" class="form-label">Module Maximum Size</label>
                        <input type="number" name="moduleMaxSize" id="moduleMaxSize" class="form-control" value="300">
                    </div>
                </div>
                <!-- Pathway Enrichment Inputs
                output directory will be defined on server side -->
                <div class="row" id="pathwayFormSection">
                    <h2>Pathway Enrichment</h2>
                        <select name="pathwayDB" id="pathwayDB" class="form-select mx-2">
                            <option selected>Select Pathway Databases</option>
                            <option value="go_molecular_function">GO Molecular Function</option>
                            <option value="go_biological_process">GO Biological Process</option>
                            <option value="go_cellular_component">GO Celllular Component</option>
                            <option value="reactome_pathways">Reactome</option>
                            <option value="kegg_pathways">KEGG</option>
                        </select>
                </div>
                <button class="btn btn-primary" type="submit">Submit</button>
                </form>
            </div>
        </div>
    
    </div>

    </div>
</body>

<script>
    // toggle cell mapping parameters
    $(document).ready(function() {
        // toggle cell mapping form section
        $('#cellMappingToggle').click(function(){
            $("#cellMappingNamingFile").prop('disabled', (i, v) => !v); // enables/disables inputs
            // $("#cellMappingFormSection").slideToggle(1000); // opens/closes section
        });
        // submit form
        $("#configScingForm").submit(function(event) {
            // prevent submit event from default action of going to the action tag of the form element
            event.preventDefault();
            // get form data
            var form_data = new FormData(document.getElementById('configScingForm'));
            form_data.append("sessionID", "<?php echo $sessionID; ?>");
            $.ajax({
              url: 'runscing.php',
              type: 'POST',
              data: form_data,
              processData: false,
              contentType: false,
              success: function(response){
                // alert('success');
                console.log(response)
              },
              error: function(error){
                alert("error, check console");
              }
            })
      });
    });

</script>
</html>