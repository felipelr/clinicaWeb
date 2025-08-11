<?php
require_once(APP . 'Vendor' . DS . 'dompdf' . DS . 'dompdf_config.inc.php');
spl_autoload_register('DOMPDF_autoload');
$dompdf = new DOMPDF();
$dompdf->set_paper('A4', 'portrait');
$dompdf->set_option('enable_html5_parser', TRUE);
$dompdf->load_html($content_for_layout);
$dompdf->render();
echo $dompdf->output();
