<?php

namespace roaresearch\yii2\roa\actions;

use roaresearch\yii2\roa\FileRecord;

use Yii;
use yii\base\InvalidConfigException;

/**
 * Access and show s the content of a file on the browser or download it.
 *
 * @author Angel (Faryshta) Guevara <angeldelcaos@gmail.com>
 */
class ProfileFileStream extends \yii\rest\Action
{
    /**
     * @var string GET parameter to decide if force the download or show it on
     * the browser.
     */
    public $downloadParam = 'download';

    /**
     * @inheritdoc
     */
    public function init()
    {
    }

    /**
     * Shows the file on the browser or download it after checking access.
     *
     * @param mixed $id the identifier value.
     * @param string $ext the requested file extension.
     */
    public function run(string $ext)
    {
        $model = Yii::$app->user->identity;
        if (!$model instanceof FileRecord) {
            throw new InvalidConfigException(
                "The user class must implement " . FileRecord::class
            );
        }

        return Yii::$app->response->sendFile(
            $model->filePath($ext),
            $model->fileName($ext),
            [
                'mimeType' => $model->fileMimeType($ext),
                'inline' => !Yii::$app->request
                    ->getQueryParam($this->downloadParam, false),
            ]
        );
    }
}

