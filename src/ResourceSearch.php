<?php

namespace roaresearch\yii2\roa;

use yii\data\DataProviderInterface;

interface ResourceSearch
{
    /**
     * Creates a data provider to search records in a resource.
     *
     * @param array $params search parameters.
     * @param ?string $formName the name of the form to load into the model
     * @return ?DataProviderInterface
     */
    public function search(
        array $params,
        ?string $formName = ''
    ): ?DataProviderInterface;

}
