<?php
namespace Nelexa\Buffer;


class Cast
{
    /**
     * A constant holding the minimum value a byte can
     * have, -2^7.
     */
    const BYTE_MIN_VALUE = -128;

    /**
     * A constant holding the maximum value a byte can
     * have, 2^7-1.
     */
    const BYTE_MAX_VALUE = 127;

    /**
     * A constant holding the minimum value a short can
     * have, -2^15.
     */
    const SHORT_MIN_VALUE = -32768;

    /**
     * A constant holding the maximum value a short can
     * have, 2^15-1.
     */
    const SHORT_MAX_VALUE = 32767;

    /**
     * A constant holding the minimum value an int can
     * have, -2^31.
     */
    const INTEGER_MIN_VALUE = -2147483648;

    /**
     * A constant holding the maximum value an int can
     * have, 2^31-1.
     */
    const INTEGER_MAX_VALUE = 2147483647;

    /**
     * A constant holding the minimum value a long can
     * have, -2^63.
     */
    const LONG_MIN_VALUE = -9223372036854775808;

    /**
     * A constant holding the maximum value a long can
     * have, 2^63-1.
     */
    const LONG_MAX_VALUE = 9223372036854775807;

    /**
     * Cast to byte (-128 >= byte <= 127)
     *
     * @param int $i
     * @return int
     * @throws BufferException
     */
    public static function toByte($i)
    {
        $i = self::toUnsignedByte($i);
        if ($i < 128) return $i;
        return $i - 256;
    }

    /**
     * Cast to unsigned byte (0 >= short <= 255)
     *
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
     * Cast to short (-32768 >= short <= 32767)
     *
     * @param int $i
     * @return int
     * @throws BufferException
     */
    public static function toShort($i)
    {
        $i = self::toUnsignedShort($i);
        if ($i < 32768) return $i;
        return $i - 65536;
    }

    /**
     * Cast to unsigned short (0 >= int <= 65535)
     *
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
     * Cast to int (-2147483648 >= int <= 2147483647)
     *
     * @param int $i
     * @return int
     * @throws BufferException
     */
    public static function toInt($i)
    {
        $i = self::toUnsignedInt($i);
        if ($i < 2147483648) return $i;
        return $i - 4294967296;
    }

    /**
     * Cast to unsigned int (0 >= long <= 4294967296)
     *
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
     * Cast to long (-9223372036854775808 >= long <= 9223372036854775807)
     *
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