<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Exists;

class EstudianteController extends Controller
{

    public function getIndex(){
        return view('estudiantes.index',['arrayEstudiantes' => Estudiante::all()]);
    }

    public function getShow($id)
    {
        return view('estudiantes.show')
            ->with('estudiante', Estudiante::findOrFail($id));
    }

    public function getEdit($id) {
        return view('estudiantes.edit')
            ->with("estudiante", Estudiante::findOrFail($id));
    }

    public function putEdit(Request $request, $id) {

        $estudiante = Estudiante::findOrFail($id);

        if ($request->hasFile('avatar')){

            $path = $request->file('avatar')->store('avatars', ['disk' => 'public']);

            if ($estudiante->avatar){

                $estudiante->avatar->delete();
                $estudiante->avatar = $path;
                $estudiante->save();
            }
        }

        $estudiante->update($request->all());

        return redirect(action([self::class, 'getShow'], ['id' => $id]));
    }

    public function getCreate(){
        return view('estudiantes.create');
    }

    public function store(Request $request){
        //dd($request->all());
        //$name = $request->input('name');

        $estudiante = Estudiante::create($request->all());

        return redirect(action([self::class, 'getShow'], ['id' => $estudiante->id]));


    }
}
