<?php

namespace roaresearch\yii2\roa\actions;

use roaresearch\yii2\roa\hal\ARContract;

/**
 * Deletes a record from the database.
 *
 * @author Angel (Faryshta) Guevara <angeldelcaos@gmail.com>
 */
class Delete extends ProctRecordAction
{
    use DeleteResponseTrait;

    /**
     * @inheritdoc
     */
    protected string $errorMessage = 'Delete failed for unknown reasons.';

    /**
     * @inheritdoc
     */
    protected function proct(ARContract $model, array $params): bool
    {
        return false !== $model->delete();
    }
}
