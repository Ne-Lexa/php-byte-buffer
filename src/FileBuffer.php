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
        $this->setFile($file, $readOnly);
    }

    /**
     * @param string $file
     * @param bool $readOnly
     * @throws BufferException
     */
    public function setFile($file, $readOnly = true)
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
        $fileSize = file_exists($file) ? filesize($file) : 0;

        $mode = $readOnly ? "rb" : (file_exists($file) ? 'r' : 'w') . "b+";

        if (($fp = fopen($file, $mode)) === false) {
            throw new BufferException("file '" . $file . "' can not open.");
        }
        $this->setResource($fp, $fileSize, $readOnly);
    }
}