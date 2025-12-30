<?php

namespace App;

use Carbon\Carbon;
use FPDF;

class ComprobantePago extends FPDF{
    private $nombre;
    private $condominio;
    private $pagos;
    private $dolar;

    public function __construct($nombre, $condominio, $pagos, $dolar) {
        parent::__construct();
        $this->nombre = $nombre;
        $this->condominio = $condominio;
        $this->pagos = $pagos;
        $this->dolar = $dolar;
    }

    /**
     * Pie de página
     */
    function Footer(){
        // Usamos Carbon para formatear la fecha actual de forma segura
        $fechaFormateada = Carbon::now()->locale('es')->isoFormat('DD [de] MMMM [de] YYYY [a las] HH:mm');
        
        $this->SetY(-10);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10, utf8_decode('Comprobante generado el: '.$fechaFormateada),0,0,'L');
        $this->Cell(0,10, utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'R');
    }
    
    public function generarPDF() {
        $this->AliasNbPages(); // Necesario para el conteo de páginas {nb}
        $this->AddPage('P','Letter');

        // Usamos public_path() para asegurar que encuentre el logo en cualquier entorno
        $logo = public_path('build/img/logo.png');
        if(file_exists($logo)){
            $this->Image($logo, 10, 5, 40, 11);
        }

        $this->Ln(10);
        $this->SetFont('Arial', 'B', 20);
        $this->SetTextColor(198,155,25);
        $this->Cell(0, 10, 'Estado de cuenta', 0, 1, 'R');
        
        // Información del cliente
        $valorPisoFormateado = number_format($this->condominio->price, 2);
        
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', 'B', 12);
        $this->Ln(14);
        $this->Cell(0, 7, utf8_decode($this->nombre), 0, 1);
        
        $this->SetFont('Arial', 'B', 15);
        $this->Ln(5);
        $this->Cell(0, 7, "P R E S E N T E", 0, 1);
        $this->Ln(2);

        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 7, "Condominio: " . utf8_decode($this->condominio->number), 0, 1, 'R');
        $this->Cell(0, 7, utf8_decode("Monto de operación de contrato: \${$valorPisoFormateado} {$this->condominio->currency}"), 0, 1);

        $this->Ln(5);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Desglose de pagos', 0, 1, 'L');

        // Dimensiones de la tabla (ajustadas para que sumen el ancho útil de la página)
        $columnWidths = [38, 38, 38, 38, 38];
        $tableWidth = array_sum($columnWidths);
        $marginLeft = ($this->GetPageWidth() - $tableWidth) / 2;

        // Encabezado de la tabla
        $this->SetFillColor(32, 48, 78);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 10);
        $this->SetX($marginLeft);
        $this->Cell($columnWidths[0], 10, 'Fecha', 1, 0, 'C', true);
        $this->Cell($columnWidths[1], 10, 'Abono', 1, 0, 'C', true);
        $this->Cell($columnWidths[2], 10, 'Abono total', 1, 0, 'C', true);
        $this->Cell($columnWidths[3], 10, 'Saldo', 1, 0, 'C', true);
        $this->Cell($columnWidths[4], 10, 'Pena', 1, 0, 'C', true);
        $this->Ln();

        // Totales iniciales
        $saldoPendiente = $this->condominio->price;
        $totalPagado = 0;

        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(32, 48, 78);

        foreach ($this->pagos as $index => $pago) {
            // Formateo de fecha con Carbon (Sustituye a strftime)
            $fechaFormateada = Carbon::parse($pago->created_at)->locale('es')->isoFormat('DD [de] MMM [de] YYYY');
            
            $saldoPendiente -= $pago->amount;
            $pena = $pago->discount_condominium;
            $totalPagado += $pago->amount;

            // Zebra styling
            $fill = ($index % 2 == 0) ? [225, 231, 242] : [188, 202, 228];
            $this->SetFillColor($fill[0], $fill[1], $fill[2]);

            $this->SetX($marginLeft);
            $this->Cell($columnWidths[0], 10, utf8_decode($fechaFormateada), 1, 0, 'C', true);
            $this->Cell($columnWidths[1], 10, "$".number_format($pago->amount, 2), 1, 0, 'C', true);
            $this->Cell($columnWidths[2], 10, "$".number_format($totalPagado, 2), 1, 0, 'C', true);
            $this->Cell($columnWidths[3], 10, "$".number_format($saldoPendiente, 2), 1, 0, 'C', true);
            $this->Cell($columnWidths[3], 10, "$".number_format($saldoPendiente - $pena, 2), 1, 0, 'C', true);
            $saldoPendiente -= $pena;
            $this->Ln();
        }

        // Totales al final de la tabla
        $this->SetFillColor(32, 48, 78);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 10);
        
        $this->SetX($marginLeft);
        $this->Cell($columnWidths[0] + $columnWidths[1], 10, "Total pagado", 1, 0, 'R', true);
        $this->Cell($columnWidths[2], 10, "$".number_format($totalPagado, 2), 1, 1, 'C', true);
        
        $this->SetX($marginLeft);
        $this->Cell($columnWidths[0] + $columnWidths[1], 10, "Saldo pendiente", 1, 0, 'R', true);
        $this->Cell($columnWidths[2], 10, "$".number_format($saldoPendiente, 2), 1, 1, 'C', true);

        // Texto final
        $this->Ln(10);
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(0, 0, 0);
        $mensaje = "Dado el desglose antes presentado según los depósitos recibidos, le informamos que al día de hoy presenta un saldo pendiente de $" . number_format($saldoPendiente, 2) . " para liquidar el contrato.";
        $this->MultiCell(0, 6, utf8_decode($mensaje));

        $this->SetY(-35); 
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 8, "Inmovitur S.A. de C.V.", 0, 1, 'L');

        // Retornamos el contenido para que el controlador lo maneje
        return $this->Output('S', 'ComprobantePago.pdf');
    }
}