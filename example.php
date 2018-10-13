<?php

require_once('SplitPdf.php');

$source = "/dir1/dir2/test_file.pdf";
$output = "/dir3/dir4/output.pdf";

$splitPdf = new SplitPdf();

//Page numbers from 540 to 623 will be extracted from source file and create the destination file
$splitPdf->split($source, 540, 623, $output);