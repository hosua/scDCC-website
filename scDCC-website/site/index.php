<?php
	if ($_GET['run']){
		// chdir("");
		
		/* Code to run scDCC */	
		// $start_dir = getcwd();
		// chdir("../../");
		// $output = shell_exec("./run-scDCC.sh");
		// file_put_contents("log.txt", "$output");
		// shell_exec("./zip-log.txt");
		// chdir("$start_dir");
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
				<a class="nav-link active" aria-current="page">Home</a>
			  </li>
			  <!--
					  <li class="nav-item">
						<a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Page 2</a>
					  </li>
			  -->
			</ul>
			  <!--
					<form class="d-flex">
					  <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
					  <button class="btn btn-outline-success" type="submit">Search</button>
					</form>
			  -->
		  </div>
		</div>
	  </nav>
	</header>


	<main>
		<div class="container">
			<div class="container-fluid pt-3 text-light bg-dark border">
					<div class="row">
						<div class="well well-small">
							<h3>scDCC</h3>
							<p> Clustering is a critical step in single cell-based studies. Most existing methods support unsupervised clustering without the prior exploitation of any domain knowledge. When confronted by the high dimensionality and pervasive dropout events of scRNA-Seq data, purely unsupervised clustering methods may not produce biologically interpretable clusters, which complicates cell type assignment. </p>
							<p> In such cases, the only recourse is for the user to manually and repeatedly tweak clustering parameters until acceptable clusters are found. </p>
							<p> Consequently, the path to obtaining biologically meaningful clusters can be ad hoc and laborious. Here we report a principled clustering method named scDCC, that integrates domain knowledge into the clustering step. Experiments on various scRNA-seq datasets from thousands to tens of thousands of cells show that scDCC can significantly improve clustering performance, facilitating the interpretability of clusters and downstream analyses, such as cell type assignment.  </p>
						</div>
					</div>
			</div>

			<br>
			
			<form method="post" action="download.php" id="scDCC-form" class="container-fluid pt-3 text-light bg-dark border" enctype="multipart/form-data">
					<div class="row">
						<div class="well well-small">
							<h3>Please upload data files for scDCC here</h3> 
							<br>
						</div>
					</div>
					<div class="row">
						<div class="col-auto">
							<label for="data-file" class="form-label">Upload data</label>
							<input class="form-control bg-dark text-white" type="file" id="data-file" name="data-file" required>
						</div>
						<div class="col-auto">
							<label for="gene-name" class="form-label">Gene name</label>
							<input placeholder="Enter the name of the gene..." id="gene-name" name="gene-name" type="text" class="form-control text-light bg-dark" required>
						</div>
					</div>

					<div class="row pt-2">
						<div class="col-auto">
							<label for="num-constraints" class="form-label">Number of constraints</label>
							<input value=2 min=0 id="num-constraints" name="num-constraints" type="number" class="form-control text-light bg-dark" @keypress="isNumberKey($event)">
						</div>
						<div class="col-auto align-self-center">
							<label for="lc-val" class="form-label">LC value</label>
							<input value=20 min=0 step="1" id="lc-val" name="lc-val" type="number" class="form-control text-light bg-dark" @keypress="isNumberKey($event)">
						</div>
						<div class="col-auto align-self-center">
							<label for="hc-val"  class="form-label">HC value</label>
							<input value=90 min=0 step="1" id="hc-val" name="hc-val" type="cl-cutoff" class="form-control text-light bg-dark" @keypress="isNumberKey($event)">
						</div>
					</div>

					<div class="row pt-2">
						<div class="col-auto align-self-center">
							<button id="run-scDCC" type="submit" class="btn btn-primary mb-3" disabled>Run scDCC</button>
						</div>
						<div class="col pt-1">
							<a href="http://google.com">How to upload files for scDCC</a>
						</div>
					</div>

			</form>
		</div> <!-- End outer container -->
		<script>
			/* Form validation and warning message is handled here */
			var scDCC_form = document.getElementById("scDCC-form");
			var data_file = document.getElementById("data-file");
			var gene_name = document.getElementById("gene-name");

			var run_btn = document.getElementById("run-scDCC");

			var warning_msg = document.createElement("div");
			warning_msg.setAttribute("id", "warning-msg");
			warning_msg.classList.add("alert", "alert-danger", "fade", "show");
			warning_msg.textContent = "Warning, you need to upload a data file and enter a gene name before you can run scDCC!";
			
			data_file.addEventListener("input", () => {
				if (data_file.value && gene_name.value)
					run_btn.disabled = false;
				else
					run_btn.disabled = true;
				
				if (run_btn.disabled)
					scDCC_form.appendChild(warning_msg);
				else if (scDCC_form.lastElementChild.id == "warning-msg")
					scDCC_form.removeChild(scDCC_form.lastElementChild);
			});

			gene_name.addEventListener("input", () => {
				if (data_file.value && gene_name.value)
					run_btn.disabled = false;
				else
					run_btn.disabled = true;
				if (run_btn.disabled)
					scDCC_form.appendChild(warning_msg);
				else if (scDCC_form.lastElementChild.id == "warning-msg")
					scDCC_form.removeChild(scDCC_form.lastElementChild);
			});
			console.log(data_file, gene_name);
		</script>

		  <!-- FOOTER -->
		  <footer class="container-fluid pt-5 text-light bg-dark">
			  <!-- <p class="float-end"><a href="#">Back to top</a></p> -->
			<p>&copy; 2022-2023 New Jersey Institute of Technology (NJIT) &middot; <a href="https://github.com/ttgump/scDCC">Github</a>
		  </footer>
		</main>

		<script src="./assets/js/bootstrap.bundle.min.js"></script>
  </body>
</html>


