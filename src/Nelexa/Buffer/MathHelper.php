<?php
namespace Nelexa\Buffer;

class MathHelper
{
    /**
     * Add two numbers
     *
     * @param int|string $left_operand
     * @param int|string $right_operand
     * @return string|int
     */
    public static function add($left_operand, $right_operand)
    {
        if (extension_loaded('gmp')) {
            return gmp_strval(gmp_add($left_operand, $right_operand));
        } else if (extension_loaded('bcmath')) {
            return bcadd($left_operand, $right_operand);
        }
        return $left_operand + $right_operand;
    }

    /**
     * Subtract numbers
     *
     * @param int|string $left_operand
     * @param int|string $right_operand
     * @return string|int
     */
    public static function sub($left_operand, $right_operand)
    {
        if (extension_loaded('gmp')) {
            return gmp_strval(gmp_sub($left_operand, $right_operand));
        } else if (extension_loaded('bcmath')) {
            return bcsub($left_operand, $right_operand);
        }
        return $left_operand - $right_operand;
    }

    /**
     * Compare numbers
     *
     * @param int|string $left_operand
     * @param int|string $right_operand
     * @return int
     */
    public static function cmp($left_operand, $right_operand)
    {
        if (extension_loaded('gmp')) {
            return gmp_cmp($left_operand, $right_operand);
        } else if (extension_loaded('bcmath')) {
            return bccomp($left_operand, $right_operand);
        }
        return $left_operand > $right_operand ? 1 : ($left_operand == $right_operand ? 0 : -1);
    }

    /**
     * Modulo operation
     *
     * @param int|string $left_operand
     * @param int|string $right_operand
     * @return int|string
     */
    public static function mod($left_operand, $right_operand)
    {
        if (extension_loaded('gmp')) {
            return gmp_strval(gmp_mod($left_operand, $right_operand));
        } else if (extension_loaded('bcmath')) {
            return bcmod($left_operand, $right_operand);
        }
        return $left_operand % $right_operand;
    }

    /**
     * Divide numbers
     *
     * @param int|string $left_operand
     * @param int|string $right_operand
     * @return int|string
     */
    public static function mul($left_operand, $right_operand)
    {
        if (extension_loaded('gmp')) {
            return gmp_strval(gmp_mul($left_operand, $right_operand));
        } else if (extension_loaded('bcmath')) {
            return bcmul($left_operand, $right_operand);
        }
        return $left_operand * $right_operand;
    }

    /**
     * Divide numbers
     *
     * @param int|string $left_operand
     * @param int|string $right_operand
     * @return int|string
     */
    public static function div($left_operand, $right_operand)
    {
        if (extension_loaded('gmp')) {
            return gmp_strval(gmp_div_q($left_operand, $right_operand, GMP_ROUND_MINUSINF));
        } else if (extension_loaded('bcmath')) {
            return (string)floor(bcdiv($left_operand, $right_operand));
        }
        return (string)floor($left_operand / $right_operand);
    }

    /**
     * Raise number into power
     *
     * @param int|string $left_operand
     * @param int|string $right_operand
     * @return int|string
     */
    public static function pow($left_operand, $right_operand)
    {
        if (extension_loaded('gmp')) {
            return gmp_strval(gmp_pow($left_operand, $right_operand));
        } else if (extension_loaded('bcmath')) {
            return bcpow($left_operand, $right_operand);
        }
        return pow($left_operand, $right_operand);
    }

    /**
     * Bitwise AND
     *
     * @param int|string $left_operand
     * @param int|string $right_operand
     * @return int|string
     */
    public static function bitwiseAnd($left_operand, $right_operand)
    {
        if (extension_loaded('gmp')) {
            return gmp_strval(gmp_and($left_operand, $right_operand));
        }
        return $left_operand & $right_operand;
    }

    /**
     * Bitwise OR
     *
     * @param int|string $left_operand
     * @param int|string $right_operand
     * @return int|string
     */
    public static function bitwiseOr($left_operand, $right_operand)
    {
        if (extension_loaded('gmp')) {
            return gmp_strval(gmp_or($left_operand, $right_operand));
        }
        return $left_operand | $right_operand;
    }

    /**
     * Bitwise XOR
     *
     * @param int|string $left_operand
     * @param int|string $right_operand
     * @return int|string
     */
    public static function bitwiseXor($left_operand, $right_operand)
    {
        if (extension_loaded('gmp')) {
            return gmp_strval(gmp_xor($left_operand, $right_operand));
        }
        return $left_operand ^ $right_operand;
    }

    /**
     * @param int|string $x
     * @param int|string $n
     * @return int
     * @see https://stackoverflow.com/questions/14132787/php-left-bit-shift-issue-on-32-bit
     */
    public static function leftShift32($x, $n)
    {
        if (extension_loaded('gmp')) {
            // optimisation gmp res
            return self::castToInt(gmp_strval(gmp_mul($x, gmp_pow(2, gmp_strval(gmp_mod($n, 32))))));
        }
        return self::castToInt(self::mul($x, self::pow(2, self::mod($n, 32))));
    }

    /**
     * @param int|string $x
     * @param int|string $n
     * @return int|string long value
     * @see https://stackoverflow.com/questions/14132787/php-left-bit-shift-issue-on-32-bit
     */
    public static function leftShift64($x, $n)
    {
        if (extension_loaded('gmp')) {
            // optimisation gmp res
            return self::castToLong(gmp_strval(gmp_mul($x, gmp_pow(2, gmp_strval(gmp_mod($n, 64))))));
        }
        return self::castToLong(self::mul($x, self::pow(2, self::mod($n, 64))));
    }

    /**
     * @param int|string $x
     * @param int|string $n
     * @return int
     * @see https://stackoverflow.com/questions/14132787/php-left-bit-shift-issue-on-32-bit
     */
    public static function rightShift32($x, $n)
    {
        if (extension_loaded('gmp')) {
            // optimisation gmp res
            return self::castToInt(gmp_strval(gmp_div_q($x, gmp_pow(2, gmp_strval(gmp_mod($n, 32))), GMP_ROUND_MINUSINF)));
        }
        return self::castToInt(self::div($x, self::pow(2, self::mod($n, 32))));
    }

    /**
     * @param int|string $x
     * @param int|string $n
     * @return int|string
     */
    public static function rightShift64($x, $n)
    {
        if (extension_loaded('gmp')) {
            // optimisation gmp res
            return gmp_strval(gmp_div_q($x, gmp_pow(2, gmp_strval(gmp_mod($n, 64))), GMP_ROUND_MINUSINF));
        }
        return self::div($x, self::pow(2, self::mod($n, 64)));
    }

    /**
     * java equalient unsigned right shift >>>
     * Algorithm @link http://wiki.secondlife.com/wiki/Right_Shift
     *
     * @param int|string $a
     * @param int|string $b
     * @return int
     */
    public static function unsignedRightShift32($a, $b)
    {
        if ($b == 0) {
            return $a;
        }
        return self::castToInt(self::sub(self::rightShift32(self::bitwiseAnd($a, '2147483647'), $b), (self::rightShift32(self::bitwiseAnd($a, '-2147483648'), $b))));
    }

    /**
     * Alg Java: ((a & Long.MAX_VALUE) >> b) - ((a & Long.MIN_VALUE) >>> b)
     *
     * @param int|string $a
     * @param int|string $b
     * @return int|string
     */
    public static function unsignedRightShift64($a, $b)
    {
        if ($b == 0) {
            return $a;
        }
        if (extension_loaded('gmp')) {
            // optimisation gmp res
            return self::castToLong(gmp_strval(
                gmp_sub(
                    gmp_div_q(
                        gmp_and($a, '9223372036854775807'),
                        gmp_pow(2, gmp_strval(gmp_mod($b, 64))),
                        GMP_ROUND_MINUSINF
                    ),
                    gmp_div_q(
                        gmp_and($a, '-9223372036854775808'),
                        gmp_pow(2, gmp_strval(gmp_mod($b, 64))),
                        GMP_ROUND_MINUSINF
                    )
                )
            ));
        }
        return self::castToLong(self::sub(self::rightShift64(self::bitwiseAnd($a, '9223372036854775807'), $b), (self::rightShift64(self::bitwiseAnd($a, '-9223372036854775808'), $b))));
    }

    /**
     * Cast to byte (Java)
     * Byte.MAX_VALUE = 127
     * Byte.MIN_VALUE = -128
     *
     * @param int|string $i
     * @return int
     * @throws \Exception
     */
    public static function castToByte($i)
    {
        if (!is_numeric($i)) {
            throw new \Exception("Cast To Byte Error - param \$i no numeric");
        }
        $i = (int)($i & 0xff);
        if ($i > 127) {
            return (($i + 128) & 0xff) - 128;
        } elseif ($i < -128) {
            return $i & 0x7f;
        }
        return $i;
//        return pack('c', $i);
//        if (extension_loaded('gmp')) {
//            // optimisation gmp res
//            if (gmp_cmp($i, 127) > 0) {
//                return gmp_strval(gmp_sub(gmp_mod(gmp_add($i, 128), 256), 128));
//            } elseif (gmp_cmp($i, -128) < 0) {
//                return gmp_strval(gmp_add(gmp_mod(gmp_sub($i, 128), 256), -128));
//            }
//        } else {
//            if (self::cmp($i, 127) > 0) {
//                return self::sub(self::mod(self::add($i, 128), 256), 128);
//            } elseif (self::cmp($i, -128) < 0) {
//                return self::add(self::mod(self::sub($i, 128), 256), -128);
//            }
//        }
//        return $i;
    }

    /**
     * Cast to short (Java)
     * Short.MAX_VALUE = 32767
     * Short.MIN_VALUE = -32768
     *
     * @param string|int $i
     * @return int short
     * @throws \Exception
     */
    public static function castToShort($i)
    {
        if (!is_numeric($i)) {
            throw new \Exception("Cast To Short Error - param \$i no numeric");
        }
        if (extension_loaded('gmp')) {
            // optimisation gmp res
            if (gmp_cmp($i, 32767) > 0) {
                return gmp_strval(gmp_sub(gmp_mod(gmp_add($i, 32768), 65536), 32768));
            } elseif (gmp_cmp($i, -32768) < 0) {
                return gmp_strval(gmp_add(gmp_mod(gmp_sub($i, 32768), 65536), -32768));
            }
        } else {
            if (self::cmp($i, 32767) > 0) {
                return self::sub(self::mod(self::add($i, 32768), 65536), 32768);
            } elseif (self::cmp($i, -32768) < 0) {
                return self::add(self::mod(self::sub($i, 32768), 65536), -32768);
            }
        }
        return $i;
    }

    /**
     * Cast to int - 64 bit safe cast (Java)
     * Integer.MAX_VALUE = 2147483647
     * Integer.MIN_VALUE = -2147483648
     *
     * Sample:
     * php 32bit - var_dump((int)4278190080) => int(-16777216);
     * php 64bit - var_dump((int)4278190080) => int(-4278190080);
     * This method return only 32bit variant
     *
     * @param int|string $i
     * @return int
     * @throws \Exception
     */
    public static function castToInt($i)
    {
        if (!is_numeric($i)) {
            throw new \Exception("Cast To Int Error - param \$i no numeric");
        }
        if (extension_loaded('gmp')) {
            // optimisation gmp res
            if (gmp_cmp($i, '2147483647') > 0) {
                return gmp_strval(gmp_sub(gmp_mod(gmp_add($i, '2147483648'), '4294967296'), '2147483648'));
            } elseif (gmp_cmp($i, '-2147483648') < 0) {
                return gmp_strval(gmp_add(gmp_mod(gmp_sub($i, '2147483648'), '4294967296'), '-2147483648'));
            }
        }
        if (self::cmp($i, '2147483647') > 0) {
            $i = self::sub(self::mod(self::add($i, '2147483648'), '4294967296'), '2147483648');
        } elseif (self::cmp($i, '-2147483648') < 0) {
            $i = self::add(self::mod(self::sub($i, '2147483648'), '4294967296'), '-2147483648');
        }
        return (int)$i;
    }

    /**
     * Cast to Java Long
     * Long.MAX_VALUE = 9223372036854775807
     * Long.MIN_VALUE = -9223372036854775808
     *
     * Sample:
     *
     * PHP 32 and 64-bit:
     * $mulValue = MathHelper::mul(45521255, 852212548554445);
     * var_dump($mulValue)
     * ->    string(23) "38793784736946772228475"
     * var_dump(MathHelper::castToLong("38793784736946772228475"));
     * ->    string(18) "281949935585180027"
     *
     * Java:
     * System.out.println(45521255L * 852212548554445L);
     * ->    281949935585180027
     *
     * @param int|string $i
     * @return int|string
     * @throws \Exception
     */
    public static function castToLong($i)
    {
        if (!is_numeric($i)) {
            throw new \Exception("Cast To Long Error - param \$i no numeric");
        }
        if (extension_loaded('gmp')) {
            // optimisation gmp res
            if (gmp_cmp($i, '9223372036854775807') > 0) {
                return gmp_strval(gmp_sub(gmp_mod(gmp_add($i, '9223372036854775808'), '18446744073709551616'), '9223372036854775808'));
            } elseif (gmp_cmp($i, '-92233720368547758088') < 0) {
                return gmp_strval(gmp_add(gmp_mod(gmp_sub($i, '9223372036854775808'), '18446744073709551616'), '-9223372036854775808'));
            }
        }
        if (self::cmp($i, '9223372036854775807') > 0) {
            $i = self::sub(self::mod(self::add($i, '9223372036854775808'), '18446744073709551616'), '9223372036854775808');
        } elseif (self::cmp($i, '-9223372036854775808') < 0) {
            $i = self::add(self::mod(self::sub($i, '9223372036854775808'), '18446744073709551616'), '-9223372036854775808');
        }

        return $i;
    }
}