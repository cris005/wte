<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property Currency $currency This country's currency
 */
class Country extends Model
{
    protected $connection = 'app';
    protected $table = 'global_country';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * Fetch a specific country record by primary key
     *
     * @param int $id
     * @return Model
     */
    public static function fetch(int $id): Model
    {
        return self::query()->whereKey($id)->firstOrFail();
    }

    /**
     * Fetch all the country records
     *
     * @return Collection
     */
    public static function fetchAll(): Collection
    {
        return self::query()->get();
    }

    /**
     * Create a relationship between this country and its currency
     *
     * @return HasOne
     */
    public function currency(): HasOne
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }
}
