<?php

namespace roaresearch\yii2\roa\urlRules;

/**
 * Url Rule intended solely for the a resource which returns the authenticated
 * user profile.
 *
 * @author Angel (Faryshta) Guevara <angeldelcaos@gmail.com>
 * @see \roaresearch\yii2\roa\controllers\ProfileResource
 */
class Profile extends Resource
{
    /**
     * @inheritdoc
     */
    public $patterns = [
        'PUT,PATCH' => 'update',
        'GET,HEAD' => 'view',
        '' => 'options',
    ];

    /**
     * @inheritdoc
     */
    public $tokens = [];
}
