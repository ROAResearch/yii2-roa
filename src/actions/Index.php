<?php

namespace roaresearch\yii2\roa\actions;

use roaresearch\yii2\roa\hal\ARContractSearch;
use Yii;
use yii\{base\InvalidConfigException, data\DataProviderInterface};

/**
 * Action to retreive a filtered and sorted collection based on a `$searchClass`
 *
 * @author Angel (Faryshta) Guevara <aguevara@alquimiadigital.mx>
 */
class Index extends Action
{
    /**
     * @var string model class to retreive the records on the collection.
     */
    public string $searchClass;

    /**
     * @var string name of the form containing the filter data.
     */
    public string $formName = '';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->searchClass ?: throw new InvalidConfigException(
            $this::class . '::$searchClass must be set.'
        );
    }

    /**
     * @return DataProviderInterface|ActiveRecordInterface
     */
    public function run(): DataProvideInterface | ARContractSearch
    {
        $searchModel = $this->buildSearchModel();
        $dataProvider = $searchModel->search(
            Yii::$app->request->getQueryParams(),
            $this->formName
        );
        $this->checkAccess($searchModel, Yii::$app->request->getQueryParams());

        return $dataProvider ?: $searchModel;
    }

    /**
     * @return ARContractSearch the model used to create the data provider
     */
    protected function buildSearchModel(): ARContractSearch
    {
        return new {$this->searchClass}();
    }
}
