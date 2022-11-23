<?php

namespace roaresearch\yii2\roa;

/**
 * @author Angel (Faryshta) Guevara
 */
class ArrayHelper extends \yii\helpers\ArrayHelper
{
    /**
     * Normalizes an array to follow a 'field' => 'definition'
     * pairing.
     *
     * [
     *    'a',
     *    'b',
     *    'c' = 'full_c'
     *    'd' => fn () => $this->a + $this->b,
     * ]
     *
     * turns into
     *
     * [
     *    'a' => 'a',
     *    'b' => 'b',
     *    'c' = 'full_c'
     *    'd' => fn () => $this->a + $this->b,
     * ]
     *
     * @param array $fields
     * @return array
     */
    public static function normalize(array $fields): array
    {
        $result = [];

        foreach ($fields as $field => $definition) {
            if (is_int($field)) {
                $field = $definition;
            }

            $result[$field] = $definition;
        }

        return $result;
    }

    /**
     * @param array $fields fields to normalize
     * @param array $filters list of valid fields. It accept the wildcard '*'
     *   and will filter out any field denoted here with an '!' token. Example:
     *   '!name' will not allow the field 'name' to be included. If the list of
     *   valid fields is empty then all fields will be included except the
     *   ones not allowed.
     * @param ?Callable $callback can be used to process the field definition
     *   with signature `function (string $field, mixed $definition): mixed`
     *   where the return is the value to be associated to $field.
     * @return array filtered and normalized fields.
     * @see normalize
     */
    public static function normalizeFilter(
        array $fields,
        array $filters,
        ?Callable $callback = null
    ): array {
        $filterIn = $filterOut = [];
        foreach ($filters as $filter) {
            if (str_starts_with($filter, '!')) {
                $filterOut[] = substr($filter, 1);
            } else {
                $filterIn[] = $filter;
            }
        }

        $filterIn = static::rootFields($filterIn);
        $result = [];

        foreach ($fields as $field => $definition) {
            if (is_int($field)) {
                $field = $definition;
            }

            if (
                (empty($filterIn) || in_array($field, $filterIn))
                && !in_array($field, $filterOut)
            ) {
                $result[$field] = $callback
                    ? $callback($field, $definition)
                    : $definition;
            }
        }

        return $result;
    }

    /**
     * @param array $fields fields to normalize
     * @param array $filters list of the only valid fields. Does not accept
     *   wildcards and if empty then the returned list will be empty too.
     * @param ?Callable $callback can be used to process the field definition
     *   with signature `function (string $field, mixed $definition): mixed`
     *   where the return is the value to be associated to $field.
     * @return array filtered and normalized fields.
     * @see normalize
     */
    public static function normalizeStrict(
        array $fields,
        array $filters,
        ?Callable $callback = null
    ): array {
        if (empty($filters)) {
            return [];
        }
        $filters = static::rootFields($filters);

        $result = [];

        foreach ($fields as $field => $definition) {
            if (is_int($field)) {
                $field = $definition;
            }

            if (in_array($field, $filters)) {
                $result[$field] = $callback
                    ? $callback($field, $definition)
                    : $definition;
            }
        }

        return $result;
    }

    /**
     * List of fields requested to the root item. Example
     *
     * ```php
     * [
     *    'name',
     *    'shop.name',
     *    'shop.address',
     * ]
     * ```
     *
     * will return `['name', 'shop']`
     *
     * @param array $fields
     * @return array
     */
    public static function rootFields(array $fields): array
    {
        $result = [];

        foreach ($fields as $field) {
            $result[] = current(explode('.', $field, 2));
        }

        if (in_array('*', $result, true)) {
            return [];
        }

        return array_unique($result);
        
    }

    /**
     * List of fields requested to the $rootItem. Example
     *
     * ```php
     * [
     *    'name',
     *    'shop.name',
     *    'shop.address',
     * ]
     * ```
     *
     * will for $rootItem 'shop' will return `['name', 'address']`
     *
     * @param array $fields
     * @param string $rootItem
     * @return array
     */
    public static function fieldsFor(array $fields, string $rootField): array
    {
        $result = [];

        foreach ($fields as $field) {
            if (str_starts_with($field, "{$rootField}.")) {
                $result[] = substr($field, strlen($rootField) + 1);
            }
        }

        return array_unique($result);
    }
}
