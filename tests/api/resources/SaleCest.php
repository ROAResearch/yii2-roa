<?php

namespace resources;

use ApiTester;
use app\fixtures\OauthAccessTokensFixture;
use Codeception\{Example, Util\HttpCode};

/**
 * Cest to Sale resource.
 *
 * @author Carlos (neverabe) Llamosas <carlos@invernaderolabs.com>
 */
class SaleCest extends \roaresearch\yii2\roa\test\AbstractResourceCest
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
        $I->wantTo('Delete a Sale record.');
        $this->internalDelete($I, $example);
    }

    /**
     * @return array[] data for test `delete()`.
     */
    protected function deleteDataProvider()
    {
        return [
            'delete sale 1' => [
                'url' => '/v1/shop/2/employee/2/sale/1',
                'httpCode' => HttpCode::NO_CONTENT,
            ],
            'not found' => [
                'url' => '/v1/shop/2/employee/2/sale/1',
                'httpCode' => HttpCode::NOT_FOUND,
                'validationErrors' => [
                    'name' => 'The record "1" does not exists.'
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
            'shop_id' => 'integer:>0',
            'employee_id' => 'integer:>0',
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getRoutePattern(): string
    {
        return 'v1/shop/<shop_id:\d+>/employee/<employe_id:\d+>/sale';
    }
}
