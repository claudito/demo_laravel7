<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asistencia;
use Carbon\Carbon;
use DB;

class AsistenciaController extends Controller
{
    function index(Request $request){
        if( $request->ajax() ){
            $result = Asistencia::get();
            return ['data'=>$result];
        }
        return view('asistencia.index');
    }

    function store(Request $request){
         //dd( $request->all() );
        Asistencia::updateOrCreate([
            'id'=>$request->id
        ],
        [
            'nombres'=>$request->nombres,
            'fecha_asistencia'=>$request->fecha_asistencia
        ]);

        return [
            'title'=>'Buen Trabajo',
            'text' =>(is_null($request->id)) ? 'Registro Agregado' : 'Registrado Actualizado',
            'icon' =>'success'
        ];
    }

    function edit($id){
        $result = Asistencia::where('id',$id)->first();
        return $result;
    }

    function destroy($id){
        Asistencia::where('id',$id)->delete();
        return [
            'title'=>'Buen Trabajo',
            'text' =>'Registro Eliminado',
            'icon' =>'success'
        ];
    }
}
