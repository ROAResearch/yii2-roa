<?php

namespace roaresearch\yii2\roa;

/**
 * Interface to get a the information of a file associated to a model.
 *
 * @author Angel (Faryshta) Guevara <aguevara@alquimiadigital.mx>
 */
interface FileRecord
{
    /**
     * Full path for the file.
     *
     * @param string $ext the requested extension.
     * @return string the full path for the file.
     */
    public function filePath(string $ext): string;

    /**
     * Name used to save the file upon download.
     *
     * @param string $ext the requested extension.
     * @return ?string
     */
    public function fileName(string $ext): ?string;

    /**
     * Full path for the file.
     *
     * @param string $ext the requested extension.
     * @return string
     */
    public function fileMimeType(string $ext): string;
}
