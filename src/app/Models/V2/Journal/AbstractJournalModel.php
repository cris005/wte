<?php

namespace App\Models\V2\Journal;

use App\Models\AbstractModel;

abstract class AbstractJournalModel extends AbstractModel
{
    protected $connection = 'wallet';
}
