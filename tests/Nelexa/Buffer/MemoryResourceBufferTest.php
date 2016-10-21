<?php
namespace Nelexa\Buffer;


class MemoryResourceBufferTest extends BufferTestCase
{

    /**
     * @return Buffer
     */
    protected function createBuffer()
    {
        return new MemoryResourceBuffer();
    }

}