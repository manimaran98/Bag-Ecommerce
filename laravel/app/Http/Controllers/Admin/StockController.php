<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockInventory;
use App\Services\AdminAssetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class StockController extends Controller
{
    public function __construct(
        private readonly AdminAssetService $assets
    ) {}

    public function index(): View
    {
        $stocks = StockInventory::query()->orderBy('stock_id')->paginate(25);

        return view('admin.stock.index', compact('stocks'));
    }

    public function create(): View
    {
        return view('admin.stock.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'itemName' => ['required', 'string', 'max:100'],
            'itemBrand' => ['required', 'string', 'max:100'],
            'itemCategory' => ['required', 'string', 'max:100'],
            'itemPrice' => ['required', 'integer', 'min:0'],
            'itemQuantity' => ['required', 'integer', 'min:0'],
            'itemDescription' => ['required', 'string', 'max:10000'],
            'itemImg' => ['required', 'file', 'image', 'max:20480'],
        ]);

        $duplicate = StockInventory::query()
            ->where('stock_name', $data['itemName'])
            ->where('stock_brand', $data['itemBrand'])
            ->exists();
        if ($duplicate) {
            return back()->withInput()->withErrors(['itemName' => 'An item with this name and brand already exists.']);
        }

        $stored = $this->assets->storeStockImage($request->file('itemImg'));
        if ($stored === null) {
            return back()->withInput()->withErrors(['itemImg' => 'Invalid image (use JPG, PNG, GIF, or WebP).']);
        }

        StockInventory::query()->create([
            'stock_name' => $data['itemName'],
            'stock_brand' => $data['itemBrand'],
            'stock_category' => $data['itemCategory'],
            'stock_quantity' => $data['itemQuantity'],
            'stock_description' => $data['itemDescription'],
            'stock_img' => $stored,
            'stock_price' => $data['itemPrice'],
        ]);

        return redirect()->route('admin.stock.index')->with('status', 'Item saved.');
    }

    public function show(StockInventory $stock): View
    {
        return view('admin.stock.show', ['stock' => $stock]);
    }

    public function edit(StockInventory $stock): View
    {
        return view('admin.stock.edit', ['stock' => $stock]);
    }

    public function update(Request $request, StockInventory $stock): RedirectResponse
    {
        $data = $request->validate([
            'stock_name' => ['required', 'string', 'max:100'],
            'stock_brand' => ['required', 'string', 'max:100'],
            'stock_category' => ['required', 'string', 'max:100'],
            'stock_price' => ['required', 'integer', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'stock_description' => ['required', 'string', 'max:10000'],
            'stock_img' => ['nullable', 'file', 'image', 'max:20480'],
        ]);

        $stock->stock_name = $data['stock_name'];
        $stock->stock_brand = $data['stock_brand'];
        $stock->stock_category = $data['stock_category'];
        $stock->stock_price = $data['stock_price'];
        $stock->stock_quantity = $data['stock_quantity'];
        $stock->stock_description = $data['stock_description'];

        if ($request->hasFile('stock_img')) {
            $new = $this->assets->storeStockImage($request->file('stock_img'));
            if ($new === null) {
                return back()->withInput()->withErrors(['stock_img' => 'Invalid image.']);
            }
            $this->assets->deleteStockImageFile($stock->stock_img);
            $stock->stock_img = $new;
        }

        $stock->save();

        return redirect()->route('admin.stock.index')->with('status', 'Stock updated.');
    }

    public function destroy(StockInventory $stock): RedirectResponse
    {
        $this->assets->deleteStockImageFile($stock->stock_img);
        $stock->delete();

        return redirect()->route('admin.stock.index')->with('status', 'Stock deleted.');
    }
}
