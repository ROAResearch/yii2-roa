<?php

namespace roaresearch\yii2\roa\actions;

use roarsearch\yii2\roa\hal\ARContract;

/**
 * Deletes a record using the `softDelete()` method. Meant to be used with
 * library "yii2tech/ar-softdelete".
 *
 * @author Angel (Faryshta) Guevara <aguevara@alquimiadigital.mx>
 */
class SoftDelete extends ProctRecordAction
{
    /**
     * @inheritdoc
     */
    protected string $errorMessage = 'Soft Delete failed for unknown reasons.';

    /**
     * @inheritdoc
     */
    protected function proct(ARContract $model, array $params): bool
    {
        return false !== $model->softDelete();
    }
}
