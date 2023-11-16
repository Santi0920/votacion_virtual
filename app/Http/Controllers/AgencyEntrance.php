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
        SELECT * FROM entrada WHERE Agencia = '$agenciaU' ORDER BY ID DESC");

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
    //REGRESA LA VISTA VOTOS Y EN EL WEB DE LE PASA EL ID {{ID}}
    public function mostrarcandidato(Request $request){
        return view('agencias/candidato');
    }


    //VISTA QUE OBTIENE LA INFORMACION DEL CANDIDATO
    public function votarcandidato(Request $request, $id){
    {
        $Tarjeton = DB::select("SELECT Foto, ID, Nombre, Apellidos, NoTarjeton, AgenciaD FROM delegados WHERE NoTarjeton = $request->NoTarjeton");
        $NoTarjeton = $request->NoTarjeton;
        if(empty($Tarjeton)){
            return back()->with("incorrecto", "Tarjetón #$request->NoTarjeton, ¡NO EXISTE!");
        }else{

            return view('agencias/candidato', ['Tarjeton' => $Tarjeton, 'id' => $id, 'NoTarjeton' => $NoTarjeton]);
        }
    }
}


    public function votar(Request $request, $id, $NoTarjeton){
        $Voto = DB::select("SELECT Voto, Cedula, Agencia FROM entrada WHERE ID = ?", [$id]);
    
        if(empty($Voto)){
            return redirect('/entrance')->with("incorrecto", "¡El registro no existe!");

        }
    
        $estadoVoto = $Voto[0]->Voto;
        $Cedula = $Voto[0]->Cedula;
        $Agencia = $Voto[0]->Agencia;
    
        if($estadoVoto == 1){
            return redirect('/entrance')->with("incorrecto", "¡El Asociado con cc. $Cedula YA VOTÓ!");
        }
    

    
        if($estadoVoto == 0){

            if($Agencia == 'Bogotá Elemento') {
                $sql2 = DB::update("UPDATE delegados SET columna_13 = columna_13+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if($Agencia == 'CaliBC') {
                $sql2 = DB::update("UPDATE delegados SET columna_30 = columna_30+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if($Agencia == 'Cali') {
                $sql2 = DB::update("UPDATE delegados SET columna_31 = columna_31+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if($Agencia == 'Palmira') {
                $sql2 = DB::update("UPDATE delegados SET columna_32 = columna_32+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if($Agencia == 'Buga') {
                $sql2 = DB::update("UPDATE delegados SET columna_34 = columna_34+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if($Agencia == 'Buenaventura') {
                $sql2 = DB::update("UPDATE delegados SET columna_33 = columna_33+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if($Agencia == 'Tuluá') {
                $sql2 = DB::update("UPDATE delegados SET columna_35 = columna_35+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if($Agencia == 'Sevilla') {
                $sql2 = DB::update("UPDATE delegados SET columna_36 = columna_36+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if($Agencia == 'La Unión') {
                $sql2 = DB::update("UPDATE delegados SET columna_37 = columna_37+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if($Agencia == 'Roldanillo') {
                $sql2 = DB::update("UPDATE delegados SET columna_38 = columna_38+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            }else if ($Agencia == 'Cartago') {
                $sql2 = DB::update("UPDATE delegados SET columna_39 = columna_39+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Zarzal') {
                $sql2 = DB::update("UPDATE delegados SET columna_40 = columna_40+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Caicedonia') {
                $sql2 = DB::update("UPDATE delegados SET columna_41 = columna_41+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'S Quilichao') {
                $sql2 = DB::update("UPDATE delegados SET columna_42 = columna_42+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Yumbo') {
                $sql2 = DB::update("UPDATE delegados SET columna_43 = columna_43+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Jamundí') {
                $sql2 = DB::update("UPDATE delegados SET columna_44 = columna_44+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Pasto') {
                $sql2 = DB::update("UPDATE delegados SET columna_45 = columna_45+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Popayán') {
                $sql2 = DB::update("UPDATE delegados SET columna_46 = columna_46+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Ipiales') {
                $sql2 = DB::update("UPDATE delegados SET columna_47 = columna_47+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Leticia') {
                $sql2 = DB::update("UPDATE delegados SET columna_48 = columna_48+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Puerto Asis') {
                $sql2 = DB::update("UPDATE delegados SET columna_49 = columna_49+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Soacha') {
                $sql2 = DB::update("UPDATE delegados SET columna_68 = columna_68+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Manizales') {
                $sql2 = DB::update("UPDATE delegados SET columna_70 = columna_70+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Zipaquirá') {
                $sql2 = DB::update("UPDATE delegados SET columna_73 = columna_73+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Facatativá') {
                $sql2 = DB::update("UPDATE delegados SET columna_75 = columna_75+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Pereira') {
                $sql2 = DB::update("UPDATE delegados SET columna_74 = columna_74+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Girardot') {
                $sql2 = DB::update("UPDATE delegados SET columna_76 = columna_76+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'San Andrés') {
                $sql2 = DB::update("UPDATE delegados SET columna_77 = columna_77+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Armenia') {
                $sql2 = DB::update("UPDATE delegados SET columna_78 = columna_78+1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Medellín') {
                $sql2 = DB::update("UPDATE delegados SET columna_80 = columna_80 + 1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Monteria') {
                $sql2 = DB::update("UPDATE delegados SET columna_81 = columna_81 + 1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Sincelejo') {
                $sql2 = DB::update("UPDATE delegados SET columna_82 = columna_82 + 1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Yopal') {
                $sql2 = DB::update("UPDATE delegados SET columna_83 = columna_83 + 1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Riohacha') {
                $sql2 = DB::update("UPDATE delegados SET columna_84 = columna_84 + 1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Valledupar') {
                $sql2 = DB::update("UPDATE delegados SET columna_85 = columna_85 + 1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Cartagena') {
                $sql2 = DB::update("UPDATE delegados SET columna_86 = columna_86 + 1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Santa Marta') {
                $sql2 = DB::update("UPDATE delegados SET columna_88 = columna_88 + 1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Duitama') {
                $sql2 = DB::update("UPDATE delegados SET columna_89 = columna_89 + 1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Bogotá Centro') {
                $sql2 = DB::update("UPDATE delegados SET columna_90 = columna_90 + 1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Bogotá TC') {
                $sql2 = DB::update("UPDATE delegados SET columna_91 = columna_91 + 1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Bogotá Norte') {
                $sql2 = DB::update("UPDATE delegados SET columna_92 = columna_92 + 1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Villavicencio') {
                $sql2 = DB::update("UPDATE delegados SET columna_93 = columna_93 + 1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Tunja') {
                $sql2 = DB::update("UPDATE delegados SET columna_94 = columna_94 + 1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Ibagué') {
                $sql2 = DB::update("UPDATE delegados SET columna_95 = columna_95 + 1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Neiva') {
                $sql2 = DB::update("UPDATE delegados SET columna_96 = columna_96 + 1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Bucaramanga') {
                $sql2 = DB::update("UPDATE delegados SET columna_97 = columna_97 + 1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            } else if ($Agencia == 'Cúcuta') {
                $sql2 = DB::update("UPDATE delegados SET columna_98 = columna_98 + 1 WHERE NoTarjeton = ?", [$NoTarjeton]);
            }
        }
    
        if($sql2){
            $sql = DB::update("UPDATE entrada SET Voto = 1 WHERE ID = ?", [$id]);
            return redirect('/entrance')->with("correcto", "¡El voto se registró correctamente!");
        } else {
            return redirect('/entrance')->with("incorrecto", "¡Hubo un problema al registrar el voto!");

        }
    }


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
