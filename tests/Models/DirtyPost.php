<?php

declare(strict_types=1);

namespace OpiyOrg\Laravelsanitized\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use OpiyOrg\LaravelSanitized\Sanitized;

/**
 * DirtyPost
 *
 * @property int $id
 * @property string $title
 * @property string $body
 * @property string $description
 * @method static DirtyPost create($attributes = [])
 * @method static DirtyPost update($attributes = [])
 */
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
    ];
}
