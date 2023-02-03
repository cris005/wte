<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property Country $country The country to which this currency belongs to
 * @property string $currency_name The currency's full name
 * @property int $decimals The number of decimals used for this currency
 * @property int $id Currency primary key ID
 * @property string $iso_code ISO 4217 standard code
 * @property int $iso_number ISO 4217 standard number
 */
class Currency extends Model
{
    protected $connection = 'app';
    protected $table = 'global_currency';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * Fetch a record by primary key
     *
     * @param int $id
     * @return Model|static
     */
    public static function fetchById(int $id): static|Model
    {
        return self::query()->whereKey($id)->firstOrFail();
    }

    /**
     * Fetch a record by ISO 4217 standard code
     *
     * @param string $code
     * @return Model|static
     */
    public static function fetchByISOCode(string $code): static|Model
    {
        return self::query()->where(['iso_code' => $code])->firstOrFail();
    }

    /**
     * Fetch a record by ISO 4217 standard number
     *
     * @param int $number
     * @return Model|static
     */
    public static function fetchByISONumber(int $number): static|Model
    {
        return self::query()->where(['iso_number' => $number])->firstOrFail();
    }

    /**
     * Create a relationship between this currency and the country where it is used
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'id', 'currency_id');
    }

    /**
     * Create a relationship between this currency and the countries where it is used
     *
     * @return BelongsToMany
     */
    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'id', 'currency_id');
    }
}
