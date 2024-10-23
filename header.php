
<style>
  .navbar-nav .nav-link.active {
  font-weight: bold;
  color: var(--primary_color) !important; /* Use your preferred color */
}

</style>

<!-- header navigation bar -->

<nav class="navbar sticky-top navbar-expand-sm  bg-body-tertiary">
  <div class="container-fluid">
    <!-- icon or logo using navbar-brand class -->
    <a class="navbar-brand" href="index.php">
        scGRNdb
        <img src="include/pictures/scNetwork_human_mouse_fig.png" alt="scGRNdb Logo" class="d-inline-block align-text-center" style="height: 60px;">
    </a> 
    <!-- use a toggle button when window size gets too small -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!-- navigation bar (collapsible)-->
    <div class="collapse navbar-collapse" id="navbarHeader">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="scing_params.php">Run SCING</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="explore_networks.php">Explore Networks</a>
        </li>
        <li class="nav-item">
          <a href="network_analysis.php" class="nav-link">Run Network Analysis</a>
        </li>
        <!-- dropdown item -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Tutorials
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">SCING Tutorial</a></li>
            <li><a class="dropdown-item" href="#">Pathway Enrichment Tutorial</a></li>
            <li><a class="dropdown-item" href="#">Disease Modeling Tutorial</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true">Disabled</a>
        </li>
      </ul>
      <!-- <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form> -->
    </div>
  </div>
</nav>
<!-- jquery to color the active nav tab -->
<script>
  $(document).ready(function() {
    // Get current page URL
    var url = window.location.href;
    
    // Remove active class from all nav links
    $('.navbar-nav .nav-link').removeClass('active');
    
    // Loop through each nav item
    $('.navbar-nav .nav-link').each(function() {
      // Check if the URL contains the href value
      if (url.includes($(this).attr('href'))) {
        $(this).addClass('active');
      }
    });
    
    // // Add click event to nav links
    // $('.navbar-nav .nav-link').on('click', function() {
    //   $('.navbar-nav .nav-link').removeClass('active');
    //   $(this).addClass('active');
    // });
  });
</script>