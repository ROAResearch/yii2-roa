<?php

namespace resources;

use ApiTester;
use app\fixtures\OauthAccessTokensFixture;
use Codeception\{Example, Util\HttpCode};

/**
 * Cest to SaleItem resource.
 *
 * @author Carlos (neverabe) Llamosas <carlos@invernaderolabs.com>
 */
class SaleItemCest extends \roaresearch\yii2\roa\test\AbstractResourceCest
{
    protected function authToken(ApiTester $I)
    {
        $I->amBearerAuthenticated(OauthAccessTokensFixture::SIMPLE_TOKEN);
    }

    /**
     * @param  ApiTester $I
     * @param  Example $example
     * @dataprovider deleteDataProvider
     * @depends resources\ShopCest:fixtures
     * @before authToken
     */
    public function delete(ApiTester $I, Example $example)
    {
        $I->wantTo('Delete a Sale Item record.');
        $this->internalDelete($I, $example);
    }

    /**
     * @return array[] data for test `delete()`.
     */
    protected function deleteDataProvider()
    {
        return [
            'delete item 1' => [
                'url' => '/v1/shop/2/employee/3/sale/2/item/3',
                'httpCode' => HttpCode::NO_CONTENT,
            ],
            'not found' => [
                'url' => '/v1/shop/2/employee/3/sale/2/item/3',
                'httpCode' => HttpCode::NOT_FOUND,
                'validationErrors' => [
                    'name' => 'The record "3" does not exists.'
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    protected function recordJsonType(): array
    {
        return [
            'id' => 'integer:>0',
            'sale_id' => 'integer:>0',
            'item_id' => 'integer:>0',
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getRoutePattern(): string
    {
        return 'v1/shop/<shop_id:\d+>/sale/<sale_id:\d+>/item';
    }
}
