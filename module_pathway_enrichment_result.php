<?php
include "functions.php";
if (isset($_GET['sessionID']) & isset($_GET['step'])){
    debug_to_console($_GET);
    $sessionID = $_GET['sessionID'];
    $step = $_GET['step'];
    $moduleDownloadFile = "Data/session/" . $sessionID . "/module_pathways/gene_memberships/network.gene_membership.txt";
    $pathwayDownloadFile = "Data/session/" . $sessionID . "/module_pathways/enrichment/network.gene_membership.GOBP.enrichment.txt";
}
?>

<div class="container">
    <div id="module-result" class="row d-flex .flex-wrap" hidden>
        <div class="col"><p>Module detection is complete!</p></div>
        <div class="col"><button class="btn btn-primary"><a href=<?php echo $moduleDownloadFile;?> download="network.gene_membership.txt">Download Modules</a></button></div>
    </div>
    <div id="pathway-result" class="row d-flex .flex-wrap" hidden>
        <div class="col"><p>Pathway Enrichment is complete!</p></div>
        <div class="col"><button class="btn btn-primary"><a href=<?php echo $pathwayDownloadFile;?> download="network.pathway_enrichment.txt">Download Pathway Enrichment</a></button></div>
    </div>
</div>


<script>
    $(document).ready(function(){
        let step = "<?php echo $step;?>"
        if (step == 'module'){
            $('#module-result').show();
        }
        if (step == 'pathway')
            $('#pathway-result').show();
    })
</script>