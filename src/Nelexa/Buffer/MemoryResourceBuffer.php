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
        if ($bytes === null) {
            throw new BufferException("null Bytes");
        }
        if (($fp = fopen("php://memory", "wb+")) === false) {
            throw new BufferException("can not open memory");
        }
        fwrite($fp, $bytes);
        rewind($fp);
        parent::__construct($fp, false);
    }
}