<?php

namespace roaresearch\yii2\roa\urlRules;

use Yii;
use yii\{base\InvalidConfigException, web\NotFoundHttpException};

/**
 * Url Rule to handle modules implementing the `UrlRuleCreator` interface.
 *
 * @author Angel (Faryshta) Guevara <angeldelcaos@gmail.com>
 */
class Modular extends Composite
{
    /**
     * @inheritdoc
     *
     * can accept parameters.
     *
     * - {moduleId}: the unique module id associated to this rule.
     */
    public $notFoundMessage = 'Unknown route for module `{moduleId}`.';

    /**
     * @var string unique id to grab the module from the application that will
     * parse the rules.
     */
    public $moduleId;

    /**
     * @var UrlRuleCreator
     */
    protected $module;

    /**
     * @inheritdoc
     */
    protected function isApplicable(string $route): bool
    {
        // only parse rules which start with the module id
        return 0 === strpos($route, $this->moduleId);
    }

    /**
     * @inheritdoc

     */
    protected function createRules()
    {
        return $this->module->createUrlRules($this);
    }

    /**
     * @inheritdoc
     */
    protected function createNotFoundException(): NotFoundHttpException
    {
        return new NotFoundHttpException(
            strtr($this->notFoundMessage, ['{moduleId}' => $this->moduleId])
        );
    }

    protected function ensureRules()
    {
        if (empty($this->moduleId)) {
            throw new InvalidConfigException('`moduleId` must be set.');
        }
        $this->module = Yii::$app->getModule($this->moduleId);
        if (!$this->module instanceof UrlRuleCreator) {
            throw new InvalidConfigException(
                "Module `{$this->moduleId}` must implement "
                    . UrlRuleCreator::class
            );
        }
        $this->module->initCreator($this);

        parent::ensureRules();
    }
}
