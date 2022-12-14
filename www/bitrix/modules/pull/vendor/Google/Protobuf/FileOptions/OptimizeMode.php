<?php
/**
 * Generated by Protobuf protoc plugin.
 *
 * File descriptor : descriptor.proto
 */


namespace google\protobuf\FileOptions;

/**
 * Protobuf enum : google.protobuf.FileOptions.OptimizeMode
 */
class OptimizeMode extends \Protobuf\Enum
{

    /**
     * SPEED = 1
     */
    const SPEED_VALUE = 1;

    /**
     * CODE_SIZE = 2
     */
    const CODE_SIZE_VALUE = 2;

    /**
     * LITE_RUNTIME = 3
     */
    const LITE_RUNTIME_VALUE = 3;

    /**
     * @var \google\protobuf\FileOptions\OptimizeMode
     */
    protected static $SPEED = null;

    /**
     * @var \google\protobuf\FileOptions\OptimizeMode
     */
    protected static $CODE_SIZE = null;

    /**
     * @var \google\protobuf\FileOptions\OptimizeMode
     */
    protected static $LITE_RUNTIME = null;

    /**
     * @return \google\protobuf\FileOptions\OptimizeMode
     */
    public static function SPEED()
    {
        if (self::$SPEED !== null) {
            return self::$SPEED;
        }

        return self::$SPEED = new self('SPEED', self::SPEED_VALUE);
    }

    /**
     * @return \google\protobuf\FileOptions\OptimizeMode
     */
    public static function CODE_SIZE()
    {
        if (self::$CODE_SIZE !== null) {
            return self::$CODE_SIZE;
        }

        return self::$CODE_SIZE = new self('CODE_SIZE', self::CODE_SIZE_VALUE);
    }

    /**
     * @return \google\protobuf\FileOptions\OptimizeMode
     */
    public static function LITE_RUNTIME()
    {
        if (self::$LITE_RUNTIME !== null) {
            return self::$LITE_RUNTIME;
        }

        return self::$LITE_RUNTIME = new self('LITE_RUNTIME', self::LITE_RUNTIME_VALUE);
    }

    /**
     * @param int $value
     * @return \google\protobuf\FileOptions\OptimizeMode
     */
    public static function valueOf($value)
    {
        switch ($value) {
            case 1: return self::SPEED();
            case 2: return self::CODE_SIZE();
            case 3: return self::LITE_RUNTIME();
            default: return null;
        }
    }


}
