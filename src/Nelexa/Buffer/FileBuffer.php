<?php
namespace Nelexa\Buffer;


class FileBuffer extends ResourceBuffer
{
    /**
     * @param string $file
     * @param bool $readOnly
     * @throws BufferException
     */
    function __construct($file, $readOnly = false)
    {
        if ($file === null) {
            throw new BufferException("file is null");
        }
        if ($readOnly && !is_readable($file)) {
            throw new BufferException("file '" . $file . "' is not readable.");
        }
        if (!$readOnly && !is_writable(dirname($file))) {
            throw new BufferException("file '" . $file . "' is not writable.");
        }

        $mode = $readOnly ? "rb" : (file_exists($file) ? 'r+' : 'w+') . "b";

        if (($fp = fopen($file, $mode)) === false) {
            throw new BufferException("file '" . $file . "' can not open.");
        }
        parent::__construct($fp, $readOnly);
    }
}