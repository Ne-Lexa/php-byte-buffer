<?php
namespace Nelexa\Buffer;


class StringBufferTest extends BufferTestCase
{

    /**
     * @return Buffer
     */
    protected function createBuffer()
    {
        return new StringBuffer();
    }
}