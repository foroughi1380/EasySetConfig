<?php

namespace Gopex\EasySetConfig\database\casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class PrivateFileCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            $this->deleteOldFile($model , $key);
            return $value->store('private', 'local');
        }

        return $value;
    }

    protected function deleteOldFile($model, $key)
    {
        $oldFilePath = $model->getOriginal($key);

        if ($oldFilePath && Storage::disk('local')->exists($oldFilePath)) {
            Storage::disk('local')->delete($oldFilePath);
        }
    }
}
