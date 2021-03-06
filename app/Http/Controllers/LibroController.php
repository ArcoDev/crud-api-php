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

   public function guardar(Request $request) {
    //Instanciar el libro para mandar la info
    $datosLibro = new Libro;

    //enviar un file a la BD
    $request->file('imagen');
    //adjuntar el file en una carpeta de
    //validar que exista un archivo
    if($request->hasFile('imagen')){
        //Concatenarle un nuevo nombrea a la imagen para evitar que cada que se mande se sobreescriba
        $nombreArchivoOriginal = $request->file('imagen')->getClientOriginalName();
        $nuevoNombre = Carbon::now()->timestamp."_".$nombreArchivoOriginal;
        
        //Creacion de la carpeta donde se guardara la imagen
        $carpetaDestino = './upload/';
        
        //guardar la img en la carpeta con el nuevo nombre de la imagen
        $request->file('imagen')->move($carpetaDestino, $nuevoNombre);
        
        //nombre de la columna de la tabla = nombre de lo que nos arroga el json de postman
        $datosLibro->titulo = $request->titulo;
        $datosLibro->imagen = ltrim($carpetaDestino,'.').$nuevoNombre;
        
        //Guardar la informacion en la bd
        $datosLibro->save();
    }
    
    //mostrar la informacion en postman
    return response()->json("Se agrego correctamente");
    return response()->json($nuevoNombre);
   
   }
   //Ver informacion de la BD
   public function ver($id) {
       $datosLibro = new Libro;
       
       //Buscar todos los libros de la BD mediante el id de las
       $datosEncontrados = $datosLibro->find($id);
       return response()->json($datosEncontrados);
   } 
 
   //Eliminar reigstro de la BD
   public function eliminar($id) {

    $datosLibro = new Libro;
    //Validar que exista un archivo
    if($datosLibro) {
        //ubicar el archivo y su ruta
        $rutaArchivo = base_path('public').$datosLibro->imagen;
        //validar que exista lo anterior y si es asi eliminarlo
        if($rutaArchivo) {
            unlink($rutaArchivo);
        }
        $datosLibro->delete();
    }
      return response()->json("Registro Borrado");
   }

   //Actualizar el registro con todo y la imagen
   public function actualizar(Request $request, $id) {

     //buscar la informacion del libro
     $datosLibro = Libro::find($id);

     //validar que exista un archivo
    if($request->hasFile('imagen')){
        if($datosLibro) {
            //ubicar el archivo y su ruta
            $rutaArchivo = base_path('public').$datosLibro->imagen;
            //validar que exista lo anterior y si es asi eliminarlo
            if($rutaArchivo) {
                unlink($rutaArchivo);
            }
            $datosLibro->delete();
        }
        //Concatenarle un nuevo nombre a la imagen para evitar que cada que se mande se sobreescriba
        $nombreArchivoOriginal = $request->file('imagen')->getClientOriginalName();
        $nuevoNombre = Carbon::now()->timestamp."_".$nombreArchivoOriginal;
        $carpetaDestino = './upload/';
        $request->file('imagen')->move($carpetaDestino, $nuevoNombre);
        $datosLibro->imagen = ltrim($carpetaDestino,'.').$nuevoNombre;
        $datosLibro->save();
    }
     if($request->input('titulo')) {
         $datosLibro->titulo = $request->input('titulo');
     }
     $datosLibro->save();
     return response()->json("Datos actualizados");
   }
}
