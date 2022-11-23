<?php

namespace roaresearch\yii2\roa\hal;

use yii\{base\Action, web\Linkable};

/**
 * Interface which adds all the needed support for a HAL contract.
 *
 * @author Angel (Faryshta) Guevara <angeldelcaos@gmail.com>
 */
interface Contract extends Embeddable, Linkable
{
    /**
     * @return string the URL to the record being referenced.
     */
    public function getSelfLink(): string;

    /**
     * @param array $params
     * @param Action $action
     * @throws \yii\web\HttpException
     */
    public function checkAccess(
        array $params = [],
        ?Action $action = null
    ): void;
}
