<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $primaryKey = 'suppliers_id';

    public $timestamps = false;

    public function getRouteKeyName(): string
    {
        return 'suppliers_id';
    }

    /** @var list<string> */
    protected $fillable = [
        'suppliers_name',
        'stock_brand',
    ];
}
