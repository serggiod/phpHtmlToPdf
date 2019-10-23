<?php

    error_reporting(~E_ALL);

    require 'vendor/autoload.php';

    use \Katzgrau\KLogger\Logger;
    use \Dompdf\Dompdf;
    
    class HtmlToPdf{

        private $Log = null;
        private $Pdf = null;
        private $PathToHtml = null;
        private $pathToPdf  = null;
        private $PDFPaperTip = 'A4';
        private $PDFPaperOri = 'portrait';

        public function __construct($argv)
        {

            // Inicializar clase Monolog.
            $this->Log = new Logger(__DIR__ . '/logs');
            $this->Log->info('Clase KLogger inicializada.');

            // Inicializar clase DOMPdf.
            $this->Pdf = new Dompdf();
            $this->Log->info('Clase DomPDF inicializada.');

            // Persistir Paths
            foreach($argv as $arg){

                $arg = explode('=',$arg);

                if($arg[0]=='--in')  $this->PathToHtml = $arg[1];
                if($arg[0]=='--out') $this->PathToPdf = $arg[1];
                // if($arg[0]=='--size') ;
                // if($arg[0]=='--orientation') ;
                // if($arg[0]=='--help') ;

            }

            $this->convertHtmlToPdf();

        }

        public function __destruct()
        {}

        private function convertHtmlToPdf()
        {

            if($this->PathToHtml==NULL||$this->PathToPdf==NULL) $this->Log->error('Los argumentos --in= 0 --out= no estan definidos.');
            else {

                $html = file_get_contents($this->PathToHtml);


                $this->Pdf->setPaper($this->PDFPaperTip,$this->PDFPaperOri);
				$this->Pdf->loadHtml($html);
				$this->Pdf->render();
	
                if(file_put_contents($this->PathToPdf,$this->Pdf->output())) $this->Log->info('El archivo se ha convertido en forma correcta.');
                else $this->Log->info('No se pudo convertir el archivo html en pdf.');
               
            }
            
        }

    }

    $HtmlToPdf = new HtmlToPdf($argv);