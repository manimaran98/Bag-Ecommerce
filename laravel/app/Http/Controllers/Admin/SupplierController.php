<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class SupplierController extends Controller
{
    public function index(): View
    {
        $suppliers = Supplier::query()->orderBy('suppliers_id')->get();

        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create(): View
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'supplierName' => ['required', 'string', 'max:100'],
            'supplierBrand' => ['required', 'string', 'max:100'],
        ]);

        $duplicate = Supplier::query()
            ->where('suppliers_name', $data['supplierName'])
            ->where('stock_brand', $data['supplierBrand'])
            ->exists();
        if ($duplicate) {
            return back()->withInput()->withErrors(['supplierName' => 'This supplier and brand combination already exists.']);
        }

        Supplier::query()->create([
            'suppliers_name' => $data['supplierName'],
            'stock_brand' => $data['supplierBrand'],
        ]);

        return redirect()->route('admin.suppliers.index')->with('status', 'Supplier added.');
    }

    public function edit(Supplier $supplier): View
    {
        return view('admin.suppliers.edit', ['supplier' => $supplier]);
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $data = $request->validate([
            'suppliers_name' => ['required', 'string', 'max:100'],
            'stock_brand' => ['required', 'string', 'max:100'],
        ]);

        $supplier->suppliers_name = $data['suppliers_name'];
        $supplier->stock_brand = $data['stock_brand'];
        $supplier->save();

        return redirect()->route('admin.suppliers.index')->with('status', 'Supplier updated.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')->with('status', 'Supplier deleted.');
    }
}
