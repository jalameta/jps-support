<?php

namespace Jalameta\Support\Database\Eloquent;

use Illuminate\Support\Str;
use Keiko\Uuid\Shortener\Dictionary;
use Keiko\Uuid\Shortener\Number\BigInt\Converter;
use Keiko\Uuid\Shortener\Shortener;

/**
 * UUID as primary key in eloquent model.
 *
 * @author      veelasky <veelasky@gmail.com>
 */
trait UuidAsPrimaryKey
{
    /**
     * Generate UUID as primary key upon creating new record on eloquent model.
     *
     * @return void
     */
    public static function bootUuidAsPrimaryKey()
    {
        self::creating(function ($model) {
            /*
             * @var $this
             */
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = $model->generateUuid();
            }
        });
    }

    /**
     * Generate Uuid.
     *
     * @return string
     * @throws \Exception
     */
    public function generateUuid()
    {
        $uuid = Str::orderedUuid();

        return ($this->isUsingShortUuid())
            ? $this->uuidShortener()->reduce($uuid)
            : $uuid;
    }

    /**
     * Get the actual RFC 4422 UUID spec value.
     *
     * @return string
     */
    public function realUuid()
    {
        return ($this->isUsingShortUuid())
            ? $this->uuidShortener()->expand($this->{$this->getKeyName()})
            : $this->{$this->getKeyName()};
    }

    /**
     * Get the UUID shortener instance.
     *
     * @return \Keiko\Uuid\Shortener\Shortener
     */
    protected function uuidShortener()
    {
        return new Shortener(Dictionary::createUnmistakable(), new Converter());
    }

    /**
     * Determine if it wants a shorter version of Uuid.
     *
     * @return bool
     */
    protected function isUsingShortUuid()
    {
        return (property_exists($this, 'shortUuid'))
            ? filter_var($this->shortUuid, FILTER_VALIDATE_BOOLEAN)
            : false;
    }
}
