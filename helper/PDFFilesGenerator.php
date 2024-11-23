<?php

use Dompdf\Dompdf;
use Dompdf\Options;

class PDFFilesGenerator
{

    private $mustachePresenter;
    private $dompdf;
    public function __construct($presenter)
    {
        $this->mustachePresenter = $presenter;

        // Configurar Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permitir recursos remotos como imágenes o CSS
        $this->dompdf = new Dompdf($options);
    }

    /**
     * Generar HTML a partir de una plantilla Mustache.
     *
     * @param string $contentFile Nombre del archivo de contenido (sin extensión).
     * @param array $data Datos para renderizar la plantilla.
     * @return string HTML generado.
     */
    private function generateHtmlFromTemplate($contentFile, $data)
    {
        return $this->mustachePresenter->generateHtmlPdf($contentFile, $data);
    }

    /**
     * Generar y renderizar el PDF.
     *
     * @param string $html Contenido HTML para renderizar.
     * @param string $outputFile Nombre del archivo PDF.
     * @param bool $download Indica si se descarga automáticamente o se muestra en el navegador.
     */
    private function renderPdf($html, $outputFile, $download = 0)
    {
        // Cargar el HTML en Dompdf
        $this->dompdf->loadHtml($html);

        // Configurar tamaño de papel y orientación
        $this->dompdf->setPaper('A4', 'portrait');

        // Renderizar el PDF
        $this->dompdf->render();

        // Mostrar o descargar el PDF
        $this->dompdf->stream($outputFile, ["Attachment" => $download]);
    }

    /**
     * Función pública para generar un PDF completo.
     *
     * @param string $contentFile Nombre del archivo de contenido (sin extensión).
     * @param array $data Datos para renderizar la plantilla.
     * @param string $outputFile Nombre del archivo PDF.
     * @param bool $download Indica si se descarga automáticamente o se muestra en el navegador.
     */
    public function generateAndRenderPdf($contentFile, $data, $outputFile, $download = 0)
    {
        // Generar HTML desde Mustache
        $html = $this->generateHtmlFromTemplate($contentFile, $data);

        // Renderizar y mostrar/descargar el PDF
        $this->renderPdf($html, $outputFile, $download);
    }


}