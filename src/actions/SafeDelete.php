<?php

namespace roaresearch\yii2\roa\actions;

use roaresearch\yii2\roa\hal\ARContract;

/**
 * Deletes a record using the `safeDelete()` method. Meant to be used with
 * library "yii2tech/ar-softdelete".
 *
 * @author Angel (Faryshta) Guevara <angeldelcaos@gmail.com>
 */
class SafeDelete extends ProctRecordAction
{
    use DeleteResponseTrait;

    /**
     * @inheritdoc
     */
    protected string $errorMessage = 'Safe Delete failed for unknown reasons.';

    /**
     * @inheritdoc
     */
    protected function proct(ARContract $model, array $params): bool
    {
        return false !== $model->safeDelete();
    }
}
