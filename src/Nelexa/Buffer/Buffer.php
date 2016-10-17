<?php
namespace Nelexa\Buffer;

abstract class Buffer
{
    const BIG_ENDIAN = "BIG_ENDIAN";
    const LITTLE_ENDIAN = "LITTLE_ENDIAN";

    /**
     * @var int
     */
    private $position = 0;
    /**
     * @var int
     */
    private $limit = 0;
    /**
     * @var string
     */
    private $order = self::BIG_ENDIAN;
    /**
     * @var boolean
     */
    private $isReadOnly = false;

    /**
     * @param int $position
     * @throws BufferException
     */
    public function setPosition($position)
    {
        $position = (int)$position;
        if ($position > $this->limit) {
            throw new BufferException('Set position ' . $position . ' invalid. Exceeded limit ' . $this->limit);
        }
        $this->position = $position;
    }

    /**
     * @return int
     */
    public final function position()
    {
        return $this->position;
    }

    /**
     * Rewinds this buffer. The position is set to zero.
     *
     * Invoke this method before a sequence of channel-write or get
     * operations, assuming that the limit has already been set
     * appropriately.
     *
     * For example:
     *
     * $buf->writeString("Hello");  // Write remaining data
     * $buf->rewind();              // Rewind buffer
     * $buf->get(5);                // get 5 bytes (Hello)
     */
    public final function rewind()
    {
        $this->setPosition(0);
    }

    /**
     * Flips this buffer.  The limit is set to the current position and then
     * the position is set to zero.
     *
     * After a sequence of channel-read or put operations, invoke
     * this method to prepare for a sequence of channel-write or relative
     * get operations.
     */
    abstract public function flip();

    /**
     * Returns the number of elements between the current position and the
     * limit.
     *
     * @return int The number of elements remaining in this buffer
     */
    public function remaining()
    {
        return $this->limit - $this->position;
    }

    /**
     * Tells whether there are any elements between the current position and
     * the limit.
     *
     * @return boolean true if, and only if, there is at least one element remaining in this buffer
     */
    public function hasRemaining()
    {
        return $this->position < $this->limit;
    }

    /**
     * Sets this buffer's limit. If the position is larger than the new limit
     * then it is set to the new limit.
     *
     * @param $newLimit int
     * @throws BufferException
     */
    public final function newLimit($newLimit)
    {
        if ($newLimit < 0) {
            throw new BufferException("New Limit < 0");
        }
        $this->limit = $newLimit;
        if ($this->position > $this->limit) {
            $this->position = $this->limit;
        }
    }

    /**
     * Returns this buffer's limit.
     *
     * @return int The limit of this buffer
     */
    public final function size()
    {
        return $this->limit;
    }

    /**
     * Modifies this buffer's byte order.
     *
     * @param string $order The new byte order, either Buffer::BIG_ENDIAN or Buffer::LITTLE_ENDIAN
     * @see Buffer::BIG_ENDIAN
     * @see Buffer::LITTLE_ENDIAN
     */
    public final function setOrder($order)
    {
        $this->order = $order == self::LITTLE_ENDIAN ? $order : self::BIG_ENDIAN;
    }

    /**
     * Retrieves this buffer's byte order.
     *
     * The byte order is used when reading or writing multibyte values, and
     * when creating buffers that are views of this byte buffer.  The order of
     * a newly-created byte buffer is always Buffer::BIG_ENDIAN
     *
     * @return string This buffer's byte order
     * @see Buffer::BIG_ENDIAN
     * @see Buffer::LITTLE_ENDIAN
     */
    public final function order()
    {
        return $this->order;
    }

    /**
     * Buffer's byte order is Buffer::LITTLE_ENDIAN
     *
     * @return bool
     * @see Buffer::BIG_ENDIAN
     * @see Buffer::LITTLE_ENDIAN
     */
    protected final function isOrderLE()
    {
        return $this->order === self::LITTLE_ENDIAN;
    }

    /**
     * @param boolean $isReadOnly
     */
    public function setReadOnly($isReadOnly)
    {
        $this->isReadOnly = $isReadOnly;
    }

    /**
     * @return boolean
     */
    public final function isReadOnly()
    {
        return $this->isReadOnly;
    }

    /**
     * Relative get method.
     * Reads the string at this buffer's current position, and then increments the position.
     *
     * @param $length
     * @return string The strings at the buffer's current position
     * @throws BufferException
     */
    abstract protected function get($length);

    /**
     * skip count bytes
     * @param int $count
     */
    public function skip($count)
    {
        $this->setPosition($this->position() + $count);
    }

    /**
     * skip 1 byte
     */
    public function skipByte()
    {
        $this->skip(1);
    }

    /**
     * skip short (2 bytes)
     */
    public function skipShort()
    {
        $this->skip(2);
    }

    /**
     * skip int (4 bytes)
     */
    public function skipInt()
    {
        $this->skip(4);
    }

    /**
     * skip long (8 bytes)
     */
    public function skipLong()
    {
        $this->skip(8);
    }

    /**
     * @return bool
     * @throws BufferException
     */
    public function getBoolean()
    {
        return (bool)$this->getUnsignedByte();
    }

    /**
     * @return int
     * @throws BufferException
     */
    public function getByte()
    {
        return Cast::toByte($this->getUnsignedByte());
    }

    /**
     * @return int
     * @throws BufferException
     */
    public function getUnsignedByte()
    {
        return current(unpack('C', $this->get(1)));
    }

    /**
     * @return int
     * @throws BufferException
     */
    public function getShort()
    {
        return Cast::toShort($this->getUnsignedShort());
    }

    /**
     * @return int
     * @throws BufferException
     */
    public function getUnsignedShort()
    {
        return current(unpack($this->isOrderLE() ? 'v' : 'n', $this->get(2)));
    }

    /**
     * @return int
     * @throws BufferException
     */
    public function getInt()
    {
        return Cast::toInt($this->getUnsignedInt());
    }

    /**
     * @return int
     * @throws BufferException
     */
    public function getUnsignedInt()
    {
        return current(unpack($this->isOrderLE() ? 'V' : 'N', $this->get(4)));
    }

    /**
     * @return string|int
     * @throws BufferException
     */
    public function getLong()
    {
        $data = $this->get(8);
        if (version_compare(PHP_VERSION, '5.6.3') >= 0) {
            return current(unpack($this->isOrderLE() ? 'P' : 'J', $data));
        }
        if ($this->isOrderLE()) {
            $unpack = unpack('Va/Vb', $data);
            return $unpack['a'] + ($unpack['b'] << 32);
        }
        else{
            $unpack = unpack('Na/Nb', $data);
            return ($unpack['a'] << 32) | $unpack['b'];
        }
    }

    /**
     * @param $size int
     * @return string
     * @throws BufferException
     */
    public function getString($size)
    {
        if ($size > 0) {
            return $this->get($size);
        }
        return "";
    }

    /**
     * @param $size int
     * @return string
     * @throws BufferException
     */
    public function getArrayBytes($size)
    {
        if ($size > 0) {
            return array_values(
                unpack('c*', $this->get($size))
            );
        }
        return array();
    }

    /**
     * @return string
     * @throws BufferException
     */
    public function getUTF()
    {
        $size = $this->getUnsignedShort();
        if ($size > 0) {
            $string = $this->getString($size);
            return $string;
        }
        return "";
    }

    /**
     * @param $length int
     * @return string
     * @throws BufferException
     */
    public function getUTF16($length)
    {
        if ($length > 0) {
            return implode('', array_map('chr', array_values(unpack('S*', $this->get($length << 1)))));
        }
        return "";
    }


    /**
     * @param bool $bool
     * @return string
     * @throws BufferException
     */
    protected function writeBoolean($bool)
    {
        if ($bool === null) {
            throw new BufferException("null boolean");
        }
        return pack('c', $bool ? 1 : 0);
    }

    /**
     * @param int|string $byte
     * @return string
     * @throws BufferException
     */
    protected function writeByte($byte)
    {
        if ($byte === null) {
            throw new BufferException("null byte");
        }
        return pack('c', $byte);
    }

    /**
     * @param int|string $v
     * @return string
     * @throws BufferException
     */
    protected function writeShort($v)
    {
        if ($v === null) {
            throw new BufferException("null short");
        }
        return pack($this->isOrderLE() ? 'v' : 'n', $v);
    }

    /**
     * @param int|string $v
     * @return string
     * @throws BufferException
     */
    protected function writeInt($v)
    {
        if ($v === null) {
            throw new BufferException("null int");
        }
        return pack($this->isOrderLE() ? 'V' : 'N', $v);
    }

    /**
     * @param string $string
     * @return string
     */
    protected function writeString($string)
    {
        return $string;
    }

    /**
     * @param array $bytes
     * @return string
     */
    protected function writeArrayBytes(array $bytes)
    {
        return call_user_func_array("pack", array_merge(array('c*'), $bytes));
    }

    /**
     * @param int|string $v
     * @return string
     * @throws BufferException
     */
    protected function writeLong($v)
    {
        if ($v === null) {
            throw new BufferException("null long");
        }
        if (version_compare(PHP_VERSION, '5.6.3') >= 0) {
            return pack($this->isOrderLE() ? "P" : "J", $v);
        }

        $left = 0xffffffff00000000;
        $right = 0x00000000ffffffff;
        if($this->isOrderLE()) {
            $r = ($v & $left) >> 32;
            $l = $v & $right;
            return pack('VV', $l, $r);
        }
        else{
            $l = ($v & $left) >>32;
            $r = $v & $right;
            return pack('NN', $l, $r);
        }
    }

    /**
     * Analog Java DataOutputStream.writeUTF
     *
     * @param string $str
     * @return string
     * @throws BufferException
     */
    protected function writeUTF($str)
    {
        if ($str === null) {
            throw new BufferException("null str");
        }
        $bytes = unpack('c*', $str);
        $length = sizeof($bytes);
        array_unshift($bytes, 'c*');
        return $this->writeShort($length) . call_user_func_array("pack", $bytes);
    }

    /**
     * @param string $string
     * @return string
     * @throws BufferException
     */
    protected function writeUTF16($string)
    {
        if ($string === null) {
            throw new BufferException("null UTF16");
        }
        $args = array_map('ord', str_split($string));
        array_unshift($args, 'S*');
        return call_user_func_array('pack', $args);
    }

    /**
     * @param Buffer|string $buffer
     * @throws BufferException
     */
    abstract public function insert($buffer);

    /**
     * Insert boolean value
     *
     * @param $bool
     * @throws BufferException
     */
    public function insertBoolean($bool)
    {
        $this->insert($this->writeBoolean($bool));
    }

    /**
     * Insert byte (-128 >= byte <= 127)
     *
     * @param int|string $byte
     * @throws BufferException
     */
    public function insertByte($byte)
    {
        $this->insert($this->writeByte($byte));
    }

    /**
     * Insert short value (-32768 >= short <= 32767)
     *
     * @param int|string $v
     * @throws BufferException
     */
    public function insertShort($v)
    {
        $this->insert($this->writeShort($v));
    }

    /**
     * Insert integer value (-2147483648 >= int <= 2147483647)
     *
     * @param int|string $v
     * @throws BufferException
     */
    public function insertInt($v)
    {
        $this->insert($this->writeInt($v));
    }

    /**
     * Insert long value (-9223372036854775808 >= long <= 9223372036854775807)
     *
     * @param int|string $v
     * @throws BufferException
     */
    public function insertLong($v)
    {
        $this->insert($this->writeLong($v));
    }

    /**
     * Insert string
     *
     * @param string $string
     * @throws BufferException
     */
    public function insertString($string)
    {
        $this->insert($this->writeString($string));
    }

    /**
     * Insert array bytes
     *
     * @param array $bytes
     * @throws BufferException
     */
    public function insertArrayBytes(array $bytes)
    {
        $this->insert($this->writeArrayBytes($bytes));
    }

    /**
     * Insert UTF string (Format - java DataOutputStream.writeUTF)
     *
     * @param string $str
     * @throws BufferException
     */
    public function insertUTF($str)
    {
        $this->insert($this->writeUTF($str));
    }

    /**
     * Insert UTF16 string
     *
     * @param string $str
     * @throws BufferException
     */
    public function insertUTF16($str)
    {
        $this->insert($this->writeUTF16($str));
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
    abstract public function put($buffer);

    /**
     * Put boolean value
     *
     * @param $bool
     * @throws BufferException
     */
    public function putBoolean($bool)
    {
        $this->put($this->writeBoolean($bool));
    }

    /**
     * Put byte (-128 >= byte <= 127)
     *
     * @param int|string $byte
     * @throws BufferException
     */
    public function putByte($byte)
    {
        $this->put($this->writeByte($byte));
    }

    /**
     * Put short value (-32768 >= short <= 32767)
     *
     * @param int|string $v
     * @throws BufferException
     */
    public function putShort($v)
    {
        $this->put($this->writeShort($v));
    }

    /**
     * Put integer value (-2147483648 >= int <= 2147483647)
     *
     * @param int|string $v
     * @throws BufferException
     */
    public function putInt($v)
    {
        $this->put($this->writeInt($v));
    }

    /**
     * Put long value (-9223372036854775808 >= long <= 9223372036854775807)
     *
     * @param int|string $v
     * @throws BufferException
     */
    public function putLong($v)
    {
        $this->put($this->writeLong($v));
    }

    /**
     * Put string
     *
     * @param string $string
     * @throws BufferException
     */
    public function putString($string)
    {
        $this->put($this->writeString($string));
    }

    /**
     * Put array bytes
     *
     * @param array $bytes
     * @throws BufferException
     */
    public function putArrayBytes(array $bytes)
    {
        $this->put($this->writeArrayBytes($bytes));
    }

    /**
     * Put UTF string (Format - java DataOutputStream.writeUTF)
     *
     * @param string $str
     * @throws BufferException
     */
    public function putUTF($str)
    {
        $this->put($this->writeUTF($str));
    }

    /**
     * Put UTF16 string
     *
     * @param string $str
     * @throws BufferException
     */
    public function putUTF16($str)
    {
        $this->put($this->writeUTF16($str));
    }

    /**
     * @param Buffer|string $buffer
     * @param int $length remove length bytes
     * @throws BufferException
     */
    abstract public function replace($buffer, $length);

    /**
     * Replace boolean value
     *
     * @param bool $bool
     * @param int $length
     * @throws BufferException
     */
    public function replaceBoolean($bool, $length)
    {
        $this->replace($this->writeBoolean($bool), $length);
    }

    /**
     * Replace byte (-128 >= byte <= 127)
     *
     * @param int|string $byte
     * @param int $length
     * @throws BufferException
     */
    public function replaceByte($byte, $length)
    {
        $this->replace($this->writeByte($byte), $length);
    }

    /**
     * Replace short value (-32768 >= short <= 32767)
     *
     * @param int|string $v
     * @param int $length
     * @throws BufferException
     */
    public function replaceShort($v, $length)
    {
        $this->replace($this->writeShort($v), $length);
    }

    /**
     * Replace integer value (-2147483648 >= int <= 2147483647)
     *
     * @param int|string $v
     * @param int $length
     * @throws BufferException
     */
    public function replaceInt($v, $length)
    {
        $this->replace($this->writeInt($v), $length);
    }

    /**
     * Replace long value (-9223372036854775808 >= long <= 9223372036854775807)
     *
     * @param int|string $v
     * @param int $length
     * @throws BufferException
     */
    public function replaceLong($v, $length)
    {
        $this->replace($this->writeLong($v), $length);
    }

    /**
     * Replace string
     *
     * @param string $string
     * @param int $length
     * @throws BufferException
     */
    public function replaceString($string, $length)
    {
        $this->replace($this->writeString($string), $length);
    }

    /**
     * Insert array bytes
     *
     * @param array $bytes
     * @param int $length
     * @throws BufferException
     */
    public function replaceArrayBytes(array $bytes, $length)
    {
        $this->replace($this->writeArrayBytes($bytes), $length);
    }

    /**
     * Replace UTF string (Format - java DataOutStream.writeUTF)
     *
     * @param string $str
     * @param int $length
     * @throws BufferException
     */
    public function replaceUTF($str, $length)
    {
        $this->replace($this->writeUTF($str), $length);
    }

    /**
     * Replace UTF16 string
     *
     * @param string $str
     * @param int $length
     * @throws BufferException
     */
    public function replaceUTF16($str, $length)
    {
        $this->replace($this->writeUTF16($str), $length);
    }

    /**
     * @param int $length
     * @throws BufferException
     */
    abstract public function remove($length);

    /**
     * Truncate data
     */
    abstract public function truncate();

    /**
     * @return string
     */
    abstract public function toString();

}