<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>scGRNdb</title>
    <?php include_once "head.php" ?>

</head>
<?php //include_once("analyticstracking.php") ?>

<body>
    <?php include_once "header.php" ?>
    <h1 class="text-center">Explore Networks</h1>

    <!-- navigation bar -->
    <ul class="nav nav-tabs justify-content-right mx-3" id="network-nav" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="network-tab" data-bs-toggle="tab" data-bs-target="#network" type="button" role="tab" aria-controls="network" aria-selected="true">Network Query</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="geneset-tab" data-bs-toggle="tab" data-bs-target="#geneset" type="button" role="tab" aria-controls="geneset" aria-selected="false">Gene Set Query</button>
        </li>
        <!-- <li class="nav-item" role="presentation">
            <button class="nav-link" id="disease-tab" data-bs-toggle="tab" data-bs-target="#disease" type="button" role="tab" aria-controls="disease" aria-selected="false">Disease Query</button>
        </li> -->
    </ul>
    <!-- navigation content -->
    <div class="container">
        <div class="tab-content" id="network-nav-content">
            <div class="tab-pane fade" id="network" role="tabpanel" aria-labelledby="network-tab">
                <form id="network-form" action="network_query.php" method="GET">
                    <div class="form-group">
                        <!-- <label for="fileSelect">Select a file:</label>
                        <select class="form-control" id="fileSelect" name="selectedNetworkFile">
                            <option value="">Pick a Network</option>
                        </select> -->
                        <label for="selectTissue">Pick a Tissue</label>
                        <select name="selectTissue" id="selectTissue" class="form-control" required>
                            <option value="">--Select Tissue--</option>
                        </select>
                        <label for="selectCellType">Pick a Cell Type</label>
                        <select name="selectCellType" id="selectCellType" class="form-control" required>
                            <option value="">--Select Cell Type--</option>
                        </select>
                        <label for="inputGenes">List of Genes (comma separated)</label>
                        <input type="text" name="inputGenes" id="inputGenes" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Next</button>
                </form>
            </div>
            <div class="tab-pane fade" id="geneset" role="tabpanel" aria-labelledby="geneset-tab">
                <form action="#" id="geneset-form">
                    <div class="form-group">
                        <label for="selectGeneset">Pick a Geneset</label>
                        <select name="selectGeneset" id="selectGeneset" class="form-control" required>
                            <option value=""> --Select Geneset-- </option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Next</button>
                </form>
            </div>
        </div>
    </div>
    
</body>

<script>
    // ajax query to populate tissue dropdown
    function populateTissues(selectedCellType) {
        let selectedTissue = $('#selectTissue').val();
        // append tissue names to #tissueSelect
        $.ajax({
            url:'get_files.php',
            type: 'GET',
            data: {run_func: 'getTissues', celltype: selectedCellType},
            success: function(response){
                console.log(response)
                let tissues = JSON.parse(response)
                let tissueDropdown = $('#selectTissue');
                tissueDropdown.empty(); // Clear the existing options
                tissueDropdown.append(new Option(text='--Select Tissue--', value=''));
                tissues.forEach(function(tissue){
                    tissueDropdown.append(new Option(text=tissue, value=tissue))
                })
                if (selectedTissue && tissues.includes(selectedTissue)){
                    tissueDropdown.val(selectedTissue)
                }
            },
            error: function(error){
                alert(error)
            }
        });
    }
    // ajax query to populate cell type dropdown
    function populateCellTypes(selectedTissue){
        let selectedCellType = $('#selectCellType').val();
        $.ajax({
            url:'get_files.php',
            type: 'GET',
            data: {run_func: 'getCellTypes', tissue:selectedTissue},
            success: function(response){
                console.log(response)
                let celltypes = JSON.parse(response)
                let celltypesDropdown = $('#selectCellType');
                celltypesDropdown.empty(); // Clear the existing options
                celltypesDropdown.append(new Option(text='--Select Cell Type--', value=''));
                celltypes.forEach(function(celltype){
                    celltypesDropdown.append(new Option(text=celltype, value=celltype))
                })
                if (selectedCellType && celltypes.includes(selectedCellType)){
                    celltypesDropdown.val(selectedCellType)
                }
            },
            error: function(error){
                alert(error)
            }
        });
    }
    // Populate select dropdown with files
    $(document).ready(function() {
        let selectedTissue = $('#selectTissue').val();
        let selectedCellType = $('#selectCellType').val();
        // append tissue names to #tissueSelect
        populateTissues(selectedCellType)
        // append cell type names to #selectCellType
        populateCellTypes(selectedTissue)
       
    });

$("#selectTissue").change(function() {
    let selectedTissue = $('#selectTissue').val();
    populateCellTypes(selectedTissue);
});

$("#selectCellType").change(function() {
    let selectedCellType = $('#selectCellType').val();
    populateTissues(selectedCellType);
});

</script>

</html>