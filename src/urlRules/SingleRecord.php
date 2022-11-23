<?php

namespace roaresearch\yii2\roa\urlRules;

/**
 * Rule for routing resources which will only handle a record per authorized
 * user or globally in the sistem.
 *
 * That means the resource doesn't contain collections.
 *
 * @author Angel (Faryshta) Guevara <angeldelcaos@gmail.com>
 */
class SingleRecord extends Resource
{
    /**
     * @inheritdoc
     */
    public $patterns = [
        'PUT,PATCH' => 'update',
        'DELETE' => 'delete',
        'GET,HEAD' => 'view',
        'POST' => 'create',
        '' => 'options',
    ];

    /**
     * @var string[] list of valid extensions that this rule can handle.
     */
    public $ext = ['png', 'jpg', 'jpeg'];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->tokens['{ext}'] = '<ext:(' . implode('|', $this->ext) . ')>';
        parent::init();
    }
}
