<?php

namespace roaresearch\yii2\roa\urlRules;

use Yii;

/**
 * Default Url Rule to handle resources with collections.
 *
 * Supports representative URL using ownership slug.
 *
 * @author Angel (Faryshta) Guevara <angeldelcaos@gmail.com>
 */
class Resource extends \yii\rest\UrlRule
{
    /**
     * @inheritdoc
     */
    public $pluralize = false;

    /**
     * @inheritdoc
     */
    public $tokens = ['{id}' => '<id:\d+>'];

    /**
     * @inheritdoc
     */
    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        foreach ($this->rules as $urlName => $rules) {
            foreach ($rules as $rule) {
                /* @var $rule \yii\web\UrlRule */
                $result = $rule->parseRequest($manager, $request);
                if (YII_DEBUG) {
                    Yii::trace([
                        'rule' => $rule instanceof \Stringable
                            ? (string)$rule
                            : $rule::class,
                        'match' => $result !== false,
                        'parent' => static::class,
                    ], __METHOD__);
                }
                if ($result !== false) {
                    return $result;
                }
            }
        }

        return false;
    }
}
