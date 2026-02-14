<?php

namespace App\Http\Controllers;

use App\Models\Rifa;
use App\Models\Boleto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RifaController extends Controller
{
    public function index()
    {
        $rifas = Rifa::paginate(15);
        return view('admin.rifas.index', compact('rifas'));
    }

    public function create()
    {
        return view('admin.rifas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|unique:rifas',
            'description' => 'nullable|string',
            'num_boletos' => 'required|integer|min:1',
            'num_adicionales' => 'required|integer|min:0',
            'amount' => 'required|numeric|min:0.01',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = '0';

        if ($request->hasFile('img')) {
            $validated['img'] = $request->file('img')->store('rifas', 'public');
        }

        $rifa = Rifa::create($validated);

        // Generar boletos
        $this->generarBoletos($rifa);

        return redirect()->route('admin.rifas.show', $rifa)
            ->with('success', 'Rifa creada exitosamente');
    }

    public function show(Rifa $rifa)
    {
        $boletos = $rifa->boletos()->paginate(50);
        $estadisticas = [
            'total' => $rifa->boletos()->count(),
            'disponibles' => $rifa->boletos()->disponible()->count(),
            'vendidos' => $rifa->boletos()->vendido()->count(),
            'ingresos' => $rifa->compras()->pagado()->sum('total'),
        ];

        return view('admin.rifas.show', compact('rifa', 'boletos', 'estadisticas'));
    }

    public function edit(Rifa $rifa)
    {
        return view('admin.rifas.edit', compact('rifa'));
    }

    public function update(Request $request, Rifa $rifa)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
            'status' => 'required|in:0,1',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('img')) {
            $validated['img'] = $request->file('img')->store('rifas', 'public');
        }

        $rifa->update($validated);

        return redirect()->route('admin.rifas.show', $rifa)
            ->with('success', 'Rifa actualizada exitosamente');
    }

    public function destroy(Rifa $rifa)
    {
        $rifa->delete();
        return redirect()->route('admin.rifas.index')
            ->with('success', 'Rifa eliminada exitosamente');
    }

    private function generarBoletos(Rifa $rifa)
    {
        $chunkSize = 1000;
        $total = $rifa->num_boletos;
        $now = now();
        for ($start = 1; $start <= $total; $start += $chunkSize) {
            $chunk = [];
            $end = min($start + $chunkSize - 1, $total);
            for ($i = $start; $i <= $end; $i++) {
                $chunk[] = [
                    'numero' => $i,
                    'adicional' => 0,
                    'subrifa_id' => null,
                    'status' => 0,
                    'rifa_id' => $rifa->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('boletos')->insert($chunk);
        }
    }
}
