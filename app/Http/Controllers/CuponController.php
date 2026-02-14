<?php

namespace App\Http\Controllers;

use App\Models\Cupon;
use Illuminate\Http\Request;

class CuponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cupones = Cupon::paginate(15);
        return view('admin.cupones.index', compact('cupones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cupones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|unique:cupons',
            'descuento' => 'required|numeric|min:0.01',
            'minimo_compra' => 'nullable|numeric|min:0',
            'vigente_desde' => 'nullable|date',
            'vigente_hasta' => 'nullable|date|after:vigente_desde',
            'uso_maximo' => 'nullable|integer|min:1',
            'estado' => 'required|in:activo,inactivo',
        ]);

        Cupon::create($validated);

        return redirect()->route('admin.cupones.index')->with('success', 'Cupón creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cupon $cupon)
    {
        return view('admin.cupones.show', compact('cupon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cupon $cupon)
    {
        return view('admin.cupones.edit', compact('cupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cupon $cupon)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|unique:cupons,codigo,' . $cupon->id,
            'descuento' => 'required|numeric|min:0.01',
            'minimo_compra' => 'nullable|numeric|min:0',
            'vigente_desde' => 'nullable|date',
            'vigente_hasta' => 'nullable|date|after:vigente_desde',
            'uso_maximo' => 'nullable|integer|min:1',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $cupon->update($validated);

        return redirect()->route('admin.cupones.index')->with('success', 'Cupón actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cupon $cupon)
    {
        $cupon->delete();

        return redirect()->route('admin.cupones.index')->with('success', 'Cupón eliminado exitosamente');
    }
}
