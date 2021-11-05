<?php

declare(strict_types=1);

namespace OpiyOrg\Laravelsanitized\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use OpiyOrg\LaravelSanitized\Sanitized;

/**
 * DirtyArticle
 *
 * @property int $id
 * @property string $title
 * @property string $body
 * @property string $description
 * @method static DirtyArticle create($attributes = [])
 * @method static DirtyArticle update($attributes = [])
 */
class DirtyArticle extends Model
{
    use Sanitized;

    public $table = 'dirty_articles';

    public $fillable = [
        'title',
        'body',
        'description',
    ];
}
