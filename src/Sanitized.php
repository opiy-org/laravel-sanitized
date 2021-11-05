<?php

declare(strict_types=1);

namespace OpiyOrg\LaravelSanitized;

use Illuminate\Database\Eloquent\Model;
use Stevebauman\Purify\Purify;

/**
 * Sanitized trait
 */
trait Sanitized
{
    /**
     * handle saving model event, sanitize fields pointed in fieldsToSanitize
     */
    public static function bootSanitized(): void
    {
        static::saving(function (Model $model) {
            foreach ($model->getDirty() as $key => $value) {
                if (!isset($model->fieldsToSanitize) || !in_array($key, $model->fieldsToSanitize)) {
                    continue;
                }

                $purifier = new Purify(config('purify.config', []));
                $model->$key = $purifier->clean($value);
            }
        });
    }
}
