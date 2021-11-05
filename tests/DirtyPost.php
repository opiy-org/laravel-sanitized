<?php

declare(strict_types=1);

namespace OpiyOrg\Laravelsanitized\Tests;

use Illuminate\Database\Eloquent\Model;
use OpiyOrg\LaravelSanitized\Sanitized;

class DirtyPost extends Model
{
    use Sanitized;

    public $table = 'dirty_posts';

    public $fillable = [
        'title',
        'body',
        'description',
    ];

    protected array $fieldsToSanitize = [
        'body',
        'description',
    ];
}
