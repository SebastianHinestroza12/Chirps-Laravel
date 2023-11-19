<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   //Evitando Problemas de rendimientos en las consulta Precargandola y asi evtar el, N + 1 por cada chirps

        /* La declaración `return view('chirps.index', ['chirps'=>
        Chirp::with('user')->latest()->get()]);` devuelve una vista llamada 'chirps.index ' y pasar
        una serie de datos a esa vista. Los datos que se pasan son una matriz de objetos "Chirp" con
        sus modelos de "usuario" asociados cargados, ordenados por el último chirrido. Se puede
        acceder a estos datos en la vista utilizando el nombre de variable `chirps`. */
        return view('chirps.index', ['chirps'=> Chirp::with('user')->latest()->get()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'min:3', 'max:255']
        ]);

        // auth()->user()->chirps()->create($validate);

        Chirp::create([
            'message'=> $request->message,
            'user_id' => auth()->id()
        ]);

        // session()->flash('status', 'Chirp created successfully');

        // return redirect('chirps');
        return to_route('chirps.index')->with('status', __('Chirp created successfully!!'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {
         $this->authorize('update', $chirp);
        // return $chirp;
        return view('chirps.edit', ['chirp'=> $chirp]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp)
    {
         $this->authorize('update', $chirp);

        $validated = $request->validate([
            'message' => ['required', 'min:3', 'max:255'],
        ]);

        $chirp->update($validated);

        return to_route('chirps.index')
            ->with('status', __('Chirp updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp)
    {
        $this->authorize('delete', $chirp);

        $chirp -> delete(); 

        return to_route('chirps.index')->with('status', 'Chirp Eliminado Correctamente');
    }
}
