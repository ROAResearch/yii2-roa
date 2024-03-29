<?php

namespace roaresearch\yii2\roa\modules;

use DateTime;
use roaresearch\yii2\roa\{
    controllers\ApiVersionController,
    urlRules\Composite as CompositeUrlRule,
    urlRules\Resource as ResourceUrlRule,
    urlRules\UrlRuleCreator
};
use Yii;
use yii\{
    base\InvalidConfigException,
    helpers\ArrayHelper,
    helpers\Url,
    web\JsonResponseFormatter,
    web\Response,
    web\UrlManager,
    web\XmlResponseFormatter
};

/**
 * Class to attach a version to an `ApiContainer` module.
 *
 * You can control the stability by setting the properties `$releaseDate`,
 * `$deprecationDate` and `$obsoleteDate`.
 *
 * The resources are declared using the `$resources` array property
 */
class ApiVersion extends \yii\base\Module implements UrlRuleCreator
{
    public const STABILITY_DEVELOPMENT = 'development';
    public const STABILITY_STABLE = 'stable';
    public const STABILITY_DEPRECATED = 'deprecated';
    public const STABILITY_OBSOLETE = 'obsolete';

    /**
     * @var string subfix used to create the default classes
     */
    public string $controllerSubfix = 'Resource';

    /**
     * @var string full class name which will be used as default for routing.
     */
    public string $urlRuleClass = ResourceUrlRule::class;

    /**
     * @var ?string date in Y-m-d format for the date at which this version
     * became stable
     */
    public ?string $releaseDate = null;

    /**
     * @var ?string date in Y-m-d format for the date at which this version
     * became deprecated
     */
    public ?string $deprecationDate = null;

    /**
     * @var ?string date in Y-m-d format for the date at which this version
     * became obsolete
     */
    public ?string $obsoleteDate = null;

    /**
     * @var string URL where the api documentation can be found.
     */
    public ?string $apidoc = null;

    /**
     * @var array|ResponseFormatterInterface[] response formatters which will
     * be attached to `Yii::$app->response->formatters`. By default just enable
     * HAL responses.
     */
    public array $responseFormatters = [
        Response::FORMAT_JSON => [
            'class' => JsonResponseFormatter::class,
            'contentType' => JsonResponseFormatter::CONTENT_TYPE_HAL_JSON,
        ],
        Response::FORMAT_XML => [
            'class' => XmlResponseFormatter::class,
            'contentType' => 'application/hal+xml',
        ],
    ];

    /**
     * @var string the stability level
     */
    protected string $stability = self::STABILITY_DEVELOPMENT;

    /**
     * @return string the stability defined for this version.
     */
    public function getStability(): string
    {
        return $this->stability;
    }

    /**
     * @inheritdoc
     */
    public $defaultRoute = 'index';

    /**
     * @inheritdoc
     */
    public $controllerMap = ['index' => ApiVersionController::class];

    /**
     * @var string[] list of 'patternRoute' => 'resource' pairs to connect a
     * route to a resource. if no key is used, then the value will be the
     * pattern too.
     *
     * Special properties:
     *
     * - urlRule array the configuration for how the routing url rules will be
     *   created before attaching them to urlManager.
     *
     * ```php
     * [
     *     'profile', // resources\ProfileResource
     *     'profile/history', // resources\profile\HistoryResource
     *     'profile/image' => [
     *         'class' => resources\profile\ImageResource::class,
     *         'urlRule' => ['class' => 'roaresearch\yii2\\roa\\urlRules\\File'],
     *     ],
     *     'post' => ['class' => resources\post\PostResource::class],
     *     'post/<post_id:[\d]+>/reply', // resources\post\ReplyResource
     * ]
     * ```
     */
    public array $resources = [];

    /**
     * @return string[] gets the list of routes allowed for this api version.
     */
    public function getRoutes(): array
    {
        $routes = ['/'];
        foreach ($this->resources as $index => $value) {
            $routes[] =
                (is_string($index) ? $index : $value);
        }

        return $routes;
    }

    /**
     * @return array stability, life cycle and resources for this version.
     */
    public function getFactSheet(): array
    {
        return [
            'stability' => $this->stability,
            'lifeCycle' => [
                'releaseDate' => $this->releaseDate,
                'deprecationDate' => $this->deprecationDate,
                'obsoleteDate' => $this->obsoleteDate,
            ],
            'routes' => $this->getRoutes(),
            '_links' => [
                'self' => $this->getSelfLink(),
                'apidoc' => $this->apidoc,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $releaseTime = $this->calcTime($this->releaseDate);
        $now = time();

        if ($releaseTime !== null && $releaseTime <= $now) {
            $deprecationTime = $this->calcTime($this->deprecationDate);
            $obsoleteTime = $this->calcTime($this->obsoleteDate);
            if ($deprecationTime !== null && $obsoleteTime !== null) {
                if ($obsoleteTime < $deprecationTime) {
                    throw new InvalidConfigException(
                        'The `obsoleteDate` must not be earlier than `deprecationDate`'
                    );
                }
                if ($deprecationTime < $releaseTime) {
                    throw new InvalidConfigException(
                        'The `deprecationDate` must not be earlier than `releaseDate`'
                    );
                }

                if ($obsoleteTime < $now) {
                    $this->stability = self::STABILITY_OBSOLETE;
                } elseif ($deprecationTime < $now) {
                    $this->stability = self::STABILITY_DEPRECATED;
                } else {
                    $this->stability = self::STABILITY_STABLE;
                }
            } else {
                $this->stability = self::STABILITY_STABLE;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        foreach ($this->responseFormatters as $id => $responseFormatter) {
            Yii::$app->response->formatters[$id] = $responseFormatter;
        }

        return true;
    }

    /**
     * @return array list of configured urlrules by default
     */
    protected function defaultUrlRules(): array
    {
        return [
            Yii::createObject([
                'class' => \yii\web\UrlRule::class,
                'pattern' => $this->uniqueId,
                'route' => $this->uniqueId,
                'normalizer' => ['class' => \yii\web\UrlNormalizer::class],
            ]),
        ];
    }

    /**
     * @inheritdoc
     */
    public function initCreator(CompositeUrlRule $urlRule): void
    {
        if ($this->stability == self::STABILITY_OBSOLETE) {
            return;
        }

        $resources = []; // normalized resources
        foreach ($this->resources as $route => $controller) {
            if (is_string($controller)) {
                $route = $controller;
                $controllerRoute = $this->buildControllerRoute($route);

                $this->controllerMap[$controllerRoute] = $resources[$route] = [
                    'class' => $this->buildControllerClass($controllerRoute),
                ];
                $resources[$route]['controllerRoute'] = $controllerRoute;

                continue;
            }

            if (is_array($controller)) {
                $controllerRoute = isset($controller['controllerRoute'])
                    ? ArrayHelper::remove($controller, 'controllerRoute')
                    : $this->buildControllerRoute($route);

                $controller['class'] = $controller['class']
                    ?? $this->buildControllerClass($controllerRoute);

                $resources[$route] = $controller;
                $resources[$route]['controllerRoute'] = $controllerRoute;

                ArrayHelper::remove($controller, 'urlRule');
                $this->controllerMap[$controllerRoute] = $controller;

                continue;
            }

            // case its an object
            $resources[$route] = [
                'controllerRoute' => $cR = $this->buildControllerRoute($route),
            ];

            $this->controllerMap[$cR] = $controller;
        }

        $this->resources = $resources; // homologate resources
    }

    /**
     * @inheritdoc
     */
    public function createUrlRules(CompositeUrlRule $urlRule): array
    {
        $rules = $this->defaultUrlRules();
        if ($this->stability == self::STABILITY_OBSOLETE) {
            $rules[] = Yii::createObject([
                'class' => \yii\web\UrlRule::class,
                'pattern' => $this->uniqueId . '/<route:*+>',
                'route' => $this->module->uniqueId . '/index/gone',
            ]);

            return $rules;
        }

        foreach ($this->resources as $route => $c) {
            $rules[] = Yii::createObject(array_merge(
                [
                    'class' => $this->urlRuleClass,
                    'controller' => [
                        $route => "{$this->uniqueId}/{$c['controllerRoute']}"
                    ],
                    'prefix' => $this->uniqueId,
                ],
                ArrayHelper::remove($c, 'urlRule', [])
            ));
        }

        return $rules;
    }

    /**
     * Converts a ROA route to an MVC route to be handled by `$controllerMap`
     *
     * @param string $roaRoute
     * @return string
     */
    private function buildControllerRoute(string $roaRoute): string
    {
        return strtr(
            preg_replace(
                '/\/\<.*?\>\//',
                '--',
                $roaRoute
            ),
            ['/' => '-']
        );
    }

    /**
     * Converts an MVC route to the default controller class.
     *
     * @param string $controllerRoute
     * @return string
     */
    private function buildControllerClass(string $controllerRoute): string
    {
        $lastSeparator = strrpos($controllerRoute, '--');
        if ($lastSeparator === false) {
            $lastClass = $controllerRoute;
            $ns = '';
        } else {
            $lastClass = substr($controllerRoute, $lastSeparator + 2);
            $ns = substr($controllerRoute, 0, $lastSeparator + 2);
        }

        return $this->controllerNamespace
            . '\\' . strtr($ns, ['--' => '\\'])
            . str_replace(' ', '', ucwords(str_replace('-', ' ', $lastClass)))
            . $this->controllerSubfix;
    }

    /**
     * @param string $date in 'Y-m-d' format
     * @return ?int unix timestamp
     */
    private function calcTime($date): ?int
    {
        if ($date === null) {
            return null;
        }
        $dt = DateTime::createFromFormat('Y-m-d', $date)
            ?: throw new InvalidConfigException(
                'Dates must use the "Y-m-d" format.'
            );

        return $dt->getTimestamp();
    }

    /**
     * @return string HTTP Url linking to this module
     */
    public function getSelfLink(): string
    {
        return Url::to(['//' . $this->getUniqueId()], true);
    }
}
