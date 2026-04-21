<?php

namespace App\Services;

use App\Models\StockInventory;
use App\Models\User;
use App\Models\UserSearchLog;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Personalized product suggestions using purchase co-occurrence ("basket" association),
 * search/category interest from logged filters, and a popularity fallback.
 */
final class ProductRecommendationService
{
    /**
     * @return Collection<int, StockInventory>
     */
    public function recommendForUser(User $user, int $limit = 8): Collection
    {
        if ($limit < 1) {
            return collect();
        }

        $cacheKey = "recommendations:user:{$user->id}";

        return Cache::remember($cacheKey, now()->addHour(), function () use ($user, $limit) {
            return $this->compute($user, $limit);
        });
    }

    private function compute(User $user, int $limit): Collection
    {
        $purchasedIds = DB::table('purchase_item')
            ->where('id', $user->id)
            ->distinct()
            ->pluck('stock_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $scores = [];

        if ($purchasedIds !== []) {
            $rows = DB::table('purchase_item as pi1')
                ->join('purchase_item as pi2', function ($join): void {
                    $join->on('pi1.purchase_id', '=', 'pi2.purchase_id')
                        ->whereColumn('pi2.stock_id', '!=', 'pi1.stock_id');
                })
                ->whereIn('pi1.stock_id', $purchasedIds)
                ->groupBy('pi2.stock_id')
                ->select('pi2.stock_id', DB::raw('count(*) as weight'))
                ->get();

            foreach ($rows as $row) {
                $sid = (int) $row->stock_id;
                if (! in_array($sid, $purchasedIds, true)) {
                    $scores[$sid] = ($scores[$sid] ?? 0) + (int) $row->weight;
                }
            }
        }

        $this->applySearchCategoryBoost($user, $purchasedIds, $scores);

        arsort($scores);
        $orderedIds = array_keys($scores);

        if (count($orderedIds) < $limit) {
            foreach ($this->popularStockIds($limit * 3, $purchasedIds) as $sid) {
                if (in_array($sid, $orderedIds, true)) {
                    continue;
                }
                $orderedIds[] = $sid;
                if (count($orderedIds) >= $limit) {
                    break;
                }
            }
        }

        $orderedIds = array_slice($orderedIds, 0, $limit);

        if ($orderedIds === []) {
            return collect();
        }

        $products = StockInventory::query()
            ->whereIn('stock_id', $orderedIds)
            ->where('stock_quantity', '>', 0)
            ->get()
            ->sortBy(fn (StockInventory $p) => array_search($p->stock_id, $orderedIds, true))
            ->values();

        return $products;
    }

    /**
     * @param  array<int>  $purchasedIds
     * @param  array<int, float|int>  $scores
     */
    private function applySearchCategoryBoost(User $user, array $purchasedIds, array &$scores): void
    {
        $categories = UserSearchLog::query()
            ->where('user_id', $user->id)
            ->whereNotNull('category_filter')
            ->where('category_filter', '!=', '')
            ->orderByDesc('created_at')
            ->limit(15)
            ->pluck('category_filter')
            ->unique()
            ->filter();

        foreach ($categories as $cat) {
            $ids = StockInventory::query()
                ->where('stock_category', $cat)
                ->where('stock_quantity', '>', 0)
                ->pluck('stock_id');

            foreach ($ids as $sid) {
                $sid = (int) $sid;
                if (in_array($sid, $purchasedIds, true)) {
                    continue;
                }
                $scores[$sid] = ($scores[$sid] ?? 0) + 1.5;
            }
        }
    }

    /**
     * @param  array<int>  $excludeStockIds
     * @return list<int>
     */
    private function popularStockIds(int $cap, array $excludeStockIds): array
    {
        $rows = DB::table('purchase_item')
            ->select('stock_id', DB::raw('sum(stock_quantity) as units'))
            ->groupBy('stock_id')
            ->orderByDesc('units')
            ->limit($cap)
            ->get();

        $out = [];
        foreach ($rows as $row) {
            $sid = (int) $row->stock_id;
            if (! in_array($sid, $excludeStockIds, true)) {
                $out[] = $sid;
            }
        }

        if ($out !== []) {
            return $out;
        }

        return StockInventory::query()
            ->where('stock_quantity', '>', 0)
            ->orderByDesc('stock_id')
            ->limit($cap)
            ->pluck('stock_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }
}
