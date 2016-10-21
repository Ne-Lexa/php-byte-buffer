<?php
namespace Nelexa\Buffer;


class MemoryResourceBuffer extends ResourceBuffer
{
    /**
     * Wraps a string into a buffer.
     *
     * @param string $bytes
     * @throws BufferException
     */
    function __construct($bytes = "")
    {
        if (($fp = fopen("php://memory", "w+b")) === false) {
            throw new BufferException("Can not open memory");
        }
        if(!empty($bytes)) {
            fwrite($fp, (string)$bytes);
            rewind($fp);
        }
        parent::__construct($fp);
    }
}