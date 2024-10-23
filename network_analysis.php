<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>scGRNdb</title>
    <?php include_once "head.php" ?>
</head>
<?php include_once "header.php" ?>
<?php include_once("session_funcs.php");
    // $sessionID = generateRandomString(10);
    $sessionID = "cR4m6g5C1T";
?>
<body>
    <h1 style="text-align: center">Network Analysis</h1>
    <div class="container">
        <div class="nav nav-pills d-flex justify-content-around" id="Network-Analysis-Main-Btns" role="tablist">
            <button class="nav-link" type="button" role="presentation" data-bs-toggle="pill" data-bs-target="#ModPathTab">Module Pathway Enrichment</button>
            <button class="nav-link" type="button" role="presentation" data-bs-toggle="pill" data-bs-target="#GeneSetTab">Gene Set Modeling Analysis</button>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade" id="ModPathTab" role="tab-panel" >
                <!-- toggle headers -->
                <div class="nav nav-tabs d-flex" id="mod-pathway-headers" role="tablist">
                    <button class="nav-link file-btn active" type="button" role="presentation" data-bs-toggle="tab" data-bs-target="#mod-pathway-form">File Inputs</button>
                    <button class="nav-link review-btn" type="button" role="presentation" data-bs-toggle="tab" data-bs-target="#mod-path-review" hidden>Review Files</button>
                </div>
                <div class="tab-content">
                    <!-- module pathway form tab -->
                    <form class="tab-pane fade show active" id="mod-pathway-form" action="#" method="POST" enctype="multipart/form-data" role="tab-panel">
                        <!-- Module Inputs
                        output directory will be defined on server side -->
                        <div class="row" id="moduleFormSection">
                            <h2>Modules</h2>
                            <div class="col-auto mb-1">
                                <label for="networkInput">Network txt file</label>
                                <input type="file" id="networkInput" name="networkInputFile" class="form-control" required>
                                <div class="form-text">Add a file with columns ("HEAD" "TAIL" "WEIGHT") defining the head node, tail node, and edge weight. Nodes are gene names in HCNG or MGI symbol.</div> 
                                <label for="moduleMinSize" class="form-label">Module Minimum Size</label>
                                <input type="number" name="moduleMinSize" id="moduleMinSize" class="form-control" value="10">
                                <label for="moduleMaxSize" class="form-label">Module Maximum Size</label>
                                <input type="number" name="moduleMaxSize" id="moduleMaxSize" class="form-control" value="300">
                            </div>
                        </div>
                        <div class="row" id="pathwayFormSection">
                        <h2>Pathway Enrichment</h2>
                            <div class="col-auto mb-1">
                                <select name="pathwayDB" id="pathwayDB" class="form-select" required>
                                    <option value="" selected>--Select Pathway Databases--</option>
                                </select>
                                <select name="species" id="species" class="form-select" required>
                                    <option value="" selected>--Select Species--</option>
                                    <option value="human">Human (homo sapien)</option>
                                    <option value="mouse">Mouse (mus musculus)</option>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </form>
                    <!-- module pathway review tab -->
                    <div class="tab-pane fade" id="mod-path-review" role="tab-panel">
                        <h1 class="text-center">Module Pathway Enrichment</h1>
                        <div class="container">
                            <p>Please check back when the job is complete</p>
                            <p hidden>Error when running analysis: </p>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="tab-pane fade" id="GeneSetTab" role="tab-panel" >
                <form action="disease_modeling.php", method="post">
                    <div class="row" id="diseaseModelingFormSection">
                        <div class="col-auto">
                            <h2>Gene Set Modeling</h2>
                            <label for="networkInput">Network txt file</label>
                            <input id="networkInput" type="file" class="form-control" required>
                            <div class="form-text">Add a file with columns ("HEAD" "TAIL" "WEIGHT") defining the head node, tail node, and edge weight.</div>             
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <!-- <ul class="nav nav-tabs mx-3" id="network-analysis-nav" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="Module-tab" data-bs-toggle="tab" data-bs-target="#network" type="button" role="tab" aria-controls="network" aria-selected="true">Module Pathway Enrichment</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="geneset-tab" data-bs-toggle="tab" data-bs-target="#geneset" type="button" role="tab" aria-controls="geneset" aria-selected="false">Gene Set Query</button>
        </li>
    </ul> -->
</body>

<script>
    // ajax query to populate pathway dropdown
    function populatePathways(selectedSpecies){
        let selectedPathway = $('#pathwayDB').val();
        // append pathway names to #pathwayDB
        $.ajax({
            url:'get_files.php',
            type: 'GET',
            data: {run_func: 'getPathways', species: selectedSpecies},
            success: function(response){
                console.log(response)
                let pathways = JSON.parse(response)
                let pathwayDropdown = $("#pathwayDB")
                pathwayDropdown.empty(); // Clear existing options
                pathwayDropdown.append(new Option(text='--Select Pathway Databases--', value=''))
                pathways.forEach(function(pathway){
                    pathwayDropdown.append(new Option(text=pathway, value=pathway))
                })
                if (selectedPathway && pathways.includes(selectedPathway)){
                    pathwayDropdown.val(selectedPathway)
                }
            },
            error: function(error){
                alert(error)
            }
        })
    }
    function checkProgress(outfile, step){
        // check progress of file every 10 seconds
        $.ajax({
            url:'check_progress.php',
            type:'POST',
            data: {filename: outfile},
            dataType: 'json',
            success: function(response){
                console.log(response)
                if (response.status == 'done'){
                    $('#mod-path-review .container').load("module_pathway_enrichment_result.php?sessionID=" + "<?php echo $sessionID?>" + "&step=" + step)
                }
                else{
                    console.log("Still Running")
                    setTimeout(checkProgress(outfile), 10000)
                }
            },
            error: function(error){
                alert(error)
            }
        })
    }

    $(document).ready(function(){
        let selectedSpecies = $('#species').val()
        // append pathway names to #pathwayDB
        populatePathways(selectedSpecies)
        
    })
    $('#species').change(function(){
        let selectedSpecies = $('#species').val()
        console.log(selectedSpecies)
        // append pathway names to #pathwayDB
        populatePathways(selectedSpecies)
    })

    // ajax to run module enrichment analysis
    $('#mod-pathway-form').submit(function(){
        event.preventDefault();
        $('#mod-pathway-headers .review-btn').removeAttr('hidden')
        // get form data
        var form_data = new FormData(document.getElementById('mod-pathway-form'));
        form_data.append("sessionID","<?php echo $sessionID; ?>");
        $.ajax({
            url: "module_pathway_enrichment.php",
            type: "POST",
            data: form_data,
            processData: false,
            contentType: false,
            dataType: 'json',
            success:function(response){
                console.log(response)
                // check progress
                checkProgress(response.module_outfile, 'module')
                checkProgress(response.pathway_outfile, 'pathway')
            },
            error: function(error){
                alert("error, check console")
            }
        })
    })
</script>
</html>