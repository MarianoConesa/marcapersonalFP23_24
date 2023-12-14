<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class CatalogController extends Controller
{
    public function getIndex()
    {
        $proyecto = Proyecto::all();
        return view('catalog.index', ['arrayProyectos' => $proyecto]);
    }

    public function getShow($id)
    {
        $proyecto = Proyecto::FindOrFail($id);
        //$proyecto->metadatos = unserialize($proyecto->metadatos);
        return view('catalog.show')
            ->with('proyecto', $proyecto)
            ->with('id', $proyecto->id);
    }

    public function putEdit(Request $request, $id) {
        $proyecto = Proyecto::findOrFail($id);
        // TODO: Eliminar el avatar anterior si existiera

        if ($proyecto->archivoProyecto && $proyecto->archivoProyecto != $request->file('archivoProyecto')){

            $proyecto->archivoProyecto->delete();
            $path = $request->file('archivoProyecto')->store('compressed_files', ['disk' => 'public']);
            $proyecto->archivoProyecto->update($path);
        }

        //$metadatosVar = serialize($request->only('metadatos'));
        //$proyecto->metadatos->update($metadatosVar);

        $proyecto->update($request->except('archivoProyecto'));
        return redirect(action([self::class, 'getShow'], ['id' => $proyecto->id]));
    }

    public function getEdit($id)
    {
        $proyecto = Proyecto::FindOrFail($id);
        //$proyecto->metadatos = unserialize($proyecto->metadatos);
        return view('catalog.edit')
            ->with('proyecto', $proyecto)
            ->with('id', $proyecto->id);
    }

    public function getCreate()
    {
        return view('catalog.create');
    }

    public function store(Request $request) {
        $proyecto = Proyecto::create($request->all());

        return redirect(action([self::class, 'getShow'], ['id' => $proyecto->id]));
    }
}
