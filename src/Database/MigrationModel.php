<?php

declare(strict_types=1);

namespace Effectra\Core\Database;

class MigrationModel extends Model
{
    use ModelBase;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected static $table = 'migrations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected static $fillable = [
        'migration'
    ];
}
