<?php
/**
 * Class SplitPdf
 *
 * This class let you import pages of existing PDF file and create a new pdf document.
 *
 */

require_once('fpdf/fpdf.php');
require_once('fpdi/autoload.php');

use \setasign\Fpdi\Fpdi;

class SplitPdf
{
    /**
     * Source file path
     *
     * @var string
     */
    protected $sourceFile;

    /**
     * Fpdi instance
     *
     * @var \setasign\Fpdi\Fpdi
     */
    protected $pdf;

    /**
     * Source File name
     *
     * @var string
     */
    protected $fileName;

    /**
     * Constructor function
     *
     * @author Sreeraj <sreeraj@lbit.in>
     *
     * @return void
     */
    public function __construct()
    {
        $this->pdf = new \setasign\Fpdi\Fpdi();
    }

    /**
     * split - function to split Source file
     *
     * @author Sreeraj <sreeraj@lbit.in>
     *
     * @param string $sourceFile Source file
     * @param int $pageFrom Page number from which the source file need to be split
     * @param int $pageTo Page number to which the source file need to be split
     * @param string $dest Destination to which the split file need to be saved
     *
     * @return void
     */
    public function split($sourceFile, $pageFrom, $pageTo, $dest)
    {
        $this->sourceFile = $sourceFile;
        $this->fileName = basename($this->sourceFile);

        //Create destination folder if not exist
        $this->createDest($dest);

        //Fpdi instance for new pdf document
        $newPdf = new \setasign\Fpdi\Fpdi();

        try {
            //Set source file for the new pdf document
            $newPdf->setSourceFile($this->sourceFile);
        } catch (Exception $e) {
            //Source file is not Fpdi compatible https://www.setasign.com/products/fpdi-pdf-parser/details

            //Convert source file to Fpdi compatible
            $compatibleFile = $this->makeFpdiCompatible();

            //Set source file for the new pdf document
            $newPdf->setSourceFile($compatibleFile);
        }
        

        //loop page numbers from pageFrom to pageTo
        for ($i = $pageFrom; $i <= $pageTo; $i++) {
            //Add new page to pdf document
            $newPdf->AddPage();

            //Set new page content from source file
            $newPdf->useTemplate($newPdf->importPage($i));
        }
            
        try {
            $newPdf->Output($dest, "F");
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    /**
     * makeFpdiCompatible - function to convert source file to FPDI compatible
     *
     * @author Sreeraj <sreeraj@lbit.in>
     *
     * @return String $newFilePath path of the new compatible file
     */
    public function makeFpdiCompatible()
    {
        //Create  a new file path for the compatible document
        $newFilePath = $this->getNewFilePath();

        //Ghostscript to convert pdf document to Fpdi compatible
        //change gs path if necessary
        exec('/usr/local/bin/gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile="' . $newFilePath . '" "' . $this->sourceFile . '"');
        return $newFilePath;
    }

    /**
     * getNewFilePath - function to create new filepath for Fpdi compatible document
     *
     * @author Sreeraj <sreeraj@lbit.in>
     *
     * @return string file path
     */
    public function getNewFilePath()
    {
        //Get the directory path of the source file
        $path = dirname($this->sourceFile);

        //Create new unique file name
        $this->fileName = time() . '_' . $this->fileName;

        return $path . $this->fileName;
    }

    /**
     * createDest - function to create destination folders if not exist
     *
     * @author Sreeraj <sreeraj@lbit.in>
     *
     * @param String $dest Destination folder path
     *
     * @return void
     */
    public function createDest($dest = false)
    {
        $path = substr($dest, 0, strrpos($dest, '/'));
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }
}
