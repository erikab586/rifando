<?php

namespace App\Http\Controllers;

use App\Models\PaymentCredential;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentCredentialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $credentials = PaymentCredential::with('paymentMethod')->paginate(15);
        return view('admin.payment-credentials.index', compact('credentials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $paymentMethods = PaymentMethod::all();
        return view('admin.payment-credentials.create', compact('paymentMethods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'nombre' => 'required|string|max:100',
            'clave' => 'required|string|max:500',
            'secreto' => 'nullable|string|max:500',
            'activo' => 'boolean',
            'banco' => 'nullable|string|max:100',
            'cuenta' => 'nullable|string|max:50',
            'cedula' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
        ]);

        PaymentCredential::create($validated);

        return redirect()->route('admin.payment-credentials.index')
            ->with('success', 'Credencial de pago creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentCredential $paymentCredential)
    {
        return view('admin.payment-credentials.show', compact('paymentCredential'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentCredential $paymentCredential)
    {
        $paymentMethods = PaymentMethod::all();
        return view('admin.payment-credentials.edit', compact('paymentCredential', 'paymentMethods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentCredential $paymentCredential)
    {
        $validated = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'nombre' => 'required|string|max:100',
            'clave' => 'required|string|max:500',
            'secreto' => 'nullable|string|max:500',
            'activo' => 'boolean',
            'banco' => 'nullable|string|max:100',
            'cuenta' => 'nullable|string|max:50',
            'cedula' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
        ]);

        $paymentCredential->update($validated);

        return redirect()->route('admin.payment-credentials.index')
            ->with('success', 'Credencial de pago actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentCredential $paymentCredential)
    {
        $paymentCredential->delete();

        return redirect()->route('admin.payment-credentials.index')
            ->with('success', 'Credencial de pago eliminada exitosamente');
    }
}
