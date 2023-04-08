<?php
if ($_SERVER['REQUEST_METHOD'] == "POST"){
	/* DEBUGGING */
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	$data_file = $_FILES["data-file"];

	$gene_name = $_POST["gene-name"];
	$num_constraints = $_POST["num-constraints"];
	$lc_val = $_POST["lc-val"];
	$hc_val = $_POST["hc-val"];

	$start_dir = getcwd();

	// File uploaded from user -> server 
	$upload_folder = "scDCC-website/site/uploads/";
	// $upload_folder = "./uploads/";
	// $output = shell_exec("./run-scDCC.sh");
	// file_put_contents("log.txt", "$output");
	// shell_exec("./zip-log.txt");
}
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Run scDCC online">
    <meta name="author" content="Josh Ortiga, Xiang Lin, Tian Tian">
    <title>scDCC</title>
	
<style>
	body { padding-top: 65px; } /* Stop navbar from overlapping content on top */
</style>
    

    <!-- Bootstrap core CSS -->
	<link href="./assets/css/bootstrap.min.css" rel="stylesheet">
	
	<body class="bg-dark text-light">
		<header>
		  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
			<div class="container-fluid">
			  <a class="navbar-brand" href="index.php">scDCC</a>
			  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			  </button>
			  <div class="collapse navbar-collapse" id="navbarCollapse">
				<ul class="navbar-nav me-auto mb-2 mb-md-0">
				  <li class="nav-item">
					<a class="nav-link" aria-current="page" href="index.php">Home</a>
				  </li>
				</ul>
			  </div>
			</div>
		  </nav>
		</header>

		<div class="container">
			<div class="container-fluid pt-3 bg-dark text-light border">
<?php
if ($_SERVER['REQUEST_METHOD'] == "POST"){
	chdir("../../");
	/* Show the user the parameters that were passed into scDCC */
	echo "<h3> Parameters used </h3>
		<hr>
		<div class='row'>
		  <p>Gene name: <strong>$gene_name</strong> </p>
		</div>
		<div class='row'>
		  <p>Number of contraints: <strong>$num_constraints</strong> </p>
		</div>
		<div class='row'>
		  <p>LC value: <strong>$lc_val</strong> </p>
		</div>
		<div class='row'>
		  <p>HC value: <strong>$hc_val</strong> </p>
		</div>
		<hr>	
";
	if (!move_uploaded_file($data_file["tmp_name"], "$upload_folder" . $data_file["name"])){
		echo "<p><strong>Error:</strong> Something went wrong with uploading the file to the server, please try again later.</p>";
	} else {
		echo "<div class='row'> 
			<p> Data is being processed... </p>
			<p> This may take some time, please be patient. </p>
		</div>";
		$cmd = "./run-scDCC.sh " . "$upload_folder" . $data_file['name'] . " "  . $lc_val . " " . $hc_val;
		$output = shell_exec($cmd);
		file_put_contents("log.txt", "$output");
		shell_exec("./zip-log.txt");
		chdir("$start_dir");
		/* Download button */
		echo '<div class="col-auto">
					<a href="./downloads/output.zip" class="btn btn-primary mb-3" download="output.zip">Download</a>
				</div>';
	}
}
?>
			</div>
		</div>

		  <!-- FOOTER -->
	<footer class="container-fluid pt-5 text-light bg-dark">
		<p>&copy; 2022-2023 New Jersey Institute of Technology (NJIT) &middot; <a href="https://github.com/ttgump/scDCC">Github</a>
	  </footer>
	</main>

	<script src="./assets/js/bootstrap.bundle.min.js"></script>
  </body>
</html>


