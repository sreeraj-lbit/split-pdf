# split-pdf
Split an existing pdf document with a range of page numbers

This class uses [FPDF](https://github.com/Setasign/FPDF) to process files.

This class require [Ghostscript](https://www.ghostscript.com/Documentation.html) to be installed in order to convert documents with PDF version > 1.4 to FPDI compatible.


# Usage
```php
require_once('SplitPdf.php');

$source = "/dir1/dir2/test_file.pdf";
$output = "/dir3/dir4/output.pdf";

$splitPdf = new sreeraj\SplitPdf\SplitPdf();

//Page numbers from 10 to 17 will be extracted from source file and create the destination file
$splitPdf->split($source, 10, 17, $output);
```
