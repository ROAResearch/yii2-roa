<?php

namespace roaresearch\yii2\roa\urlRules;

use Yii;
use yii\{
    base\InvalidConfigException,
    di\Instance,
    web\NotFoundHttpException,
    web\UrlManager,
    web\UrlNormalizer
};

/**
 * Url rule that can call children rule when applicable.
 *
 * @author Angel (Faryshta) Guevara <angeldelcaos@gmail.com>
 */
abstract class Composite extends \yii\web\CompositeUrlRule
{
    /**
     * @var bool whether this rule must throw an `NotFoundHttpException` when
     * parse request fails.
     */
    public bool $strict = true;

    /**
     * @var string message used to create the `NotFoundHttpException` when
     * `$strict` equals `true` and no children rules could parse the request.
     */
    public string $notFoundMessage = 'Unknown route.';

    /**
     * @var ?UrlNormalizer
     */
    protected ?UrlNormalizer $normalizer = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
    }

    /**
     * @inheritdoc
     */
    public function setNormalizer(UrlNormalizer | array $normalizer): void
    {
        $this->normalizer = Instance::ensure($normalizer, UrlNormalizer::class);
    }

    /**
     * @param UrlManager $manager the URL manager
     * @return ?UrlNormalizer
     */
    protected function getNormalizer(UrlManager $manager): ?UrlNormalizer
    {
        if (!$this->normalizer && $manager->normalizer) {
            $this->setNormalizer($manager->normalizer);
        }

        return $this->normalizer;
    }

    /**
     * Determines if this rule must parse the request using the children rules
     * or return `false` inmediately.
     *
     * @param string $route
     * @return bool
     */
    abstract protected function isApplicable(string $route): bool;

    /**
     * Ensures that `$rules` property is set
     */
    protected function ensureRules()
    {
        $this->rules = $this->rules ?: $this->createRules();
    }

    /**
     * @inheritdoc
     */
    public function parseRequest($manager, $request)
    {
        // only parse rules applicable rules
        if (!$this->isApplicable($request->pathInfo)) {
            return false;
        }

        $normalized = false;
        if ($this->hasNormalizer($manager)) {
            $request->pathInfo = $this->getNormalizer($manager)
                ->normalizePathInfo(
                    $request->pathInfo,
                    '',
                    $normalized
                );
        }

        $this->ensureRules();
        $result = parent::parseRequest($manager, $request);

        if ($result === false && $this->strict === true) {
            throw $this->createNotFoundException();
        }

        return $normalized
            ? $this->getNormalizer($manager)->normalizeRoute($result)
            : $result;
    }

    /**
     * @inheritdoc
     */
    public function createUrl($manager, $route, $params)
    {
        // only parse rules applicable rules
        if (!$this->isApplicable($route)) {
            return false;
        }
        $this->ensureRules();

        return parent::createUrl($manager, $route, $params);
    }

    /**
     * @param UrlManager $manager the URL manager
     * @return bool
     */
    protected function hasNormalizer($manager): bool
    {
        return null !== $this->getNormalizer($manager);
    }

    /**
     * @return NotFoundHttpException
     */
    protected function createNotFoundException(): NotFoundHttpException
    {
        return new NotFoundHttpException($this->notFoundMessage);
    }
}
