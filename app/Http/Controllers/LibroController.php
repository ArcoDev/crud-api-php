<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;

use Carbon\Carbon;

class LibroController extends Controller{
   //eloquent herramienta para interectuar con la base de datos
   public function index() {
       //acceder al modelo Libro y consutlar su informacion y devolverlo en un json
       $datosLibro = Libro::all();
       return response()->json($datosLibro);
   }

   public function guardar(Request $request){
    //Instanciar el libro para mandar la info
    $datosLibro = new Libro;

    //enviar un file a la BD
    $request->file('imagen');
    //adjuntar el file en una carpeta de
    //validar que exista un archivo
    if($request->hasFile('imagen')){
        $nombreArchivoOriginal = $request->file('imagen')->getClientOriginalName();
        $nuevoNombre = Carbon::now()->timestamp."_".$nombreArchivoOriginal;
        //Creacion de la carpeta donde se guardara la imagen
        $carpetaDestino = './upload/';
        //guardar la img en la carpeta con el nuevo nombre de la imagen
        $request->file('imagen')->move($carpetaDestino, $nuevoNombre);
    }
    //nombre de la columna de la tabla = nombre de lo que nos arroga el json de postman
    $datosLibro->titulo = $request->titulo;
    $datosLibro->imagen = $request->imagen;
    
    //Guardar la informacion en la bd
    $datosLibro->save();
    
    //mostrar la informacion en postman
    return response()->json($nuevoNombre);
   
   }
}