<?php
namespace Nelexa\Buffer;


class Cast
{
    /**
     * A constant holding the minimum value a byte can
     * have, -2<sup>7</sup>.
     */
    const BYTE_MIN_VALUE = -128;

    /**
     * A constant holding the maximum value a byte can
     * have, 2<sup>7</sup>-1.
     */
    const BYTE_MAX_VALUE = 127;

    /**
     * A constant holding the minimum value a short can
     * have, -2<sup>15</sup>.
     */
    const SHORT_MIN_VALUE = -32768;

    /**
     * A constant holding the maximum value a short can
     * have, 2<sup>15</sup>-1.
     */
    const SHORT_MAX_VALUE = 32767;

    /**
     * A constant holding the minimum value an int can
     * have, -2<sup>31</sup>.
     */
    const INTEGER_MIN_VALUE = -2147483648;

    /**
     * A constant holding the maximum value an int can
     * have, 2<sup>31</sup>-1.
     */
    const INTEGER_MAX_VALUE = 2147483647;

    /**
     * A constant holding the minimum value a long can
     * have, -2<sup>63</sup>.
     */
    const LONG_MIN_VALUE = -9223372036854775808;

    /**
     * A constant holding the maximum value a long can
     * have, 2<sup>63</sup>-1.
     */
    const LONG_MAX_VALUE = 9223372036854775807;

    /**
     * @param int $i
     * @return int
     * @throws BufferException
     */
    public static function toByte($i)
    {
        $i = self::toUnsignedByte($i);
        if ($i > static::BYTE_MAX_VALUE) {
            return -(-$i & 0xff);
        } elseif ($i < static::BYTE_MIN_VALUE) {
            return $i & static::BYTE_MIN_VALUE;
        }
        return $i;
    }

    /**
     * @param int $i
     * @return int
     * @throws BufferException
     */
    public static function toUnsignedByte($i)
    {
        if (!is_numeric($i)) {
            throw new BufferException("Cast To Byte Error - param \$i no numeric");
        }
        return (int)($i & 0xff);
    }

    /**
     * @param int $i
     * @return int
     * @throws BufferException
     */
    public static function toShort($i)
    {
        $i = self::toUnsignedShort($i);
        if ($i > static::SHORT_MAX_VALUE) {
            return -(-$i & 0xffff);
        } elseif ($i < static::SHORT_MIN_VALUE) {
            return $i & static::SHORT_MIN_VALUE;
        }
        return $i;
    }

    /**
     * @param int $i
     * @return int
     * @throws BufferException
     */
    public static function toUnsignedShort($i)
    {
        if (!is_numeric($i)) {
            throw new BufferException("Cast To Short Error - param \$i no numeric");
        }
        return (int)($i & 0xffff);
    }

    /**
     * @param int $i
     * @return int
     * @throws BufferException
     */
    public static function toInt($i)
    {
        $i = self::toUnsignedInt($i);
        if ($i > static::INTEGER_MAX_VALUE) {
            return -(-$i & 0xffffffff);
        } elseif ($i < static::INTEGER_MIN_VALUE) {
            return $i & static::INTEGER_MIN_VALUE;
        }
        return $i;
    }

    /**
     * @param int $i
     * @return int
     * @throws BufferException
     */
    public static function toUnsignedInt($i)
    {
        if (!is_numeric($i)) {
            throw new BufferException("Cast To Integer Error - param \$i no numeric");
        }
        return (int)($i & 0xffffffff);
    }

    /**
     * @param int $i
     * @return int
     * @throws BufferException
     */
    public static function toLong($i)
    {
        $i = (int)$i;
        if ($i > static::LONG_MAX_VALUE) {
            throw new BufferException('Invalid long value');
        } elseif ($i < static::LONG_MIN_VALUE) {
            throw new BufferException('Invalid long value');
        }
        return $i;
    }

}