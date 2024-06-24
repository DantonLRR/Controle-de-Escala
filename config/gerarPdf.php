<?php 
$diretorioDoPdf = $_POST['diretorioDoPdf'];
require __DIR__."../../vendor/autoload.php";
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->setChroot(__DIR__);
$dompdf = new Dompdf($options);

ob_start();

$html = include __DIR__."/$diretorioDoPdf";
$dompdf->loadHtml(ob_get_clean());
$dompdf->setpaper('A4', 'landscape');
$dompdf->render();

header('Content-Type: application/pdf');
echo $dompdf->output();
?>
