<?php

namespace App\Models\V2\Transaction;

use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use Laminas\Json\Json;

class Metadata extends AbstractModel
{
    protected $table = 'transaction_meta';

    protected $fillable = [
        'transaction_id',
        'key',
        'value',
    ];

    /** @inheritDoc */
    public static function boot(): void
    {
        parent::boot();

        static::creating(static function (Metadata $metadata) {
            $metadata->uuid = Str::orderedUuid()->toString();

            if (is_iterable($metadata->value)) {
                $metadata->value = Json::encode($metadata->value);
            }
        });
    }

    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Str::isJson($value) ? Json::decode($value) : $value,
        );
    }
}
