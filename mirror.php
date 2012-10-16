<?php 

$filename1 = '/data/diskarray2/pubmedOpenAccess/mirror/rdf_files.tar.gz';
$filename2 = '/data/diskarray2/pubmedOpenAccess/mirror/virtuoso_graphs.tar.gz';

function _format_bytes($a_bytes)
{
    if ($a_bytes < 1024) {
        return $a_bytes .' B';
    } elseif ($a_bytes < 1048576) {
        return round($a_bytes / 1024, 2) .' KiB';
    } elseif ($a_bytes < 1073741824) {
        return round($a_bytes / 1048576, 2) . ' MiB';
    } elseif ($a_bytes < 1099511627776) {
        return round($a_bytes / 1073741824, 2) . ' GiB';
    } elseif ($a_bytes < 1125899906842624) {
        return round($a_bytes / 1099511627776, 2) .' TiB';
    } elseif ($a_bytes < 1152921504606846976) {
        return round($a_bytes / 1125899906842624, 2) .' PiB';
    } elseif ($a_bytes < 1180591620717411303424) {
        return round($a_bytes / 1152921504606846976, 2) .' EiB';
    } elseif ($a_bytes < 1208925819614629174706176) {
        return round($a_bytes / 1180591620717411303424, 2) .' ZiB';
    } else {
        return round($a_bytes / 1208925819614629174706176, 2) .' YiB';
    }
}


?>

<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title>bioteà - biomedical annotation : Create a bioteà mirror</title>
	<meta name="description" content="">
	<meta name="author" content="iDigInfo at Florida State University">

	<!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS
  ================================================== -->
	<link href='http://fonts.googleapis.com/css?family=Oswald:400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="stylesheets/base.css">
	<link rel="stylesheet" href="stylesheets/skeleton.css">
	<link rel="stylesheet" href="stylesheets/layout.css">

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	


	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="images/favicon.ico">
	<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
	
	
	
    

</head>
<body>



	<div id="header">
	<div class="container">
		<div class="sixteen columns">
		<ul>
			<li><a class="first" href="index.html">Run a Query</a></li>
			<li><a class="second" href="mirror.php">Create a Mirror</a></li>
			<li><a class="third" href="graph_loading.php">Load Graphs</a></li>
			<li><a class="first" href="contact.html">Contact</a></li>
		</ul>
		</div>
		<div class="two-thirds column space">&nbsp;</div>
		<div class="one-third column logo">
		<a href="/"><img src="images/biotea_logo.png" alt="bioteà" border="0" /></a>
		</div>
	</div>
	</div>

	<div class="container main">
		<div class="two-thirds column">
			<h3>Build a mirror site for Virtuoso and RDF of PubMedCentral</h3>
			
			<br />
			<p><strong>1: Host Physical Configuration</strong>
			<br />Construct computational resources to support storage of and access to the graphs stored in the Virtuoso database (i.e. cpu cycles and disk storage space).</p>
			
			<p><strong>2: Install and configure the Virtuoso server</strong>
			<br />Be sure to add the Graphs storage directory name in the the <em>[Parameters]DirsAllowed</em> entry in the virtuoso.ini file</p>
			
			<p><strong>3: Configure access with the web</strong>
			<br />For the Virtuoso server, SPARQL Endpoint, and RDF storage directories.</p>
			
			<p><strong>4: Download the archive files</strong>
			<br />These files are updated weekly. The newest files are available from the download boxes on this page.</p>
			
			<p><strong>5: Unpack rdf_files.tar.gz into the RDF storage directory.</strong></p>
			
			<p><strong>6: Unpack virtuoso_graphs.tar.gz into the Graphs storage directory.</strong></p>
			
			<p><em>Notes</em></p>
			<ul style="margin: 0 0 0 20px; list-style: disc">
				<li>The current virtuoso verison (06.01.3127) requires the Bulk Loader Procedures be manually loaded via the isql command line - save the source from the VirtBulkRDFLoaderScript page as <em>rdfloader.sql</em> (<a href="http://www.openlinksw.com/dataspace/dav/wiki/Main/VirtBulkRDFLoader" target="_blank">OpenLink reference</a>)</li>
				<li>Start the isql process and enter at the SQL&gt; prompt: <em>load rdfloader.sql</em> (<a href="http://www.openlinksw.com/dataspace/dav/wiki/Main/VirtBulkRDFLoaderScript" target="_blank">OpenLink Reference</a>)</li>
			</ul>
	
			</div>
		<div class="one-third column">
			
			<h3 class="space"><br />&nbsp;</h3>
			<br />
			<div id="download">
			<a class="file" href="http://biotea.idiginfo.org/pubmedOpenAccess/mirror/rdf_files.tar.gz">rdf_files.tar.gz
			<br /><br /><em><?php echo _format_bytes(filesize($filename1)); ?> 
			<br /><?php echo "Updated: " . date ("F d Y H:i:s", filemtime($filename1)); ?> </em></a>
			</div>

			<div id="download2">
			<a class="file" href="http://biotea.idiginfo.org/pubmedOpenAccess/mirror/virtuoso_graphs.tar.gz">virtuoso_graphs.tar.gz 
			<br /><br /><em><?php echo _format_bytes(filesize($filename2)); ?> 
			<br /><?php echo "Updated: " . date ("F d Y H:i:s", filemtime($filename2)); ?> </em></a>
			</div>

		</div>

	</div><!-- container -->

	<div id="footer">
	<div class="container">
		<a href="https://www.idiginfo.org" target="_blank"><img src="images/idiginfo.png" alt="iDigInfo" border="0" /></a>
	</div>
	</div>


<!-- End Document
================================================== -->
</body>
</html>