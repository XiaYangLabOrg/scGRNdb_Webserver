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
<?php include_once "head.php" ?>

<?php include_once "header.php" ?>


<!-- overview figures
- atlas figure
- number of cell types
- number of tissues
- network stats -->



<body>
<div class="container-fluid">
  <h1 class="text-center" style="font-size: 100px">scGRNdb</h1>
</div>
<!-- atlas fig -->
<div id="Home-Overview" class="container-fluid">
    <div class="row align-items-center my-5" style="background-color: var(--tertiary_color)">
        <div id="description" class="col text-start">
          <p>scGRNdb is a database of over 1000 tissue- and cell-type- specific gene regulatory networks (GRNs) and a web server GRN construction and analysis. 
            You can provide your own single cell data to build a custom network or use one of the pre-built cell type networks across 8 human and mouse single cell atlases.
            We use the Single Cell Integrative Gene regulatory network (SCING) method, as an unbiased GRN method that has been demonstrated to outperform other GRN methods in predicting 
            downstream effects of single cell gene knockout experiments. </p>
        </div>
        <div class="col-3 text-center">
          <img class="img-fluid" src="include/pictures/tissue_network_num_top20.png" alt="overview fig">
        </div>
    </div>
    <div class="row align-items-center my-5" style="background-color: var(--secondary_color)">
      <div id="description" class="col text-start">
        Network Analysis Tools include module detection, module-geneset enrichment, disease modeling, and key driver analysis
      </div>
      <div class="col-md-3 text-center">
        <img class="img-fluid" src="include/pictures/Module Pathway Enrichment.png" alt="network analysis fig">
      </div>
    </div>
</div>
  
</body>
<footer>
  <div class="container-fluid my-5">
    Footer
  </div>
</footer>
</html>