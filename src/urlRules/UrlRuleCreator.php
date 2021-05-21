<?php

namespace roaresearch\yii2\roa\urlRules;

interface UrlRuleCreator
{
    /**
     * Method created to initialize the creator even if the rules were loaded
     * from cache or other external source.
     *
     * @param Composite $urlRule the $urlRule object invoking the creator.
     * @return bool whether creation
     */
    public function initCreator(Composite $urlRule): void;

    /**
     * Creates children url rules to be passed to a `Compoposite` url rule.
     *
     * @param Composite $urlRule the $urlRule object invoking the creator.
     * @return \yii\web\UrlRuleInterface[]
     */
    public function createUrlRules(Composite $urlRule): array;
}
