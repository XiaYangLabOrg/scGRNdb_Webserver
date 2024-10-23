<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>scGRNdb</title>
</head>
<?php //include_once("analyticstracking.php") ?>
<?php 
include_once "head.php";
include_once "header.php"; 
?>
<link rel="stylesheet" href="page_templates/cytoscape_style.css">
<link rel="stylesheet" href="page_templates/cytoscape.js-panzoom.css">
<!-- load cytoscape js -->
<script src="include/js/cytoscape.min.js"></script>
<script src="page_templates/cytoscape-panzoom.js"></script>

<?php
$tissue = isset($_GET['selectTissue']) ? $_GET['selectTissue'] : '';
$celltype = isset($_GET['selectCellType']) ? $_GET['selectCellType'] : '';
$genes = isset($_GET['inputGenes']) ? $_GET['inputGenes'] : '';
$genes = json_encode(array("genes" => explode(',', str_replace(array(', ', '\\n'), ',',$genes))));
?>

<body>
    <!-- <h1>Data from prev page</h1>
    <div class="container my-5"> 
        <p id="target-network-filename">Network file: 
        <?php 
        // if ($_SERVER['REQUEST_METHOD']=="GET"){
        //     echo htmlspecialchars($_GET["selectTissue"]);
        // }
        ?>
        </p>
    </div> -->
    <div class="container">
      <div class="row">
        <h1><?php echo $tissue . " " . $celltype . " Network"?></h1>
      </div>
      <div id=cy-interface class="row">
        <!-- cytoscape div -->
        <div id="cy" class="col-auto"></div>
        <div id='cy-params' class="col-auto mx-5">
          <form action="#" method="post" id="cy-params-form">
            <label for="node_hop_number">Neighbor Search</label>
            <input type="number" name="nNodeHop" id="n-node-hop" class="form-control" max="3" min="1" value="1">
            <button type="submit" id="reload-btn" class="btn btn-primary">Reload Network</button>
          </form>
        </div>
      </div>


    </div>
    
    
</body>
</html>


<!-- cytoscape script generating network -->
<script>
  // create cytoscape object
  var cy = cytoscape({
    container: $('#cy'),
    style: [
      {
        selector: 'node',
        style: {
          'label': 'data(id)',
          'background-color': '#66b3ff',
          'text-valign': 'center'
        }
      },
      {
        selector: 'edge',
        style: {
          'width': 2,
          'line-color': '#ccc',
          'target-arrow-color': '#ccc',
          'target-arrow-shape': 'triangle',
          'curve-style': 'bezier'
        }
      },
      {
        selector: "node:selected",
        style: {
          "border-width": "6px",
          "border-color": "#AAD8FF",
          "border-opacity": "0.5",
          "background-color": "yellow",
          "text-outline-color": "#77828C"
        }
      },
    ],
    layout: {
      name: 'cose'
    }
    // style: [
    //   {
    //     selector: 'node',
    //     style: {
    //       shape: 'ellipse',
    //       'background-color': 'red',
    //       label: 'data(id)'
    //     }
    //   }
    // ]
  });

</script>

<script>
  // query network
  $(document).ready(function(){
    // read in form data
    let tissue = "<?php echo addslashes($tissue);?>";
    let celltype = "<?php echo addslashes($celltype)?>";
    let genes = <?php echo json_encode($genes) ?>;
    // genes = JSON.parse(genes);
    console.log("Tissue: " + tissue)
    console.log("Cell Type: " + celltype)
    console.log("Genes: " + genes)
    // query network from neo4j db
    $.ajax({
      url: 'get_network.php',
      type: 'POST',
      data: {
        tissue: tissue,
        celltype: celltype,
        genes: genes,
        nNodeHop: $('#n-node-hop').val()
      },
      success: function(response){
        // parse JSON response into an Object class
        // console.log(response)
        resArray = JSON.parse(response)
        // console.log(typeof resArray)
        console.log(resArray);
        // find all object properties
        // for (const [key, value] of Object.entries(resArray)) {
        //   console.log(`${key}: ${value}`);
        // }
        // console.log(resArray.main_output.elements)
        cy.add(resArray.main_output.elements).layout({name: 'cose'}).run()
        // newNodes.layout({name: 'cose'}).run()
      },
      error: function(error){
        alert('Error: ' + error)
      },
    })
  });

  // reload network with new parameters
  $("#cy-params-form").submit(function(event){
    event.preventDefault();
    cy.elements().remove()
    // console.log($(this)[0]); // extracts form DOM from form element
    // console.log(new FormData(document.getElementById("cy-params-form")));
    // // get form data
    var form_data = new FormData($(this)[0]);
    let tissue = "<?php echo addslashes($tissue);?>";
    let celltype = "<?php echo addslashes($celltype)?>";
    let genes = <?php echo json_encode($genes) ?>;
    let existingNodes = new Object;
    existingNodes.existingNodes = cy.nodes().map(node => node.id());
    let existingEdges = new Object;
    existingEdges.existingEdges = cy.edges().map(edge => edge.id());
    form_data.append("tissue", tissue);
    form_data.append("celltype", celltype);
    form_data.append('genes', genes);
    // form_data.append("existingNodes", JSON.stringify(existingNodes));
    // form_data.append("existingEdges", JSON.stringify(existingEdges));
    for (const pair of form_data.entries()) {
      console.log(pair[0], pair[1]);
    }
    $.ajax({
      url: 'get_network.php',
      type: 'POST',
      data: form_data,
      processData: false,
      contentType: false,
      success: function(response){
        // // parse JSON response into an Object class
        resArray = JSON.parse(response)
        // console.log(resArray);
        // resArray.main_output.elements.style = [{selector: 'node', style: {'background-color': 'red'}}]
        cy.add(resArray.main_output.elements)
        cy.layout({name: 'cose'}).run()
        
        console.log(resArray)

      },
      error: function(error){
        alert('Error: ' + error)
      }
    })
  })
</script>
