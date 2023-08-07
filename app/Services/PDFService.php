<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Response;

class PDFService
{

    /**
     * download collection as pdf file
     *
     * @param string $html
     * @param string $filename
     * @param string $paper
     * @param string $orientation
     * @return Response
     */
    public function downloadPdf(string $html, string $filename = 'filename.pdf', string $paper = 'Letter', string $orientation = 'landscape')
    {
        return PDF::setPaper($paper, $orientation)->loadHTML($html)->download($filename);
    }

    /**
     * download collection as pdf file (Letter)
     *
     * @param string $html
     * @param string $filename
     * @param string $orientation
     * @return Response
     */
    public function downloadPdfLetter(string $html, string $filename, string $orientation = 'landscape')
    {
        return $this->downloadPdf($html, $filename, 'Letter', $orientation);
    }

    /**
     * download collection as pdf file (Legal)
     *
     * @param string $html
     * @param string $filename
     * @param string $orientation
     * @return Response
     */
    public function downloadPdfLegal(string $html, string $filename, string $orientation = 'landscape')
    {
        return $this->downloadPdf($html, $filename, 'Legal', $orientation);
    }

    /**
     * download collection as pdf file (A1)
     *
     * @param string $html
     * @param string $filename
     * @param string $orientation
     * @return Response
     */
    public function downloadPdfA1(string $html, string $filename, string $orientation = 'landscape')
    {
        return $this->downloadPdf($html, $filename, 'A1', $orientation);
    }

    /**
     * download collection as pdf file (A2)
     *
     * @param string $html
     * @param string $filename
     * @param string $orientation
     * @return Response
     */
    public function downloadPdfA2(string $html, string $filename, string $orientation = 'landscape')
    {
        return $this->downloadPdf($html, $filename, 'A2', $orientation);
    }

    /**
     * download collection as pdf file (A3)
     *
     * @param string $html
     * @param string $filename
     * @param string $orientation
     * @return Response
     */
    public function downloadPdfA3(string $html, string $filename, string $orientation = 'landscape')
    {
        return $this->downloadPdf($html, $filename, 'A3', $orientation);
    }

    /**
     * download collection as pdf file (A4)
     *
     * @param string $html
     * @param string $filename
     * @param string $orientation
     * @return Response
     */
    public function downloadPdfA4(string $html, string $filename, string $orientation = 'landscape')
    {
        return $this->downloadPdf($html, $filename, 'A4', $orientation);
    }
}
