<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



//ALTER TABLE entrada AUTO_INCREMENT = 1;
class RegistroCC extends Controller
{
    public function data(){
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;
        $user = DB::select("
        SELECT * FROM entrada
        ");

        return datatables()->of($user)->toJson();

    }


    public function data2()
    {
        $user = DB::select("SELECT * FROM users WHERE rol = 'Agencia'");
        
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

    public function datadelegate(){
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;
        $user = DB::select("
        SELECT * FROM delegados ORDER BY Posicion ASC
        ");

        return datatables()->of($user)->toJson();

    }

    public function createdelegate (Request $request){
    $existingCard = DB::select("SELECT NoTarjeton FROM delegados where NoTarjeton = ?", [$request->NoTarjeton]);

    if($existingCard == true){
        return back()->with("incorrecto", "Existe un delegado vinculado al tarjeton No. " . $request->NoTarjeton);
    } else {
        // date_default_timezone_set('America/Bogota');
        // $fechaHoraActual = date('Y-m-d H:i');
    $sql = DB::insert('INSERT INTO delegados (AgenciaD, NoTarjeton, Nombre, Apellidos, Fecharegistro, Horaregistro) VALUES (?, ?, UPPER(?), UPPER(?), ?, ?)', [
            $request->Agencia,
            $request->NoTarjeton,
            $request->Nombre,
            $request->Apellidos,
            $request->Fecharegistro,
            $request->Horaregistro
        ]);

        if ($sql) {
            return back()->with("correcto", "Persona Registrada correctamente!");
        } else {
            return back()->with("incorrecto", "Error al insertar el registro!");
        }

    }
}

        public function delete($id){
            $sql = DB::update("DELETE FROM delegados WHERE ID=$id", []);
        

        if ($sql) {
            return back()->with("correcto", "Registro eliminado correctamente!");
        } else {
            return back()->with("incorrecto", "Error al eliminar!");
        }
    }


public function store(Request $request)
{
    $existingPerson = DB::select('SELECT * FROM users WHERE email = ?', [$request->email]);

    if ($existingPerson == true) {
        return back()->with("incorrecto", "Correo registrado en la base de datos! Error al Registrar!");
    }

    if (!preg_match('/^[a-zA-Z\sñÑ]+$/', $request->name)) {
        return back()->with("incorrecto", "El nombre de usuario debe contener solo letras!");
    }

    if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
        return back()->with("incorrecto", "El correo electrónico no tiene un formato válido!");
    }

    if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,12}$/', $request->password)) {
        return back()->with("incorrecto", "La contraseña debe tener entre 8 y 12 caracteres y contener al menos una letra, un número y un símbolo!");
    }

    if ($request->password !== $request->password_confirmation) {
        return back()->with("incorrecto", "Las contraseñas no coinciden. Por favor, inténtelo de nuevo.");
    }

    

    $this->validate(request(), [
        'name' => 'required',
        'email' => 'required|email',
        'rol' => 'required',
        'password' => 'required|confirmed',
        'agenciau' => 'required'
    ]);

    $user = User::create(request(['name', 'email', 'rol', 'password', 'agenciau']));


    return back()->with("correcto", "Persona registrada correctamente!");
}




    public function updatedelegate($id, Request $request){
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;
        

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
            
            $sql = DB::update("UPDATE delegados SET columna_$columna=? WHERE ID = $id", [
                $request->Votos,
            ]);

        }

        $nombreResult = DB::select("SELECT Nombre FROM delegados WHERE ID = $id");
        $apellidosResult = DB::select("SELECT Apellidos FROM delegados WHERE ID = $id");

        $nombre = $nombreResult[0]->Nombre;
        $apellidos = $apellidosResult[0]->Apellidos;

        $nombrecompleto = $nombre . ' ' . $apellidos;
    
        if ($sql) {
            return back()->with("correcto",  $request->Votos . " voto(s) añadido(s) correctamente a ".$nombrecompleto."!");
        } else {
            return back()->with("incorrecto", "¡Error al añadir votos al registro!");
        }
    }

    public function imprimir()
    {
        $sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");

        $anchoHoja = 1115; // Ancho de la hoja A4
        $altoHoja = 700; // Alto de la hoja A4
    
        $fpdf = new Fpdf('P', 'mm', [$anchoHoja, $altoHoja]); // Personaliza el tamaño de la hoja
        $fpdf->AddPage("landscape");


        $headers = ['Tarjeton', 'Candidatos', '13', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '68', '70', '73', '74', '76', '77', '78', '80', '81', '82', '83', '84', '85', '86', '87', '88', '89', '90', '91', '92', '93', '94', '95', '96', '97', '98', 'Total'];

        Carbon::setLocale('es');
        $fecha_actual = Carbon::now('America/Bogota');
        $fecha_formateada = $fecha_actual->isoFormat('dddd, D [de] MMMM [de] YYYY');
        $fecha_formateada = $fecha_actual->isoFormat('dddd, D [de] MMMM [de] YYYY , [Hora de Generación] - H:mm:ss');
        $fecha_formateada = preg_replace_callback('/\b(\p{L}+)\b/u', function ($matches) {
            return ucfirst($matches[0]);
        }, $fecha_formateada);
        $fpdf->SetFont('Arial', 'B', 90);
        $fpdf->SetX(350); 
        $fpdf->Cell(0, 15, utf8_decode('5'), 0, 0, 'L'); 
        $fpdf->SetX(0);
        $fpdf->SetFont('Arial', 'B', 20);
        $fpdf->Cell(0, 10, utf8_decode('COOPERATIVA DE SERVIDORES PÚBLICOS Y JUBILADOS DE COLOMBIA "COOPSERP COLOMBIA"'), 0, 0, 'C');
        $fpdf->Ln();    
        $fpdf->Cell(0, 10, utf8_decode("ELECCIÓN DE DELEGADOS PERÍODO 2024 - 2029"), 0, 0, 'C');
        $fpdf->Ln();
        $fpdf->Cell(0, 10, utf8_decode("Fecha y Hora de votaciones: 17 de Noviembre de 2023 de 8:00am a 4:00pm"), 0, 0, 'C');
        $fpdf->Ln();
        $fpdf->Cell(0,10, utf8_decode($fecha_formateada), 0, 0, 'C');    

        $fpdf->Ln(); $fpdf->Ln();    
        $columnWidthText = 40;
        $columnWidth = 40;
        $columnWidthCandidatos = 120;
        $columnWidthNumber = 20; 
        $headerFontSize = 16;

        $headerFillColor = [180, 180, 180];
        $fpdf->SetFillColor($headerFillColor[0], $headerFillColor[1], $headerFillColor[2]);


        foreach ($headers as $header) {
            if ($header === 'Candidatos') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthCandidatos, 15, $header, 1, 0, 'C', true); 
            } elseif ($header === 'Tarjeton' || $header === 'Votos x Cand') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthText, 15, $header, 1, 0, 'C', true); 
            } else {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthNumber, 15, $header, 1, 0, 'C', true); 
            }
        }
    
        $fpdf->Ln(); 
    
        $fpdf->SetFont('Helvetica', '', 16);

        foreach ($sql as $row) {
            $fpdf->Cell($columnWidth, 10, $row->NoTarjeton, 1, 0, 'C');
            $fpdf->Cell($columnWidthCandidatos, 10, $row->Nombre ." ". $row->Apellidos, 1);
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_13, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_30, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_31, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_32, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_33, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_34, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_35, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_36, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_37, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_38, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_39, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_40, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_41, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_42, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_43, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_44, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_45, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_46, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_47, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_48, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_68, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_70, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_73, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_74, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_76, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_77, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_78, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_80, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_81, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_82, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_83, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_84, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_85, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_86, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_87, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_88, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_89, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_90, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_91, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_92, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_93, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_94, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_95, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_96, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_97, 1, 0, 'C');
            $fpdf->Cell($columnWidthNumber, 10, $row->columna_98, 1, 0, 'C');

            $totalSum = intval($row->columna_13) +
                intval($row->columna_30) +
                intval($row->columna_31) +
                intval($row->columna_32) +
                intval($row->columna_33) +
                intval($row->columna_34) +
                intval($row->columna_35) +
                intval($row->columna_36) +
                intval($row->columna_37) +
                intval($row->columna_38) +
                intval($row->columna_39) +
                intval($row->columna_40) +
                intval($row->columna_41) +
                intval($row->columna_42) +
                intval($row->columna_43) +
                intval($row->columna_44) +
                intval($row->columna_45) +
                intval($row->columna_46) +
                intval($row->columna_47) +
                intval($row->columna_48) +
                intval($row->columna_68) +
                intval($row->columna_70) +
                intval($row->columna_73) +
                intval($row->columna_74) +
                intval($row->columna_76) +
                intval($row->columna_77) +
                intval($row->columna_78) +
                intval($row->columna_80) +
                intval($row->columna_81) +
                intval($row->columna_82) +
                intval($row->columna_83) +
                intval($row->columna_84) +
                intval($row->columna_85) +
                intval($row->columna_86) +
                intval($row->columna_87) +
                intval($row->columna_88) +
                intval($row->columna_89) +
                intval($row->columna_90) +
                intval($row->columna_91) +
                intval($row->columna_92) +
                intval($row->columna_93) +
                intval($row->columna_94) +
                intval($row->columna_95) +
                intval($row->columna_96) +
                intval($row->columna_97) +
                intval($row->columna_98);
            $fpdf->Cell($columnWidthNumber, 10, $totalSum, 1, 0, 'C');
            $noTarjeton = $row->NoTarjeton; 

            DB::table('delegados')
        ->where('NoTarjeton', $noTarjeton)
        ->update(['Total' => $totalSum]);
            

            $fpdf->Ln(); 
        
    }

    $candidatos = DB::table('delegados')
    ->orderByDesc('Total') 
    ->orderBy('Fecharegistro') 
    ->orderBy('Horaregistro') 
    ->get();

    $posicion = 1;
foreach ($candidatos as $candidato) {
DB::table('delegados')
    ->where('ID', $candidato->ID)
    ->update(['Posicion' => $posicion]);
$posicion++;
}

    $totalSum = 0;
    $data = DB::select("SELECT * FROM delegados");
foreach ($data as $row) {

    $rowTotal = intval($row->columna_13) +
    intval($row->columna_30) +
    intval($row->columna_31) +
    intval($row->columna_32) +
    intval($row->columna_33) +
    intval($row->columna_34) +
    intval($row->columna_35) +
    intval($row->columna_36) +
    intval($row->columna_37) +
    intval($row->columna_38) +
    intval($row->columna_39) +
    intval($row->columna_40) +
    intval($row->columna_41) +
    intval($row->columna_42) +
    intval($row->columna_43) +
    intval($row->columna_44) +
    intval($row->columna_45) +
    intval($row->columna_46) +
    intval($row->columna_47) +
    intval($row->columna_48) +
    intval($row->columna_68) +
    intval($row->columna_70) +
    intval($row->columna_73) +
    intval($row->columna_74) +
    intval($row->columna_76) +
    intval($row->columna_77) +
    intval($row->columna_78) +
    intval($row->columna_80) +
    intval($row->columna_81) +
    intval($row->columna_82) +
    intval($row->columna_83) +
    intval($row->columna_84) +
    intval($row->columna_85) +
    intval($row->columna_86) +
    intval($row->columna_87) +
    intval($row->columna_88) +
    intval($row->columna_89) +
    intval($row->columna_90) +
    intval($row->columna_91) +
    intval($row->columna_92) +
    intval($row->columna_93) +
    intval($row->columna_94) +
    intval($row->columna_95) +
    intval($row->columna_96) +
    intval($row->columna_97) +
    intval($row->columna_98);

    
    $totalSum += $rowTotal;
}
$columnWidthTotal = 1080;
$columnWidthTotal2 = 20;
$fpdf->SetFont('Arial', 'B', 20);
$fpdf->Cell($columnWidthTotal, 10, "Total votos", 1, 0, 'R');
$fpdf->Cell($columnWidthTotal2, 10, $totalSum, 1, 0, 'C');
$fpdf->Ln();         $fpdf->Ln(); 


$headers2 = ['13', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '68', '70', '73', '74', '76', '77', '78', '80', '81', '82', '83', '84', '85', '86', '87', '88', '89', '90', '91', '92', '93', '94', '95', '96', '97', '98', 'Total'];
$columnWidthTotal3 = 160;
$columnWidthTotal4 = 20;
$fpdf->Cell($columnWidthTotal3, 15, "Agencia", 1, 0, 'R');


foreach ($headers2 as $header) {
    if ($header === 'Candidatos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 15, $header, 1, 0, 'C', true); 
    } elseif ($header === 'Tarjeton' || $header === 'Votos x Cand') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthText, 15, $header, 1, 0, 'C', true); 
    } else {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthNumber, 15, $header, 1, 0, 'C', true); 
    }
}

$fpdf->SetFont('Arial', 'B', 20);
$fpdf->Ln();
$fpdf->Cell($columnWidthTotal3, 10, "Votos por Agencia", 1, 0, 'R');

$sumaColumna13 = DB::table('delegados')->sum('columna_13');
$sumaColumna30 = DB::table('delegados')->sum('columna_30');
$sumaColumna31 = DB::table('delegados')->sum('columna_31');
$sumaColumna32 = DB::table('delegados')->sum('columna_32');
$sumaColumna33 = DB::table('delegados')->sum('columna_33');
$sumaColumna34 = DB::table('delegados')->sum('columna_34');
$sumaColumna35 = DB::table('delegados')->sum('columna_35');
$sumaColumna36 = DB::table('delegados')->sum('columna_36');
$sumaColumna37 = DB::table('delegados')->sum('columna_37');
$sumaColumna38 = DB::table('delegados')->sum('columna_38');
$sumaColumna39 = DB::table('delegados')->sum('columna_39');
$sumaColumna40 = DB::table('delegados')->sum('columna_40');
$sumaColumna41 = DB::table('delegados')->sum('columna_41');
$sumaColumna42 = DB::table('delegados')->sum('columna_42');
$sumaColumna43 = DB::table('delegados')->sum('columna_43');
$sumaColumna44 = DB::table('delegados')->sum('columna_44');
$sumaColumna45 = DB::table('delegados')->sum('columna_45');
$sumaColumna46 = DB::table('delegados')->sum('columna_46');
$sumaColumna47 = DB::table('delegados')->sum('columna_47');
$sumaColumna48 = DB::table('delegados')->sum('columna_48');
$sumaColumna68 = DB::table('delegados')->sum('columna_68');
$sumaColumna70 = DB::table('delegados')->sum('columna_70');
$sumaColumna73 = DB::table('delegados')->sum('columna_73');
$sumaColumna74 = DB::table('delegados')->sum('columna_74');
$sumaColumna76 = DB::table('delegados')->sum('columna_76');
$sumaColumna77 = DB::table('delegados')->sum('columna_77');
$sumaColumna78 = DB::table('delegados')->sum('columna_78');
$sumaColumna80 = DB::table('delegados')->sum('columna_80');
$sumaColumna81 = DB::table('delegados')->sum('columna_81');
$sumaColumna82 = DB::table('delegados')->sum('columna_82');
$sumaColumna83 = DB::table('delegados')->sum('columna_83');
$sumaColumna84 = DB::table('delegados')->sum('columna_84');
$sumaColumna85 = DB::table('delegados')->sum('columna_85');
$sumaColumna86 = DB::table('delegados')->sum('columna_86');
$sumaColumna87 = DB::table('delegados')->sum('columna_87');
$sumaColumna88 = DB::table('delegados')->sum('columna_88');
$sumaColumna89 = DB::table('delegados')->sum('columna_89');
$sumaColumna90 = DB::table('delegados')->sum('columna_90');
$sumaColumna91 = DB::table('delegados')->sum('columna_91');
$sumaColumna92 = DB::table('delegados')->sum('columna_92');
$sumaColumna93 = DB::table('delegados')->sum('columna_93');
$sumaColumna94 = DB::table('delegados')->sum('columna_94');
$sumaColumna95 = DB::table('delegados')->sum('columna_95');
$sumaColumna96 = DB::table('delegados')->sum('columna_96');
$sumaColumna97 = DB::table('delegados')->sum('columna_97');
$sumaColumna98 = DB::table('delegados')->sum('columna_98');

$sumaTotal = $sumaColumna13 + $sumaColumna30 + $sumaColumna31 + $sumaColumna32 + $sumaColumna33 + $sumaColumna34 + $sumaColumna35 + $sumaColumna36 + $sumaColumna37 + $sumaColumna38 + $sumaColumna39 + $sumaColumna40 + $sumaColumna41 + $sumaColumna42 + $sumaColumna43 + $sumaColumna44 + $sumaColumna45 + $sumaColumna46 + $sumaColumna47 + $sumaColumna48 + $sumaColumna68 + $sumaColumna70 + $sumaColumna73 + $sumaColumna74 + $sumaColumna76 + $sumaColumna77 + $sumaColumna78 + $sumaColumna80 + $sumaColumna81 + $sumaColumna82 + $sumaColumna83 + $sumaColumna84 + $sumaColumna85 + $sumaColumna86 + $sumaColumna87 + $sumaColumna88 + $sumaColumna89 + $sumaColumna90 + $sumaColumna91 + $sumaColumna92 + $sumaColumna93 + $sumaColumna94 + $sumaColumna95 + $sumaColumna96 + $sumaColumna97 + $sumaColumna98;


    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna13, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna30, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna31, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna32, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna33, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna34, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna35, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna36, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna37, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna38, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna39, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna40, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna41, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna42, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna43, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna44, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna45, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna46, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna47, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna48, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna68, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna70, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna73, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna74, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna76, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna77, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna78, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna80, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna81, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna82, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna83, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna84, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna85, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna86, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna87, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna88, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna89, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna90, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna91, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna92, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna93, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna94, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna95, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna96, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna97, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaColumna98, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $sumaTotal, 1, 0, 'C');
    

    $fpdf->Ln();         $fpdf->Ln(); 
    $columnWidthTotal3 = 160;
    $columnWidthTotal4 = 20;
    $fpdf->Cell($columnWidthTotal3, 15, "Agencia", 1, 0, 'R');
    foreach ($headers2 as $header) {
        if ($header === 'Candidatos') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 15, $header, 1, 0, 'C', true); 
        } elseif ($header === 'Tarjeton' || $header === 'Votos x Cand') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthText, 15, $header, 1, 0, 'C', true); 
        } else {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthNumber, 15, $header, 1, 0, 'C', true); 
        }
    }
    $fpdf->Ln();
    $fpdf->SetFont('Arial', 'B', 20);
    $fpdf->Cell($columnWidthTotal3, 10, "Ingresos por Agencia", 1, 0, 'R');
    $Agencia13 = DB::table('entrada')->where('agencia', 'Bogotá Elemento')->count();
    $Agencia30 = DB::table('entrada')->where('agencia', 'CaliBC')->count();
    $Agencia31 = DB::table('entrada')->where('agencia', 'Cali')->count();
    $Agencia32 = DB::table('entrada')->where('agencia', 'Palmira')->count();
    $Agencia34 = DB::table('entrada')->where('agencia', 'Buga')->count();
    $Agencia33 = DB::table('entrada')->where('agencia', 'Buenaventura')->count();
    $Agencia35 = DB::table('entrada')->where('agencia', 'Tuluá')->count();
    $Agencia36 = DB::table('entrada')->where('agencia', 'Sevilla')->count();
    $Agencia37 = DB::table('entrada')->where('agencia', 'La Unión')->count();
    $Agencia38 = DB::table('entrada')->where('agencia', 'Roldanillo')->count();
    $Agencia39 = DB::table('entrada')->where('agencia', 'Cartago')->count();
    $Agencia40 = DB::table('entrada')->where('agencia', 'Zarzal')->count();
    $Agencia41 = DB::table('entrada')->where('agencia', 'Caicedonia')->count();
    $Agencia42 = DB::table('entrada')->where('agencia', 'S Quilichao')->count();
    $Agencia43 = DB::table('entrada')->where('agencia', 'Yumbo')->count();
    $Agencia44 = DB::table('entrada')->where('agencia', 'Jamundí')->count();
    $Agencia45 = DB::table('entrada')->where('agencia', 'Pasto')->count();
    $Agencia46 = DB::table('entrada')->where('agencia', 'Popayán')->count();
    $Agencia47 = DB::table('entrada')->where('agencia', 'Ipiales')->count();
    $Agencia48 = DB::table('entrada')->where('agencia', 'Leticia')->count();
    $Agencia49 = DB::table('entrada')->where('agencia', 'Puerto Asis')->count();
    $Agencia68 = DB::table('entrada')->where('agencia', 'Soacha')->count();
    $Agencia70 = DB::table('entrada')->where('agencia', 'Manizales')->count();
    $Agencia73 = DB::table('entrada')->where('agencia', 'Zipaquirá')->count();
    $Agencia75 = DB::table('entrada')->where('agencia', 'Facatativá')->count();
    $Agencia74 = DB::table('entrada')->where('agencia', 'Pereira')->count();
    $Agencia76 = DB::table('entrada')->where('agencia', 'Girardot')->count();
    $Agencia77 = DB::table('entrada')->where('agencia', 'San Andrés')->count();
    $Agencia78 = DB::table('entrada')->where('agencia', 'Armenia')->count();
    $Agencia80 = DB::table('entrada')->where('agencia', 'Medellín')->count();
    $Agencia81 = DB::table('entrada')->where('agencia', 'Monteria')->count();
    $Agencia82 = DB::table('entrada')->where('agencia', 'Sincelejo')->count();
    $Agencia83 = DB::table('entrada')->where('agencia', 'Yopal')->count();
    $Agencia84 = DB::table('entrada')->where('agencia', 'Riohacha')->count();
    $Agencia85 = DB::table('entrada')->where('agencia', 'Valledupar')->count();
    $Agencia86 = DB::table('entrada')->where('agencia', 'Cartagena')->count();
    $Agencia87 = DB::table('entrada')->where('agencia', 'Barranquilla')->count();
    $Agencia88 = DB::table('entrada')->where('agencia', 'Santa Marta')->count();
    $Agencia89 = DB::table('entrada')->where('agencia', 'Duitama')->count();
    $Agencia90 = DB::table('entrada')->where('agencia', 'Bogotá Centro')->count();
    $Agencia91 = DB::table('entrada')->where('agencia', 'Bogotá TC')->count();
    $Agencia92 = DB::table('entrada')->where('agencia', 'Bogotá Norte')->count();
    $Agencia93 = DB::table('entrada')->where('agencia', 'Villavicencio')->count();
    $Agencia94 = DB::table('entrada')->where('agencia', 'Tunja')->count();
    $Agencia95 = DB::table('entrada')->where('agencia', 'Ibagué')->count();
    $Agencia96 = DB::table('entrada')->where('agencia', 'Neiva')->count();
    $Agencia97 = DB::table('entrada')->where('agencia', 'Bucaramanga')->count();
    $Agencia98 = DB::table('entrada')->where('agencia', 'Cúcuta')->count();
    
    $totalAgencias = $Agencia13 + $Agencia30 + $Agencia31 + $Agencia32 + $Agencia34 + $Agencia33 + $Agencia35 + $Agencia36 + $Agencia37 + $Agencia38 + $Agencia39 + $Agencia40 + $Agencia41 + $Agencia42 + $Agencia43 + $Agencia44 + $Agencia45 + $Agencia46 + $Agencia47 + $Agencia48 + $Agencia49 + $Agencia68 + $Agencia70 + $Agencia73 + $Agencia75 + $Agencia74 + $Agencia76 + $Agencia77 + $Agencia78 + $Agencia80 + $Agencia81 + $Agencia82 + $Agencia83 + $Agencia84 + $Agencia85 + $Agencia86 + $Agencia87 + $Agencia88 + $Agencia89 + $Agencia90 + $Agencia91 + $Agencia92 + $Agencia93 + $Agencia94 + $Agencia95 + $Agencia96 + $Agencia97 + $Agencia98;

    
    $fpdf->Cell($columnWidthNumber, 10, $Agencia13, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia30, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia31, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia32, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia34, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia33, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia35, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia36, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia37, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia38, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia39, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia40, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia41, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia42, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia43, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia44, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia45, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia46, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia47, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia48, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia68, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia70, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia73, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia74, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia76, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia77, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia78, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia80, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia81, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia82, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia83, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia84, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia85, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia86, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia87, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia88, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia89, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia90, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia91, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia92, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia93, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia94, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia95, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia96, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia97, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $Agencia98, 1, 0, 'C');
    $fpdf->Cell($columnWidthNumber, 10, $totalAgencias, 1, 0, 'C');
    $fpdf->Ln();$fpdf->Ln();$fpdf->Ln();




    $fpdf->Output('I', 'impresion/Ticket-' . "asd" . '.pdf');
    $fpdf->Output('F', 'impresion/Ticket-' . "asd" . '.pdf');
    exit;
        
        }



    public function imprimir2()
    {
        

        $anchoHoja = 700;
        $altoHoja = 700; 


    
        $fpdf = new Fpdf('P', 'mm', [$anchoHoja, $altoHoja]); // Personaliza el tamaño de la hoja
        $fpdf->AddPage("landscape");



        $posicionX = ($anchoHoja - 610) / 2;
        $fpdf->SetX($posicionX);
        Carbon::setLocale('es');
        $fecha_actual = Carbon::now('America/Bogota');
        $fecha_formateada = $fecha_actual->isoFormat('dddd, D [de] MMMM [de] YYYY , [Hora de Generación] - H:mm:ss');
        $fecha_formateada = preg_replace_callback('/\b(\p{L}+)\b/u', function ($matches) {
            return ucfirst($matches[0]);
        }, $fecha_formateada);
        $fpdf->SetFont('Arial', 'B', 90);
        $fpdf->SetX(20); 
        $fpdf->Cell(0, 15, utf8_decode('1'), 0, 0, 'L'); 
        $fpdf->SetX(0);
        $fpdf->SetFont('Arial', 'B', 35);
        $fpdf->Cell(0, 15, utf8_decode('COOPERATIVA DE SERVIDORES PÚBLICOS Y JUBILADOS DE COLOMBIA "COOPSERP COLOMBIA"'), 0, 0, 'C');
        $fpdf->Ln();    
        $fpdf->Cell(0, 15, utf8_decode("ELECCIÓN DE DELEGADOS PERÍODO 2024 - 2029"), 0, 0, 'C');
        $fpdf->Ln();
        $fpdf->Cell(0, 15, utf8_decode("Reporte parcial de votaciones EN ORDEN POR TARJETON"), 0, 0, 'C');
        $fpdf->Ln();
        $fpdf->Cell(0, 15, utf8_decode("Fecha y Hora de votaciones: 17 de noviembre de 2023 de 8:00am a 4:00pm"), 0, 0, 'C');
        $fpdf->Ln();
        $fpdf->Ln();
        $fpdf->Cell(0,15, utf8_decode($fecha_formateada), 0, 0, 'C');
            


        $fpdf->Ln(); $fpdf->Ln();    
        $columnWidthText = 40;
        $columnWidth = 40;
        $columnWidthCandidatos = 100;
        $columnWidthNumber = 20; 
        

        $fpdf->SetX($posicionX);
        $columnWidthTarjeton = 20;
        $columnWidthName = 285;
        $columnWidthVotes = 120;
        $headerFontSize = 40;

        $headers = ['Tarjetón', 'Nombre', 'Agencia del Candidato', 'Votos'];


        foreach ($headers as $header) {
            if ($header === 'Tarjetón') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'TB', 0, 'L'); 
            } elseif ($header === 'Nombre') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthName, 30, $header, 'TB', 0, 'L'); 
            } elseif ($header === 'Agencia del Candidato') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthCandidatos, 30, $header, 'TB', 0, 'L'); 
            }elseif ($header === 'Votos') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthVotes, 30, $header, 'TB', 0, 'R'); 
            }
        }


    
        $fpdf->Ln(); 

        $sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
        $fpdf->SetFont('Helvetica', '', 40);
        $sumaTotal = DB::table('delegados')->sum('Total');

        foreach ($sql as $row) {
            $fpdf->SetX($posicionX);
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
            $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos) ,0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
            $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->Total), 0, 0, 'R');
            

            $fpdf->Ln(); 
        
    }
    $fpdf->SetX($posicionX);
    $columnWidthTotal = 560;
    $columnWidthTotal2 = 100;
    $fpdf->SetFont('Arial', 'B', 40);
    $fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
    $fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

    $fpdf->Ln();         $fpdf->Ln(); 
    

    $fpdf->Output('I', 'impresion/Ticket-' . "asd" . '.pdf');
    $fpdf->Output('F', 'impresion/Ticket-' . "asd" . '.pdf');
    exit;
        
        }

        public function imprimir3()
    {
        

        $anchoHoja = 800;
        $altoHoja = 700; 


    
        $fpdf = new Fpdf('P', 'mm', [$anchoHoja, $altoHoja]); // Personaliza el tamaño de la hoja
        $fpdf->AddPage("landscape");



        $posicionX = ($anchoHoja - 750) / 2;
        $fpdf->SetX($posicionX);
        Carbon::setLocale('es');
        $fecha_actual = Carbon::now('America/Bogota');
        $fecha_formateada = $fecha_actual->isoFormat('dddd, D [de] MMMM [de] YYYY , [Hora de Generación] - H:mm:ss');
        $fecha_formateada = preg_replace_callback('/\b(\p{L}+)\b/u', function ($matches) {
            return ucfirst($matches[0]);
        }, $fecha_formateada);
        
        $fpdf->SetFont('Arial', 'B', 90);
        $fpdf->SetX(60); 
        $fpdf->Cell(0, 15, utf8_decode('2'), 0, 0, 'L'); 
        $fpdf->SetX(0);
        $fpdf->SetFont('Arial', 'B', 35);
        
        $fpdf->Cell(0, 15, utf8_decode('COOPERATIVA DE SERVIDORES PÚBLICOS Y JUBILADOS DE COLOMBIA "COOPSERP COLOMBIA"'), 0, 0, 'C');
        $fpdf->Ln();    
        $fpdf->Cell(0, 15, utf8_decode("ELECCIÓN DE DELEGADOS PERÍODO 2024 - 2029"), 0, 0, 'C');
        $fpdf->Ln();
        $fpdf->Cell(0, 15, utf8_decode("Reporte parcial de votaciones AGRUPADOS POR AGENCIA"), 0, 0, 'C');
        $fpdf->Ln();
        $fpdf->Cell(0, 15, utf8_decode("Fecha y Hora de votaciones: 17 de noviembre de 2023 de 8:00am a 4:00pm"), 0, 0, 'C');
        $fpdf->Ln();
        $fpdf->Ln();
        $fpdf->Cell(0,15, utf8_decode($fecha_formateada), 0, 0, 'C');    



        $fpdf->Ln(); $fpdf->Ln();    
        $columnWidthText = 40;
        $columnWidth = 40;
        $columnWidthCandidatos = 115;
        $columnWidthNumber = 20; 
        

        
        $columnWidthTarjeton = 20;
        $columnWidthName = 285;
        $columnWidthVotes = 120;
        $headerFontSize = 40;
        $fpdf->SetX($posicionX);
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $headers = ['Posición','Tarjetón', 'Nombre', 'Agencia del Candidato', 'Votos'];
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('13 - BOGOTA ELEMENTO'), 0, 0, 'L');
        $fpdf->Ln();


        

        $fpdf->SetX($posicionX);
        foreach ($headers as $header) {
            if ($header === 'Posición') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
 
            } elseif ($header === 'Tarjetón') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
            } elseif ($header === 'Nombre') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
            } elseif ($header === 'Agencia del Candidato') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
            }elseif ($header === 'Votos') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
            }
        }
        $fpdf->Ln();
        $sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
        $fpdf->SetFont('Helvetica', '', 40);
        foreach ($sql as $row) {
            if ($row->columna_13 != 0 && isset($row->NoTarjeton)) {
                $fpdf->SetX($posicionX);
                $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
                $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
                $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
                $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
                $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_13), 0, 0, 'R');
                $fpdf->Ln();
            }
        }
        
    $fpdf->Ln();
    $fpdf->SetX($posicionX);
    $columnWidthTotal = 710;
    $columnWidthTotal2 = 40;
    $sumaTotal = DB::table('delegados')->sum('columna_13');
    $fpdf->SetFont('Arial', 'B', 40);
    $fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
    $fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');
    $fpdf->Ln(); 
    $fpdf->Ln();         

    $fpdf->SetX($posicionX);
    $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
    $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('30 - CALIBC'), 0, 0, 'L');
    $fpdf->Ln();
    $fpdf->SetX($posicionX);
    foreach ($headers as $header) {
        if ($header === 'Posición') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');

        } elseif ($header === 'Tarjetón') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
        } elseif ($header === 'Nombre') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
        } elseif ($header === 'Agencia del Candidato') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
        }elseif ($header === 'Votos') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
        }
    }
    $fpdf->Ln();
    $sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
    $fpdf->SetFont('Helvetica', '', 40);
    foreach ($sql as $row) {
        if ($row->columna_30 != 0 && isset($row->NoTarjeton)) {
            $fpdf->SetX($posicionX);
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
            $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
            $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_30), 0, 0, 'R');
            $fpdf->Ln();
        }
    }
    
    $fpdf->SetX($posicionX);
    $columnWidthTotal = 710;
    $columnWidthTotal2 = 40;
    $sumaTotal = DB::table('delegados')->sum('columna_30');
    $fpdf->SetFont('Arial', 'B', 40);
    $fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
    $fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

    $fpdf->Ln();         $fpdf->Ln(); 

      $fpdf->SetX($posicionX);
    $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
    $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('31 - CALI'), 0, 0, 'L');
    $fpdf->Ln();
    $fpdf->SetX($posicionX);
    foreach ($headers as $header) {
        if ($header === 'Posición') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');

        } elseif ($header === 'Tarjetón') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
        } elseif ($header === 'Nombre') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
        } elseif ($header === 'Agencia del Candidato') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
        }elseif ($header === 'Votos') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
        }
    }
    $fpdf->Ln();
    $sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
    $fpdf->SetFont('Helvetica', '', 40);
    foreach ($sql as $row) {
        if ($row->columna_31 != 0 && isset($row->NoTarjeton)) {
            $fpdf->SetX($posicionX);
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
            $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
            $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_31), 0, 0, 'R');
            $fpdf->Ln();
        }
    }
    
    $fpdf->SetX($posicionX);
    $columnWidthTotal = 710;
    $columnWidthTotal2 = 40;
    $sumaTotal = DB::table('delegados')->sum('columna_31');
    $fpdf->SetFont('Arial', 'B', 40);
    $fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
    $fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

    $fpdf->Ln();         $fpdf->Ln(); 


    $fpdf->SetX($posicionX);
    $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
    $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('32 - PALMIRA'), 0, 0, 'L');
    $fpdf->Ln();
    $fpdf->SetX($posicionX);
    foreach ($headers as $header) {
        if ($header === 'Posición') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');

        } elseif ($header === 'Tarjetón') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
        } elseif ($header === 'Nombre') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
        } elseif ($header === 'Agencia del Candidato') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
        }elseif ($header === 'Votos') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
        }
    }
    $fpdf->Ln();
    $sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
    $fpdf->SetFont('Helvetica', '', 40);
    foreach ($sql as $row) {
        if ($row->columna_32 != 0 && isset($row->NoTarjeton)) {
            $fpdf->SetX($posicionX);
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
            $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
            $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_32), 0, 0, 'R');
            $fpdf->Ln();
        }
    }
    
    $fpdf->SetX($posicionX);
    $columnWidthTotal = 710;
    $columnWidthTotal2 = 40;
    $sumaTotal = DB::table('delegados')->sum('columna_32');
    $fpdf->SetFont('Arial', 'B', 40);
    $fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
    $fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

    $fpdf->Ln();         $fpdf->Ln(); 


    $fpdf->SetX($posicionX);
    $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
    $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('33 - BUENAVENTURA'), 0, 0, 'L');
    $fpdf->Ln();
    $fpdf->SetX($posicionX);
    foreach ($headers as $header) {
        if ($header === 'Posición') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');

        } elseif ($header === 'Tarjetón') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
        } elseif ($header === 'Nombre') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
        } elseif ($header === 'Agencia del Candidato') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
        }elseif ($header === 'Votos') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
        }
    }
    $fpdf->Ln();
    $sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
    $fpdf->SetFont('Helvetica', '', 40);
    foreach ($sql as $row) {
        if ($row->columna_33 != 0 && isset($row->NoTarjeton)) {
            $fpdf->SetX($posicionX);
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
            $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
            $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_33), 0, 0, 'R');
            $fpdf->Ln();
        }
    }
    
    $fpdf->SetX($posicionX);
    $columnWidthTotal = 710;
    $columnWidthTotal2 = 40;
    $sumaTotal = DB::table('delegados')->sum('columna_33');
    $fpdf->SetFont('Arial', 'B', 40);
    $fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
    $fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

    $fpdf->Ln();         $fpdf->Ln(); 

    $fpdf->SetX($posicionX);
    $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
    $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('34 - BUGA'), 0, 0, 'L');
    $fpdf->Ln();
    $fpdf->SetX($posicionX);
    foreach ($headers as $header) {
        if ($header === 'Posición') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');

        } elseif ($header === 'Tarjetón') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
        } elseif ($header === 'Nombre') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
        } elseif ($header === 'Agencia del Candidato') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
        }elseif ($header === 'Votos') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
        }
    }
    $fpdf->Ln();
    $sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
    $fpdf->SetFont('Helvetica', '', 40);
    foreach ($sql as $row) {
        if ($row->columna_34 != 0 && isset($row->NoTarjeton)) {
            $fpdf->SetX($posicionX);
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
            $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
            $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_34), 0, 0, 'R');
            $fpdf->Ln();
        }
    }
    
    $fpdf->SetX($posicionX);
    $columnWidthTotal = 710;
    $columnWidthTotal2 = 40;
    $sumaTotal = DB::table('delegados')->sum('columna_34');
    $fpdf->SetFont('Arial', 'B', 40);
    $fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
    $fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

    $fpdf->Ln();         $fpdf->Ln(); 


    $fpdf->SetX($posicionX);
    $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
    $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('35 - TULUA'), 0, 0, 'L');
    $fpdf->Ln();
    $fpdf->SetX($posicionX);
    foreach ($headers as $header) {
        if ($header === 'Posición') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');

        } elseif ($header === 'Tarjetón') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
        } elseif ($header === 'Nombre') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
        } elseif ($header === 'Agencia del Candidato') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
        }elseif ($header === 'Votos') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
        }
    }
    $fpdf->Ln();
    $sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
    $fpdf->SetFont('Helvetica', '', 40);
    foreach ($sql as $row) {
        if ($row->columna_35 != 0 && isset($row->NoTarjeton)) {
            $fpdf->SetX($posicionX);
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
            $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
            $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_35), 0, 0, 'R');
            $fpdf->Ln();
        }
    }
    
    $fpdf->SetX($posicionX);
    $columnWidthTotal = 710;
    $columnWidthTotal2 = 40;
    $sumaTotal = DB::table('delegados')->sum('columna_35');
    $fpdf->SetFont('Arial', 'B', 40);
    $fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
    $fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

    $fpdf->Ln();         $fpdf->Ln(); 



    $fpdf->SetX($posicionX);
    $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
    $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('36 - SEVILLA'), 0, 0, 'L');
    $fpdf->Ln();
    $fpdf->SetX($posicionX);
    foreach ($headers as $header) {
        if ($header === 'Posición') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');

        } elseif ($header === 'Tarjetón') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
        } elseif ($header === 'Nombre') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
        } elseif ($header === 'Agencia del Candidato') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
        }elseif ($header === 'Votos') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
        }
    }
    $fpdf->Ln();
    $sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
    $fpdf->SetFont('Helvetica', '', 40);
    foreach ($sql as $row) {
        if ($row->columna_36 != 0 && isset($row->NoTarjeton)) {
            $fpdf->SetX($posicionX);
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
            $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
            $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_36), 0, 0, 'R');
            $fpdf->Ln();
        }
    }
    
    $fpdf->SetX($posicionX);
    $columnWidthTotal = 710;
    $columnWidthTotal2 = 40;
    $sumaTotal = DB::table('delegados')->sum('columna_36');
    $fpdf->SetFont('Arial', 'B', 40);
    $fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
    $fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

    $fpdf->Ln();         $fpdf->Ln(); 



    $fpdf->SetX($posicionX);
    $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
    $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('37 - LA UNION'), 0, 0, 'L');
    $fpdf->Ln();
    $fpdf->SetX($posicionX);
    foreach ($headers as $header) {
        if ($header === 'Posición') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');

        } elseif ($header === 'Tarjetón') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
        } elseif ($header === 'Nombre') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
        } elseif ($header === 'Agencia del Candidato') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
        }elseif ($header === 'Votos') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
        }
    }
    $fpdf->Ln();
    $sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
    $fpdf->SetFont('Helvetica', '', 40);
    foreach ($sql as $row) {
        if ($row->columna_37 != 0 && isset($row->NoTarjeton)) {
            $fpdf->SetX($posicionX);
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
            $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
            $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_37), 0, 0, 'R');
            $fpdf->Ln();
        }
    }
    
    $fpdf->SetX($posicionX);
    $columnWidthTotal = 710;
    $columnWidthTotal2 = 40;
    $sumaTotal = DB::table('delegados')->sum('columna_37');
    $fpdf->SetFont('Arial', 'B', 40);
    $fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
    $fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

    $fpdf->Ln();         $fpdf->Ln(); 


    //rol
    $fpdf->SetX($posicionX);
    $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
    $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('38 - ROLDANILLO'), 0, 0, 'L');
    $fpdf->Ln();

    $fpdf->SetX($posicionX);
    foreach ($headers as $header) {
        if ($header === 'Posición') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
        } elseif ($header === 'Tarjetón') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
        } elseif ($header === 'Nombre') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
        } elseif ($header === 'Agencia del Candidato') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
        } elseif ($header === 'Votos') {
            $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
            $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
        }
    }
    $fpdf->Ln();

    $sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
    $fpdf->SetFont('Helvetica', '', 40);
    foreach ($sql as $row) {
        if ($row->columna_38 != 0 && isset($row->NoTarjeton)) {
            $fpdf->SetX($posicionX);
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
            $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
            $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_38), 0, 0, 'R');
            $fpdf->Ln();
        }
    }

    $fpdf->SetX($posicionX);
    $columnWidthTotal = 710;
    $columnWidthTotal2 = 40;
    $sumaTotal = DB::table('delegados')->sum('columna_38');
    $fpdf->SetFont('Arial', 'B', 40);
    $fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
    $fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

    $fpdf->Ln();         
    $fpdf->Ln(); 

    // Columna 39 - CARTAGO
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('39 - CARTAGO'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_39 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_39), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_39');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 40 - ZARZAL
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('40 - ZARZAL'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_40 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_40), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_40');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 




// Columna 41 - CAICEDONIA
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('41 - CAICEDONIA'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_41 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_41), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_41');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 42 - SANTANDER DE QUILICHAO
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('42 - SANTANDER DE QUILICHAO'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_42 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_42), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_42');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 43 - YUMBO
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('43 - YUMBO'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_43 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_43), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_43');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 44 - JAMUNDI
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('44 - JAMUNDI'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_44 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_44), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_44');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 


// Columna 45 - PASTO
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('45 - PASTO'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_45 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_45), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_45');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 46 - POPAYAN
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('46 - POPAYAN'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_46 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_46), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_46');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 47 - IPIALES
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('47 - IPIALES'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_47 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_47), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_47');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 48 - LETICIA
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('48 - LETICIA'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_48 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_48), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_48');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 68 - SOACHA
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('68 - SOACHA'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_68 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_68), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_68');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 70 - MANIZALES
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('70 - MANIZALES'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_70 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_70), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_70');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 73 - ZIPAQUIRA
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('73 - ZIPAQUIRA'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_73 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_73), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_73');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 76 - GIRARDOT
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('76 - GIRARDOT'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_76 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_76), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_76');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 77 - SAN ANDRES
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('77 - SAN ANDRES'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_77 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_77), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_77');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 78 - ARMENIA
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('78 - ARMENIA'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_78 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_78), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_78');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 


// Columna 80 - MEDELLIN
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('80 - MEDELLIN'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_80 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_80), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_80');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 81 - MONTERIA
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('81 - MONTERIA'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_81 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_81), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_81');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 82 - SINCELEJO
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('82 - SINCELEJO'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_82 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_82), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_82');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 83 - YOPAL
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('83 - YOPAL'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_83 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_83), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_83');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 84 - RIOHACHA
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('84 - RIOHACHA'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_84 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_84), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_84');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 85 - VALLEDUPAR
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('85 - VALLEDUPAR'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_85 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_85), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_85');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln();

// Columna 86 - CARTAGENA
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('86 - CARTAGENA'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_86 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_86), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_86');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 87 - BARRANQUILLA
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('87 - BARRANQUILLA'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_87 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_87), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_87');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 88 - SANTA MARTA
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('88 - SANTA MARTA'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_88 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_88), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_88');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 89 - DUITAMA
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('89 - DUITAMA'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_89 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_89), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_89');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 90 - BOGOTA CENTRO
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('90 - BOGOTA CENTRO'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_90 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_90), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_90');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 91 - BOGOTA T.C.
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('91 - BOGOTA T.C.'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_91 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_91), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_91');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 92 - BOGOTA NORTE
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('92 - BOGOTA NORTE'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_92 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_92), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_92');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 93 - VILLAVICENCIO
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('93 - VILLAVICENCIO'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_93 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_93), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_93');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 94 - TUNJA
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('94 - TUNJA'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_94 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_94), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_94');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 95 - IBAGUE
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('95 - IBAGUE'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_95 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_95), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_95');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 96 - NEIVA
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('96 - NEIVA'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_96 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_96), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_96');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 97 - BUCARAMANGA
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('97 - BUCARAMANGA'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_97 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_97), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_97');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();         
$fpdf->Ln(); 

// Columna 98 - CUCUTA
$fpdf->SetX($posicionX);
$fpdf->SetFont('Helvetica', 'B', $headerFontSize);
$fpdf->Cell($columnWidthCandidatos, 30, utf8_decode('98 - CUCUTA'), 0, 0, 'L');
$fpdf->Ln();

$fpdf->SetX($posicionX);
foreach ($headers as $header) {
    if ($header === 'Posición') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L');
    } elseif ($header === 'Tarjetón') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'T', 0, 'L'); 
    } elseif ($header === 'Nombre') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Agencia del Candidato') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 'T', 0, 'L'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthVotes, 30, $header, 'T', 0, 'R'); 
    }
}
$fpdf->Ln();

$sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
$fpdf->SetFont('Helvetica', '', 40);
foreach ($sql as $row) {
    if ($row->columna_98 != 0 && isset($row->NoTarjeton)) {
        $fpdf->SetX($posicionX);
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
        $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos), 0, 0, 'L');
        $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
        $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->columna_98), 0, 0, 'R');
        $fpdf->Ln();
    }
}

$fpdf->SetX($posicionX);
$columnWidthTotal = 710;
$columnWidthTotal2 = 40;
$sumaTotal = DB::table('delegados')->sum('columna_98');
$fpdf->SetFont('Arial', 'B', 40);
$fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
$fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');

$fpdf->Ln();
$fpdf->Ln();

$headers2 = ['#','Agencia', 'Votos'];
$posicionX2 = ($anchoHoja - 20) / 5;
$fpdf->SetX($posicionX2);
foreach ($headers2 as $header) {
    if ($header === '#') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 1, 0, 'C');

    } elseif ($header === 'Agencia') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName, 30, utf8_decode($header), 1, 0, 'C'); 
    } elseif ($header === 'Votos') {
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, $header, 1, 0, 'C'); 
    }
}
$fpdf->Ln();

    //Actualizar votos suma total
    $sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
    foreach ($sql as $row) {
    $totalSum = intval($row->columna_13) +
                    intval($row->columna_30) +
                    intval($row->columna_31) +
                    intval($row->columna_32) +
                    intval($row->columna_33) +
                    intval($row->columna_34) +
                    intval($row->columna_35) +
                    intval($row->columna_36) +
                    intval($row->columna_37) +
                    intval($row->columna_38) +
                    intval($row->columna_39) +
                    intval($row->columna_40) +
                    intval($row->columna_41) +
                    intval($row->columna_42) +
                    intval($row->columna_43) +
                    intval($row->columna_44) +
                    intval($row->columna_45) +
                    intval($row->columna_46) +
                    intval($row->columna_47) +
                    intval($row->columna_48) +
                    intval($row->columna_68) +
                    intval($row->columna_70) +
                    intval($row->columna_73) +
                    intval($row->columna_74) +
                    intval($row->columna_76) +
                    intval($row->columna_77) +
                    intval($row->columna_78) +
                    intval($row->columna_80) +
                    intval($row->columna_81) +
                    intval($row->columna_82) +
                    intval($row->columna_83) +
                    intval($row->columna_84) +
                    intval($row->columna_85) +
                    intval($row->columna_86) +
                    intval($row->columna_87) +
                    intval($row->columna_88) +
                    intval($row->columna_89) +
                    intval($row->columna_90) +
                    intval($row->columna_91) +
                    intval($row->columna_92) +
                    intval($row->columna_93) +
                    intval($row->columna_94) +
                    intval($row->columna_95) +
                    intval($row->columna_96) +
                    intval($row->columna_97) +
                    intval($row->columna_98);

                $noTarjeton = $row->NoTarjeton; 
    
                DB::table('delegados')
            ->where('NoTarjeton', $noTarjeton)
            ->update(['Total' => $totalSum]);
                
    
                $fpdf->Ln(); 
            
        }
    
        $candidatos = DB::table('delegados')
        ->orderByDesc('Total') 
        ->orderBy('Fecharegistro') 
        ->orderBy('Horaregistro') 
        ->get();
    
        $posicion = 1;
    foreach ($candidatos as $candidato) {
    DB::table('delegados')
        ->where('ID', $candidato->ID)
        ->update(['Posicion' => $posicion]);
    $posicion++;
    }

    $agenciaColumnaMapping = [
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
    
    foreach ($agenciaColumnaMapping as $ciudad => $numero) {
        $fpdf->SetX($posicionX2);
        $fpdf->SetFont('Helvetica', '', $headerFontSize);
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($numero), 1, 0, 'C');
        $fpdf->Cell($columnWidthName, 30, utf8_decode($ciudad), 1, 0, 'C');
        $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($row->Total), 1, 0, 'C');

        $fpdf->Ln();
    }
 

    
    $columnWidthName4 = 580;
    $columnWidthName5 = -245;
    
    $sumaTotal = DB::table('delegados')->sum('Total');
    $fpdf->SetX($posicionX2);
        $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
        $fpdf->Cell($columnWidthName4, 30, utf8_decode("Total Votos Todas las Agencias:"), 0, 0, 'C'); 
        $fpdf->SetFont('Helvetica', '', $headerFontSize);
        $fpdf->Cell($columnWidthName5, 30, utf8_decode($sumaTotal), 0, 0, 'C'); 

    $fpdf->Output('I', 'impresion/Ticket-' . "asd" . '.pdf');
    $fpdf->Output('F', 'impresion/Ticket-' . "asd" . '.pdf');
    exit;
        
        }

    public function imprimir4(){
        $anchoHoja = 820;
        $altoHoja = 700; 


    
        $fpdf = new Fpdf('P', 'mm', [$anchoHoja, $altoHoja]); // Personaliza el tamaño de la hoja
        $fpdf->AddPage("landscape");



        $posicionX = ($anchoHoja - 700) / 2;
        $fpdf->SetX($posicionX);
        Carbon::setLocale('es');
        $fecha_actual = Carbon::now('America/Bogota');
        $fecha_formateada = $fecha_actual->isoFormat('dddd, D [de] MMMM [de] YYYY , [Hora de Generación] - H:mm:ss');
        $fecha_formateada = preg_replace_callback('/\b(\p{L}+)\b/u', function ($matches) {
            return ucfirst($matches[0]);
        }, $fecha_formateada);
        $fpdf->SetFont('Arial', 'B', 90);
        $fpdf->SetX(60); 
        $fpdf->Cell(0, 15, utf8_decode('3'), 0, 0, 'L'); 
        $fpdf->SetX(0);
        $fpdf->SetFont('Arial', 'B', 35);
        $fpdf->Cell(0, 15, utf8_decode('COOPERATIVA DE SERVIDORES PÚBLICOS Y JUBILADOS DE COLOMBIA "COOPSERP COLOMBIA"'), 0, 0, 'C');
        $fpdf->Ln();    
        $fpdf->Cell(0, 15, utf8_decode("ELECCIÓN DE DELEGADOS PERÍODO 2024 - 2029"), 0, 0, 'C');
        $fpdf->Ln();
        $fpdf->Cell(0, 15, utf8_decode("Reporte parcial de votaciones EN ORDEN POR VOTOS"), 0, 0, 'C');
        $fpdf->Ln();
        $fpdf->Cell(0, 15, utf8_decode("Fecha y Hora de votaciones: 17 de noviembre de 2023 de 8:00am a 4:00pm"), 0, 0, 'C');
        $fpdf->Ln();
        $fpdf->Ln();
        $fpdf->Cell(0,15, utf8_decode($fecha_formateada), 0, 0, 'C');    


        $fpdf->Ln(); $fpdf->Ln();    
        $columnWidthText = 40;
        $columnWidth = 40;
        $columnWidthCandidatos = 100;
        $columnWidthNumber = 20; 
        

        $fpdf->SetX($posicionX);
        $columnWidthTarjeton = 20;
        $columnWidthName = 285;
        $columnWidthVotes = 120;
        $headerFontSize = 40;

        $headers = ['Posición','Tarjetón', 'Nombre', 'Agencia del Candidato', 'Votos'];


        foreach ($headers as $header) {
            if ($header === 'Posición') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'TB', 0, 'L'); 
            }elseif ($header === 'Tarjetón') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'TB', 0, 'L'); 
            } elseif ($header === 'Nombre') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthName, 30, $header, 'TB', 0, 'L'); 
            } elseif ($header === 'Agencia del Candidato') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthCandidatos, 30, $header, 'TB', 0, 'L'); 
            }elseif ($header === 'Votos') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthVotes, 30, $header, 'TB', 0, 'R'); 
            }
        }


    
        $fpdf->Ln(); 

        $sql = DB::select("SELECT * FROM delegados ORDER BY Posicion ASC");


        $fpdf->SetFont('Helvetica', '', 40);
        $sumaTotal = DB::table('delegados')->sum('Total');

        foreach ($sql as $row) {
            $fpdf->SetX($posicionX);
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
            $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos) ,0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
            $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->Total), 0, 0, 'R');
            

            $fpdf->Ln(); 
        
        }

    $sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
    foreach ($sql as $row) {
    $totalSum = intval($row->columna_13) +
                    intval($row->columna_30) +
                    intval($row->columna_31) +
                    intval($row->columna_32) +
                    intval($row->columna_33) +
                    intval($row->columna_34) +
                    intval($row->columna_35) +
                    intval($row->columna_36) +
                    intval($row->columna_37) +
                    intval($row->columna_38) +
                    intval($row->columna_39) +
                    intval($row->columna_40) +
                    intval($row->columna_41) +
                    intval($row->columna_42) +
                    intval($row->columna_43) +
                    intval($row->columna_44) +
                    intval($row->columna_45) +
                    intval($row->columna_46) +
                    intval($row->columna_47) +
                    intval($row->columna_48) +
                    intval($row->columna_68) +
                    intval($row->columna_70) +
                    intval($row->columna_73) +
                    intval($row->columna_74) +
                    intval($row->columna_76) +
                    intval($row->columna_77) +
                    intval($row->columna_78) +
                    intval($row->columna_80) +
                    intval($row->columna_81) +
                    intval($row->columna_82) +
                    intval($row->columna_83) +
                    intval($row->columna_84) +
                    intval($row->columna_85) +
                    intval($row->columna_86) +
                    intval($row->columna_87) +
                    intval($row->columna_88) +
                    intval($row->columna_89) +
                    intval($row->columna_90) +
                    intval($row->columna_91) +
                    intval($row->columna_92) +
                    intval($row->columna_93) +
                    intval($row->columna_94) +
                    intval($row->columna_95) +
                    intval($row->columna_96) +
                    intval($row->columna_97) +
                    intval($row->columna_98);

                $noTarjeton = $row->NoTarjeton; 
    
                DB::table('delegados')
            ->where('NoTarjeton', $noTarjeton)
            ->update(['Total' => $totalSum]);
                

                
            
        }

        $candidatos = DB::table('delegados')
        ->orderByDesc('Total') 
        ->orderBy('Fecharegistro') 
        ->orderBy('Horaregistro') 
        ->get();
    
        $posicion = 1;
    foreach ($candidatos as $candidato) {
    DB::table('delegados')
        ->where('ID', $candidato->ID)
        ->update(['Posicion' => $posicion]);
    $posicion++;
    }
    


    $fpdf->SetX($posicionX);
    $columnWidthTotal = 680;
    $columnWidthTotal2 = 40;
    $fpdf->SetFont('Arial', 'B', 40);
    $fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
    $fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');




    $fpdf->Ln();         $fpdf->Ln(); 
    

    $fpdf->Output('I', 'impresion/Ticket-' . "asd" . '.pdf');
    $fpdf->Output('F', 'impresion/Ticket-' . "asd" . '.pdf');
    exit;
        
        
    }
    public function imprimir5(){
        $anchoHoja = 1000;
        $altoHoja = 700; 


    
        $fpdf = new Fpdf('P', 'mm', [$anchoHoja, $altoHoja]); // Personaliza el tamaño de la hoja
        $fpdf->AddPage("landscape");



        $posicionX = ($anchoHoja - 950) / 2;
        $fpdf->SetX($posicionX);
        Carbon::setLocale('es');
        $fecha_actual = Carbon::now('America/Bogota');
        $fecha_formateada = $fecha_actual->isoFormat('dddd, D [de] MMMM [de] YYYY , [Hora de Generación] - H:mm:ss');
        $fecha_formateada = preg_replace_callback('/\b(\p{L}+)\b/u', function ($matches) {
            return ucfirst($matches[0]);
        }, $fecha_formateada);
        $fpdf->SetFont('Arial', 'B', 90);
        $fpdf->SetX(60); 
        $fpdf->Cell(0, 15, utf8_decode('4'), 0, 0, 'L'); 
        $fpdf->SetX(0);
        $fpdf->SetFont('Arial', 'B', 35);
        $fpdf->Cell(0, 15, utf8_decode('COOPERATIVA DE SERVIDORES PÚBLICOS Y JUBILADOS DE COLOMBIA "COOPSERP COLOMBIA"'), 0, 0, 'C');
        $fpdf->Ln();    
        $fpdf->Cell(0, 15, utf8_decode("ELECCIÓN DE DELEGADOS PERÍODO 2024 - 2029"), 0, 0, 'C');
        $fpdf->Ln();
        $fpdf->Cell(0, 15, utf8_decode("Reporte parcial de votaciones EN ORDEN POR CURUL"), 0, 0, 'C');
        $fpdf->Ln();
        $fpdf->Cell(0, 15, utf8_decode("Fecha y Hora de votaciones: 17 de noviembre de 2023 de 8:00am a 4:00pm"), 0, 0, 'C');
        $fpdf->Ln();
        $fpdf->Ln();
        $fpdf->Cell(0,15, utf8_decode($fecha_formateada), 0, 0, 'C');    
        


        $fpdf->Ln(); $fpdf->Ln();    
        $columnWidthText = 40;
        $columnWidth = 40;
        $columnWidthCandidatos = 100;
        $columnWidthNumber = 20; 
        

        $fpdf->SetX($posicionX);
        $columnWidthTarjeton = 20;
        $columnWidthName = 285;
        $columnWidthVotes = 120;
        $headerFontSize = 40;

        $headers = ['Posición','Tarjetón', 'Nombre', 'Agencia del Candidato', 'Votos', 'Fecha Insc', 'Hora Insc'];


        foreach ($headers as $header) {
            if ($header === 'Posición') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'TB', 0, 'L'); 
            }elseif ($header === 'Tarjetón') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthCandidatos, 30, utf8_decode($header), 'TB', 0, 'L'); 
            } elseif ($header === 'Nombre') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthName, 30, $header, 'TB', 0, 'L'); 
            } elseif ($header === 'Agencia del Candidato') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthCandidatos, 30, $header, 'TB', 0, 'L'); 
            }elseif ($header === 'Votos') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthVotes, 30, $header, 'TB', 0, 'R'); 
            }elseif ($header === 'Fecha Insc') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthVotes, 30, $header, 'TB', 0, 'R'); 
            }elseif ($header === 'Hora Insc') {
                $fpdf->SetFont('Helvetica', 'B', $headerFontSize);
                $fpdf->Cell($columnWidthVotes, 30, $header, 'TB', 0, 'R'); 
            }
        }


    
        $fpdf->Ln(); 

        $sql = DB::select("SELECT * FROM delegados ORDER BY Posicion ASC");


        $fpdf->SetFont('Helvetica', '', 40);
        $sumaTotal = DB::table('delegados')->sum('Total');

        foreach ($sql as $row) {
            $fpdf->SetX($posicionX);

            if ($row->Posicion >= 0 && $row->Posicion <= 45) {
                $fpdf->SetFillColor(192, 192, 192);
            } else {
                $fpdf->SetFillColor(255, 255, 255);
            }
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->Posicion), 'TBRL', 0, 'L', true);
            $fpdf->Cell($columnWidthCandidatos, 20, utf8_decode($row->NoTarjeton), 0, 0, 'L');
            $fpdf->Cell($columnWidthName, 20, utf8_decode($row->Nombre ." ". $row->Apellidos) ,0, 0, 'L');
            $fpdf->Cell($columnWidthCandidatos, 20, strtoupper(utf8_decode($row->AgenciaD)), 0, 0, 'L');
            $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->Total), 0, 0, 'R');
            $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->Fecharegistro), 0, 0, 'R');
            $fpdf->Cell($columnWidthVotes, 20, utf8_decode($row->Horaregistro), 0, 0, 'R');
            
            $fpdf->Ln(); 
        
    }

    $sql = DB::select("SELECT * FROM delegados ORDER BY CAST(NoTarjeton AS SIGNED) ASC");
    foreach ($sql as $row) {
    $totalSum = intval($row->columna_13) +
                    intval($row->columna_30) +
                    intval($row->columna_31) +
                    intval($row->columna_32) +
                    intval($row->columna_33) +
                    intval($row->columna_34) +
                    intval($row->columna_35) +
                    intval($row->columna_36) +
                    intval($row->columna_37) +
                    intval($row->columna_38) +
                    intval($row->columna_39) +
                    intval($row->columna_40) +
                    intval($row->columna_41) +
                    intval($row->columna_42) +
                    intval($row->columna_43) +
                    intval($row->columna_44) +
                    intval($row->columna_45) +
                    intval($row->columna_46) +
                    intval($row->columna_47) +
                    intval($row->columna_48) +
                    intval($row->columna_68) +
                    intval($row->columna_70) +
                    intval($row->columna_73) +
                    intval($row->columna_74) +
                    intval($row->columna_76) +
                    intval($row->columna_77) +
                    intval($row->columna_78) +
                    intval($row->columna_80) +
                    intval($row->columna_81) +
                    intval($row->columna_82) +
                    intval($row->columna_83) +
                    intval($row->columna_84) +
                    intval($row->columna_85) +
                    intval($row->columna_86) +
                    intval($row->columna_87) +
                    intval($row->columna_88) +
                    intval($row->columna_89) +
                    intval($row->columna_90) +
                    intval($row->columna_91) +
                    intval($row->columna_92) +
                    intval($row->columna_93) +
                    intval($row->columna_94) +
                    intval($row->columna_95) +
                    intval($row->columna_96) +
                    intval($row->columna_97) +
                    intval($row->columna_98);

                $noTarjeton = $row->NoTarjeton; 
    
                DB::table('delegados')
            ->where('NoTarjeton', $noTarjeton)
            ->update(['Total' => $totalSum]);
                

                
            
        }

        $candidatos = DB::table('delegados')
        ->orderByDesc('Total') 
        ->orderBy('Fecharegistro') 
        ->orderBy('Horaregistro') 
        ->get();
    
        $posicion = 1;
    foreach ($candidatos as $candidato) {
    DB::table('delegados')
        ->where('ID', $candidato->ID)
        ->update(['Posicion' => $posicion]);
    $posicion++;
    }
    


    $fpdf->SetX($posicionX);
    $columnWidthTotal = 680;
    $columnWidthTotal2 = 40;
    $columnWidthTotal3 = 225;
    $fpdf->SetFont('Arial', 'B', 40);
    $fpdf->Cell($columnWidthTotal, 30, "Total votos:", 'T', 0, 'R');
    $fpdf->Cell($columnWidthTotal2, 30, $sumaTotal, 'T', 0, 'C');
    $fpdf->Cell($columnWidthTotal3, 30, '      ', 'T', 0, 'C');




    $fpdf->Ln();         $fpdf->Ln(); 
    

    $fpdf->Output('I', 'impresion/Ticket-' . "asd" . '.pdf');
    $fpdf->Output('F', 'impresion/Ticket-' . "asd" . '.pdf');
    exit;
        
        
    }
}
    



    

