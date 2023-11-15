<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Carbon;

class AgencyEntrance extends Controller
{
    public function data(){
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;
        $user = DB::select("
        SELECT * FROM entrada WHERE Agencia = '$agenciaU'");

        return datatables()->of($user)->toJson();

    }


    public function data2(){
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;
        $user = DB::select("
        SELECT * FROM delegados");

        return datatables()->of($user)->toJson();
        

    }

    public function create(Request $request)
    {
        $existingPerson = DB::select('SELECT * FROM entrada WHERE Cedula = ?', [$request->Cedula]);

        if ($existingPerson == true) {
            return back()->with("incorrecto", "Persona con cc. $request->Cedula ya existe! Error al Registrar!");
        }
        
         else {
            date_default_timezone_set('America/Bogota');
            $fechaHoraActual = date('Y-m-d H:i');
            $sql = DB::insert('INSERT INTO entrada (Agencia, Cedula, Priapellido, Segapellido, Nombre, Genero, Anionaci, Mesnaci, Dianaci, TipoSangre, Cuenta, Fecha) VALUES (?, ?, ?, UPPER(?), ?, ?, ?, ?, ?, ?, ?, ?)', [
                $request->Agencia,
                $request->Cedula,
                $request->Priapellido,
                $request->Segapellido,
                $request->Nombre,
                $request->Genero,
                $request->Anionaci,
                $request->Mesnaci,
                $request->Dianaci,
                $request->TipoSangre,
                $request->Cuenta,
                $fechaHoraActual
            ]);

            if ($sql) {
                return back()->with("correcto", "Persona Registrada correctamente!");
            } else {
                return back()->with("incorrecto", "Error al insertar el registro!");
            }
        }
    }

    public function mostrarcandidato(Request $request, $id){
        $Tarjeton = DB::select("SELECT * FROM delegados WHERE NoTarjeton = $id");

        
    }



    public function votarcandidato(Request $request, $id)
    {
        $existingPerson = DB::select('SELECT * FROM entrada WHERE Cedula = ?', [$request->Cedula]);

        if ($existingPerson == true) {
            return back()->with("incorrecto", "Persona con cc. $request->Cedula ya existe! Error al Registrar!");
        }
        
         else {
            date_default_timezone_set('America/Bogota');
            $fechaHoraActual = date('Y-m-d H:i');
            $sql = DB::insert('INSERT INTO entrada (Agencia, Cedula, Priapellido, Segapellido, Nombre, Genero, Anionaci, Mesnaci, Dianaci, TipoSangre, Cuenta, Fecha) VALUES (?, ?, ?, UPPER(?), ?, ?, ?, ?, ?, ?, ?, ?)', [
                $request->Agencia,
                $request->Cedula,
                $request->Priapellido,
                $request->Segapellido,
                $request->Nombre,
                $request->Genero,
                $request->Anionaci,
                $request->Mesnaci,
                $request->Dianaci,
                $request->TipoSangre,
                $request->Cuenta,
                $fechaHoraActual
            ]);

            if ($sql) {
                return back()->with("correcto", "Persona Registrada correctamente!");
            } else {
                return back()->with("incorrecto", "Error al insertar el registro!");
            }
        }
    }





    // public function updatevotos ($id, Request $request){



    //     $usuarioActual = Auth::user();
    //     $agenciaU = $usuarioActual->agenciau;
        

    //     $agenciaColumnaMapping = [
    //         'Bogotá Elemento' => 13,
    //         'CaliBC' => 30,
    //         'Cali' => 31,
    //         'Palmira' => 32,
    //         'Buga' => 34,
    //         'Buenaventura' => 33,
    //         'Tuluá' => 35,
    //         'Sevilla' => 36,
    //         'La Unión' => 37,
    //         'Roldanillo' => 38,
    //         'Cartago' => 39,
    //         'Zarzal' => 40,
    //         'Caicedonia' => 41,
    //         'S Quilichao' => 42,
    //         'Yumbo' => 43,
    //         'Jamundí' => 44,
    //         'Pasto' => 45,
    //         'Popayán' => 46,
    //         'Ipiales' => 47,
    //         'Leticia' => 48,
    //         'Puerto Asis' => 49,
    //         'Soacha' => 68,
    //         'Manizales' => 70,
    //         'Zipaquirá' => 73,
    //         'Facatativá' => 75,
    //         'Pereira' => 74,
    //         'Girardot' => 76,
    //         'San Andrés' => 77,
    //         'Armenia' => 78,
    //         'Medellín' => 80,
    //         'Monteria' => 81,
    //         'Sincelejo' => 82,
    //         'Yopal' => 83,
    //         'Riohacha' => 84,
    //         'Valledupar' => 85,
    //         'Cartagena' => 86,
    //         'Santa Marta' => 88,
    //         'Duitama' => 89,
    //         'Bogotá Centro' => 90,
    //         'Bogotá TC' => 91,
    //         'Bogotá Norte' => 92,
    //         'Villavicencio' => 93,
    //         'Tunja' => 94,
    //         'Ibagué' => 95,
    //         'Neiva' => 96,
    //         'Bucaramanga' => 97,
    //         'Cúcuta' => 98,
    //     ];



        
    //     $nombreResult = DB::select("SELECT Nombre FROM delegados WHERE ID = $id");
    //     $apellidosResult = DB::select("SELECT Apellidos FROM delegados WHERE ID = $id");

    //     $nombre = $nombreResult[0]->Nombre;
    //     $apellidos = $apellidosResult[0]->Apellidos;

    //     $nombrecompleto = $nombre . ' ' . $apellidos;
    //     $columna = $agenciaColumnaMapping[$agenciaU];



    //     $resultado = DB::select("SELECT columna_$columna FROM delegados WHERE ID = $id");

    //     if (!empty($resultado)) {
    //         $primerResultado = $resultado[0]; 
        
    //         $numero = intval($primerResultado->{"columna_$columna"});
        
    //         if ($numero > 0) {
    //             return back()->with("incorrecto",  "YA HA REGISTRADO LOS VOTOS POR EL CANDIDATO!");
    //         } else {
    //             if (array_key_exists($agenciaU, $agenciaColumnaMapping)) {
    //                 $columna = $agenciaColumnaMapping[$agenciaU];
        
    //                 $sql = DB::update("UPDATE delegados SET columna_$columna=? WHERE ID = $id", [
    //                     $request->Votos,
    //                 ]);
    //             }
    //         }
    //     }
            
    //     if ($sql) {

    //         return back()->with("correcto",  $request->Votos . " voto(s) añadido(s) correctamente a ".$nombrecompleto."!");
    //     } else {
    //         return back()->with("incorrecto", "¡Error al añadir votos al registro!");
    //     }

    // }

    public function imprimir(){
        $sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;

        $anchoHoja = 400; 
        $altoHoja = 400; 
    
        $fpdf = new Fpdf('P', 'mm', [$anchoHoja, $altoHoja]); // Personaliza el tamaño de la hoja
        $fpdf->AddPage("landscape");


        $headers = ['Tarjeton', 'Candidatos', 'Votos '.$agenciaU];

        Carbon::setLocale('es');
        $fecha_actual = Carbon::now('America/Bogota');
        $fecha_formateada = $fecha_actual->isoFormat('dddd, D [de] MMMM [de] YYYY');
        $fecha_formateada = preg_replace_callback('/\b(\p{L}+)\b/u', function ($matches) {
            return ucfirst($matches[0]);
        }, $fecha_formateada);
        
        $fpdf->SetFont('Arial', 'B', 20);
        $fpdf->Cell(0, 10, utf8_decode('COOPERATIVA DE SERVIDORES PÚBLICOS Y JUBILADOS DE COLOMBIA "COOPSERP COLOMBIA"'), 0, 0, 'C');
        $fpdf->Ln();    
        $fpdf->Cell(0, 10, utf8_decode("ELECCIÓN DE DELEGADOS PERÍODO 2024 - 2029"), 0, 0, 'C');
        $fpdf->Ln();
        $fpdf->Cell(0,10, utf8_decode($fecha_formateada), 0, 0, 'C');    


        $fpdf->Ln(); $fpdf->Ln();    
        $columnWidthText = 40;
        $columnWidth = 40;
        $columnWidthAgency = 75;
        $columnWidthCandidatos = 120;
        $columnWidthNumber = 20; 
        $headerFontSize = 16;

        $headerFillColor = [180, 180, 180];
        $fpdf->SetFillColor($headerFillColor[0], $headerFillColor[1], $headerFillColor[2]);

        $posicionX = ($anchoHoja - 235) / 2;
        $fpdf->SetX($posicionX);

        foreach ($headers as $header) {
            if ($header === 'Candidatos') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthCandidatos, 15, $header, 1, 0, 'C', true); 
            } elseif ($header === 'Tarjeton' || $header === 'Votos x Cand') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthText, 15, $header, 1, 0, 'C', true); 
            } else {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthAgency, 15, $header, 1, 0, 'C', true); 
            }
        }
       
        $fpdf->Ln(); 
    
        $fpdf->SetFont('Helvetica', '', 16);



        foreach ($sql as $row) {
            $agenciaColumnaMapping = [
                'Bogotá Elemento' => 13,
                'CaliBC' => 30,
                'Cali' => 31,
                'Palmira' => 32,
                'Buga' => 34,
                'Buenaventura' => 33,
                'Tuluá' => 35,
                'Sevilla' => 36,
                'La Unión' => 37,
                'Roldanillo' => 38,
                'Cartago' => 39,
                'Zarzal' => 40,
                'Caicedonia' => 41,
                'S Quilichao' => 42,
                'Yumbo' => 43,
                'Jamundí' => 44,
                'Pasto' => 45,
                'Popayán' => 46,
                'Ipiales' => 47,
                'Leticia' => 48,
                'Puerto Asis' => 49,
                'Soacha' => 68,
                'Manizales' => 70,
                'Zipaquirá' => 73,
                'Facatativá' => 75,
                'Pereira' => 74,
                'Girardot' => 76,
                'San Andrés' => 77,
                'Armenia' => 78,
                'Medellín' => 80,
                'Monteria' => 81,
                'Sincelejo' => 82,
                'Yopal' => 83,
                'Riohacha' => 84,
                'Valledupar' => 85,
                'Cartagena' => 86,
                'Santa Marta' => 88,
                'Duitama' => 89,
                'Bogotá Centro' => 90,
                'Bogotá TC' => 91,
                'Bogotá Norte' => 92,
                'Villavicencio' => 93,
                'Tunja' => 94,
                'Ibagué' => 95,
                'Neiva' => 96,
                'Bucaramanga' => 97,
                'Cúcuta' => 98,
            ];
    
            if (array_key_exists($agenciaU, $agenciaColumnaMapping)) {
                $columna = $agenciaColumnaMapping[$agenciaU];
    
            }
            $fpdf->SetX($posicionX);

            $fpdf->Cell($columnWidth, 10, $row->NoTarjeton, 1, 0, 'C');
            $fpdf->Cell($columnWidthCandidatos, 10, $row->Nombre ." ". $row->Apellidos, 1);
            $fpdf->Cell($columnWidthAgency, 10, $row->{"columna_".$columna}, 1, 0, 'C');

        
            

            $fpdf->Ln(); 
        
    }

    $totalSum = 0;
    $data = DB::select("SELECT * FROM delegados");
foreach ($data as $row) {

    $sumaColumna = DB::table('delegados')->sum("columna_".$columna);

$columnWidthTotal = 160;
$columnWidthTotal2 = 75;
$fpdf->SetFont('Arial', 'B', 20);
$fpdf->SetX($posicionX);
$fpdf->Cell($columnWidthTotal, 10, "Total votos", 1, 0, 'R');
$fpdf->Cell($columnWidthTotal2, 10, $sumaColumna, 1, 0, 'C');
}



    $fpdf->Output('I', 'impresion/Ticket-' . "asd" . '.pdf');
    $fpdf->Output('F', 'impresion/Ticket-' . "asd" . '.pdf');
    exit;
        
        
    }

}
