<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use App\Models\DispositivoModel;
use App\Models\EnergiaModel;

class Facturas extends BaseController
{
    public function generarPDF($dispositivoId)
    {
        $dispositivoModel = new DispositivoModel();
        $EnergiaModel = new EnergiaModel();

        // Obtenemos el dispositivo y sus lecturas
        $dispositivo = $dispositivoModel->find($dispositivoId);
        $lecturas = $EnergiaModel->where('id_dispositivo', $dispositivoId)->findAll();

        // Validamos que existan datos
        if (!$dispositivo || empty($lecturas)) {
            return redirect()->back()->with('error', 'No se encontraron datos para este dispositivo');
        }

        // Calculamos el total de kWh
        $totalKwh = array_sum(array_column($lecturas, 'kwh'));

        // Generamos el HTML a partir de la vista
        $html = view('facturas/pdf', [
            'dispositivo' => $dispositivo,
            'lecturas'    => $lecturas,
            'totalKwh'    => $totalKwh,
        ]);

        // Instanciamos Dompdf y generamos el PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Enviamos el PDF al navegador
        $dompdf->stream("Factura-" . $dispositivo['nombre'] . ".pdf", ["Attachment" => true]);
    }
}
