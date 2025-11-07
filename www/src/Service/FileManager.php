<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;

class FileManager
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Génère un PDF à partir d'un template twig et des données fournies
     * Retourne le contenu binaire du PDF
     */
    public function renderPdfFromTemplate(string $template, array $context = []): string
    {
        $html = $this->twig->render($template, $context);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultPaperSize', 'a4');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}
