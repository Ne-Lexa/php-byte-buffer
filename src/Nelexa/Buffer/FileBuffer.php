<?php
namespace Nelexa\Buffer;


class FileBuffer extends ResourceBuffer
{
    /**
     * @var bool
     */
    private $writable;

    /**
     * @param string $file
     * @throws BufferException
     */
    function __construct($file)
    {
        if ($file === null) {
            throw new BufferException("file is null");
        }
        if (!is_readable($file)) {
            throw new BufferException("file '" . $file . "' is not readable.");
        }
        $this->writable = is_writable(dirname($file));
        parent::setReadOnly(!$this->writable);

        $mode = !$this->writable ? "rb" : (file_exists($file) ? 'r+' : 'w+') . "b";

        if (($fp = fopen($file, $mode)) === false) {
            throw new BufferException("file '" . $file . "' can not open.");
        }
        parent::__construct($fp);
    }

    /**
     * @param bool $isReadOnly
     * @return Buffer
     * @throws BufferException
     */
    public function setReadOnly($isReadOnly)
    {
        if(!$this->writable && !$isReadOnly){
            throw new BufferException("You can not set the recording flag. The directory containing the file is not available for recording.");
        }
        return parent::setReadOnly($isReadOnly);
    }


}