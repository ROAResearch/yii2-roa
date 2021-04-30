<?php

namespace app\api\models;

use roaresearch\yii2\roa\hal\ARContractSearch;
use yii\data\ActiveDataProvider;

/**
 * Contract to filter and sort collections of `Shop` records.
 *
 * @author Carlos (neverabe) Llamosas <carlos@invernaderolabs.com>
 */
class ShopSearch extends Shop implements ARContractSearch
{
    /**
     * @inhertidoc
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
        ];
    }

    /**
     * @inhertidoc
     */
    public function search(
        array $params,
        ?string $formName = ''
    ): ?ActiveDataProvider {
        $this->load($params, $formName);
        if (!$this->validate()) {
            return null;
        }
        $class = get_parent_class();

        return new ActiveDataProvider([
            'query' => $class::find()->andFilterDeleted()->andFilterWhere(
                ['like', 'name', $this->name]
            ),
        ]);
    }
}
