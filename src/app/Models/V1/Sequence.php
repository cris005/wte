<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @property int $ID          The current sequence value
 * @property string $SEQ_NAME The sequence type key
 */
class Sequence extends Model
{
    public const SEQ_NAME_REF_NUM = 'txn_refnum';

    public $timestamps = false;

    protected $connection = 'app';
    protected $table = 'WSEQUENCES';
    protected $primaryKey = 'SEQ_NAME';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['ID'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = ['ID' => 'integer'];

    /**
     * Generate a new reference number
     *
     * @return int
     * @throws ModelNotFoundException
     */
    public static function generate(): int
    {
        $sequence = self::query()->findOrFail(self::SEQ_NAME_REF_NUM);
        ++$sequence->ID;
        $sequence->save();
        return $sequence->ID;
    }
}
