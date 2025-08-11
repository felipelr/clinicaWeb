<?php
// APP/View/PdfView.php

App::uses('View', 'View');

// A linha abaixo carrega o arquivo de configuração do DOMPDF.
// A localização pode variar dependendo de como você instalou a biblioteca.
// Certifique-se de que o caminho esteja correto.
require_once(APP . 'Vendor' . DS . 'dompdf' . DS . 'dompdf_config.inc.php');

class PdfView extends View
{

    /**
     * Renderiza o HTML da view e o passa para o DOMPDF para gerar o PDF.
     * @param string $view O nome da view a ser renderizada.
     * @param string $layout O nome do layout a ser usado.
     * @return string O conteúdo do PDF.
     */
    public function render($view = null, $layout = null)
    {
        // Renderiza o HTML da view usando o método padrão do CakePHP.
        // A variável $html agora contém o conteúdo completo da sua view (com <html>, <head>, etc.).
        $html = parent::render($view, false); // O segundo parâmetro `false` desativa o layout.

        // Inicializa a biblioteca DOMPDF.
        $dompdf = new DOMPDF();

        // Configura o tamanho e a orientação do papel.
        $dompdf->set_paper('A4', 'portrait');

        // Carrega o HTML na instância do DOMPDF.
        $dompdf->load_html($html);

        // Gera o PDF.
        $dompdf->render();

        // Define os headers de resposta para forçar o download do arquivo PDF.
        //$this->response->type('pdf');
        //$this->response->download('relatorio_paciente.pdf');

        // Retorna o conteúdo binário do PDF.
        return $dompdf->output();
    }
}
