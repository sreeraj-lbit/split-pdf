# split-pdf
Split an existing pdf document with a range of page numbers

This class uses [FPDF](https://github.com/Setasign/FPDF) to process files.


# Usage
```php
require_once('SplitPdf.php');

$source = "/dir1/dir2/test_file.pdf";
$output = "/dir3/dir4/output.pdf";

$splitPdf = new SplitPdf();

//Page numbers from 10 to 17 will be extracted from source file and create the destination file
$splitPdf->split($source, 10, 17, $output);
```