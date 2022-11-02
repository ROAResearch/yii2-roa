<?php

namespace roaresearch\yii2\roa\actions;

use roaresearch\yii2\roa\hal\ARContract;

/**
 * Restores a record using the `restoreDelete()` method. Meant to be used with
 * library "yii2tech/ar-softdelete".
 *
 * @author Angel (Faryshta) Guevara <aguevara@alquimiadigital.mx>
 */
class Restore extends ProctRecordAction
{
    /**
     * @inheritdoc
     */
    protected string $errorMessage = 'Restore failed for unknown reasons.';

    /**
     * @inheritdoc
     */
    protected function proct(ARContract $model, array $params): bool
    {
        return false !== $model->restore();
    }
}
