<?php

namespace App\Services;

use App\Models\Delivery;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockInventory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

final class PurchaseAdminService
{
    public function __construct(
        private readonly AdminAssetService $assets
    ) {}

    /**
     * @param  array{id: int, total_price: string, purchase_date: string, purchase_validation: string}  $data
     */
    public function updatePurchase(Purchase $purchase, array $data, ?UploadedFile $receipt): void
    {
        DB::transaction(function () use ($purchase, $data, $receipt): void {
            $purchase->refresh();
            $previous = (string) $purchase->purchase_validation;
            $next = $data['purchase_validation'];

            $newReceipt = null;
            if ($receipt !== null && $receipt->isValid()) {
                $newReceipt = $this->assets->storeReceipt($receipt);
            }

            if ($newReceipt !== null) {
                $this->assets->deleteReceiptFile($purchase->payment_resit);
                $purchase->payment_resit = $newReceipt;
            }

            $purchase->id = $data['id'];
            $purchase->total_price = $data['total_price'];
            $purchase->purchase_date = $data['purchase_date'];
            $purchase->purchase_validation = $next;
            $purchase->save();

            if ($next === 'Approved') {
                Delivery::query()->where('purchase_id', $purchase->purchase_id)->update([
                    'payment_status' => 'Approved',
                ]);
            }

            if ($next === 'Declined' && $previous !== 'Declined') {
                $this->applyDeclined($purchase->purchase_id);
            }
        });
    }

    private function applyDeclined(string $purchaseId): void
    {
        Delivery::query()->where('purchase_id', $purchaseId)->update([
            'payment_status' => 'Declined',
            'delivery_agent' => 'Canceled',
            'delivery_status' => 'Canceled',
        ]);

        $items = PurchaseItem::query()->where('purchase_id', $purchaseId)->get();
        foreach ($items as $row) {
            $stock = StockInventory::query()->where('stock_id', $row->stock_id)->lockForUpdate()->first();
            if ($stock) {
                $stock->stock_quantity = (int) $stock->stock_quantity + (int) $row->stock_quantity;
                $stock->save();
            }
        }

        PurchaseItem::query()->where('purchase_id', $purchaseId)->delete();
    }

    public function deletePurchase(Purchase $purchase): void
    {
        DB::transaction(function () use ($purchase): void {
            $pid = $purchase->purchase_id;
            PurchaseItem::query()->where('purchase_id', $pid)->delete();
            Delivery::query()->where('purchase_id', $pid)->delete();
            $this->assets->deleteReceiptFile($purchase->payment_resit);
            $purchase->delete();
        });
    }
}
