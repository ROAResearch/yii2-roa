<?php

namespace roaresearch\yii2\roa\modules;

use roaresearch\yii2\oauth2server\filters\auth\CompositeAuth;
use roaresearch\yii2\oauth2server\Module as OAuth2Module;
use roaresearch\yii2\roa\{
    controllers\ApiContainerController,
    urlRules\Composite as CompositeUrlRule,
    urlRules\Modular as ModularUrlRule,
    urlRules\UrlRuleCreator
};
use Yii;
use yii\{
    base\BootstrapInterface,
    base\InvalidArgumentException,
    base\Module,
    filters\ContentNegotiator,
    helpers\Url,
    web\Response,
    web\UrlNormalizer
};

/**
 * @author Angel (Faryshta) Guevara <aguevara@tecnocen.com>
 *
 * @var OAuth2Module $oauth2Module
 */
class ApiContainer extends Module implements UrlRuleCreator, BootstrapInterface
{
    /**
     * @var string
     */
    public string $identityClass;

    /**
     * @var string
     */
    public string $versionUrlRuleClass = ModularUrlRule::class;

    /**
     * @var string
     */
    public string $containerUrlRuleClass = ModularUrlRule::class;

    /**
     * @inheritdoc
     */
    public $defaultRoute = 'index';

    /**
     * @inheritdoc
     */
    public $controllerMap = ['index' => ApiContainerController::class];

    /**
     * @var array
     */
    public array $versions = [];

    /**
     * @var string
     */
    public string $errorAction;

    /**
     * @var string the module id for the oauth2 server module.
     */
    public string $oauth2ModuleId = 'oauth2';

    /**
     * @var array default OAuth2Module configuration.
     */
    private array $oauth2Module = [
        'class' => OAuth2Module::class,
        'tokenParamName' => 'accessToken',
        'tokenAccessLifetime' => 3600 * 24,
        'storageMap' => [
        ],
        'grantTypes' => [
            'user_credentials' => [
                'class' => \OAuth2\GrantType\UserCredentials::class,
            ],
            'refresh_token' => [
                'class' => \OAuth2\GrantType\RefreshToken::class,
                'always_issue_new_refresh_token' => true
            ],
        ],
    ];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
            ],
            'authenticator' => [
                'class' => CompositeAuth::class,
                'oauth2Module' => $this->getUniqueId() . '/'
                    . $this->oauth2ModuleId,
                'except' => [
                    'oauth2/*', // the oauth2 module
                    'index/*', // controller that return this module info
                    '*/options', // all OPTIONS actions for CORS preflight
                ],
            ],
        ];
    }

    /**
     * @var array module
     */
    public function setOauth2Module($module)
    {
        if (is_array($module)) {
            $this->setModule($this->oauth2ModuleId, array_merge(
                $this->oauth2Module,
                ['storageMap' => ['user_credentials' => $this->identityClass]],
                $module
            ));
        } elseif (!$module instanceof OAuth2Module) {
            $this->setModule($this->oauth2ModuleId, $module);
        } else {
            throw new InvalidArgumentException(
                static::class
                    . '::$oauth2Module must be an array or instance of '
                    . OAuth2Module::class
            );
        }
    }

    /**
     * @return OAuth2Module
     */
    public function getOauth2Module(): OAuth2Module
    {
        if (!$this->hasModule($this->oauth2ModuleId)) {
            $this->oauth2Module['storageMap']['user_credentials']
                = $this->identityClass;
            $this->setModule($this->oauth2ModuleId, $this->oauth2Module);
        }

        return $this->getModule($this->oauth2ModuleId);
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->getOauth2Module()->bootstrap($app);
        if (empty($this->errorAction)) {
            $this->errorAction = $this->uniqueId . '/index/error';
        }
        $app->urlManager->addRules([[
            'class' => $this->containerUrlRuleClass,
            'moduleId' => $this->uniqueId,
            'normalizer' => [
                'action' => UrlNormalizer::ACTION_REDIRECT_PERMANENT,
            ],
        ]]);
    }

    /**
     * @return ApiVersion[] return all the versions attached to the container
     * indexed by their respective id.
     */
    public function getVersionModules(): array
    {
        $versions = [];
        foreach ($this->versions as $route => $config) {
            if (!$this->hasModule($route)) {
                $this->setModule($route, $config);
            }
            $versions[$route] = $this->getModule($route);
        }

        return $versions;
    }

    /**
     * @return \yii\web\UrlRuleInterface[]
     */
    protected function defaultUrlRules(): array
    {
        return [
            Yii::createObject([
                'class' => \yii\web\UrlRule::class,
                'pattern' => $this->getUniqueId(),
                'route' => $this->getUniqueId(),
            ]),
        ];
    }

    /**
     * @inheritdoc
     */
    public function initCreator(CompositeUrlRule $urlRule): void
    {
        // change the error handler and identityClass
        Yii::$app->errorHandler->errorAction = $this->errorAction;
        Yii::$app->user->identityClass = $this->identityClass;

        $auth = $this->getBehavior('authenticator');
        foreach ($this->versions as $route => $config) {
            $auth->except[] = $route . '/index/*';
            $this->setModule($route, $config);
        }
    }

    /**
     * @inheritdoc
     */
    public function createUrlRules(CompositeUrlRule $urlRule): array
    {
        $rules = $this->defaultUrlRules();
        foreach ($this->versions as $route => $config) {
            $rules[] = Yii::createObject([
                'class' => $this->versionUrlRuleClass,
                'moduleId' => "{$this->uniqueId}/$route",
            ]);
        }

        return $rules;
    }

    /**
     * @return string HTTP Url linking to this module
     */
    public function getSelfLink(): string
    {
        return Url::to(['//' . $this->getUniqueId()]);
    }
}
