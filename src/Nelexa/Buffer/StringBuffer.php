<?php
namespace Nelexa\Buffer;

/**
 * StringBuffer class for binary safe operation with strings
 * (Like Java ByteBuffer Or Java DataInputStream and DataOutputStream).
 */
class StringBuffer extends Buffer
{
    /**
     * @var string
     */
    private $string;

    /**
     * Wraps a string into a buffer.
     *
     * @param string $string
     * @throws BufferException
     */
    function __construct($string = "")
    {
        if ($string === null) {
            throw new BufferException("null Bytes");
        }
        $this->setString($string);
    }

    /**
     * @param string $string
     */
    public final function setString($string)
    {
        $this->string = $string;
        $this->rewind();
        $this->newLimit(strlen($this->string));
    }

    /**
     * @return string
     */
    public final function toString()
    {
        return $this->string;
    }

    /**
     * Flips this buffer.  The limit is set to the current position and then
     * the position is set to zero.
     *
     * After a sequence of channel-read or put operations, invoke
     * this method to prepare for a sequence of channel-write or relative
     * get operations.
     */
    public final function flip()
    {
        $this->setString(substr($this->string, 0, $this->position()));
        $this->setPosition(0);
    }


    /**
     * Relative get method.
     * Reads the string at this buffer's current position, and then increments the position.
     *
     * @param $length
     * @return string The strings at the buffer's current position
     * @throws BufferException
     */
    protected function get($length)
    {
        if ($length > $this->remaining()) {
            throw new BufferException("get length > remaining");
        }
        $str = substr($this->string, $this->position(), $length);
        $this->skip($length);
        return $str;
    }


    /**
     * @param Buffer|string $buffer
     * @throws BufferException
     */
    public function insert($buffer)
    {
        if ($this->isReadOnly()) {
            throw new BufferException("Read Only");
        }
        if ($buffer === null) {
            throw new BufferException("null buffer");
        }
        if ($buffer instanceof Buffer) {
            $buffer = $buffer->toString();
        }
        $length = strlen($buffer);
        $this->string = substr_replace($this->string, $buffer, $this->position(), 0);
        $this->newLimit($this->size() + $length);
        $this->skip($length);
    }

    /**
     * Relative put method (optional operation).
     *
     * Writes the given string into this buffer at the current
     * position, and then increments the position.
     *
     * @param Buffer|string $buffer
     * @throws BufferException
     */
    public function put($buffer)
    {
        if ($this->isReadOnly()) {
            throw new BufferException("Read Only");
        }
        if ($buffer === null) {
            throw new BufferException("null buffer");
        }
        $length = null;
        if ($buffer instanceof Buffer) {
            $length = $buffer->size();
            $buffer = $buffer->toString();
        } else {
            $length = strlen($buffer);
        }
        if ($length > $this->remaining()) {
            throw new BufferException("put length > remaining");
        }
        $this->string = substr_replace($this->string, $buffer, $this->position(), $length);
        $this->skip($length);
    }


    /**
     * @param Buffer|string $buffer
     * @param int $length remove length bytes
     * @throws BufferException
     */
    public function replace($buffer, $length)
    {
        $length = (int)$length;
        if ($this->isReadOnly()) {
            throw new BufferException("Read Only");
        }
        if ($length < 0) {
            throw new BufferException("length < 0");
        }
        if ($length > $this->remaining()) {
            throw new BufferException("replace length > remaining");
        }
        if ($buffer === null) {
            throw new BufferException("null buffer");
        }
        if ($buffer instanceof Buffer) {
            $buffer = $buffer->toString();
        }
        $bufferLength = strlen($buffer);
        $this->string = substr_replace($this->string, $buffer, $this->position(), $length);
        $this->newLimit($this->size() + $bufferLength - $length);
        $this->skip($bufferLength);
    }


    /**
     * @param int $length
     * @throws BufferException
     */
    public final function remove($length)
    {
        if ($this->isReadOnly()) {
            throw new BufferException("Read Only");
        }
        if ($length > $this->remaining()) {
            throw new BufferException("remove length > remaining");
        }
        $this->string = substr_replace($this->string, '', $this->position(), $length);
        $this->newLimit($this->size() - $length);
    }

    /**
     * Truncate buffer
     */
    public final function truncate()
    {
        $this->setString("");
    }

    /**
     * Close buffer. If this buffer resource that closes the stream.
     */
    public function close()
    {
        if ($this->string !== null) {
            $this->string = null;
        }
    }

    /**
     * Destruct object, close file description.
     */
    function __destruct()
    {
        $this->close();
    }

    /**
     * @return string
     */
    function __toString()
    {
        return __CLASS__ . '{' .
        'position=' . $this->position() .
        ', limit=' . $this->size() .
        ', order=' . $this->order() .
        ', readOnly=' . ($this->isReadOnly() ? 'true' : 'false') .
        '}';
    }
}