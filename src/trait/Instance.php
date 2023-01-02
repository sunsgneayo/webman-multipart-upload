<?php

declare(strict_types=1);
namespace Sunsgne\WebmanMultipartUpload\trait;


use InvalidArgumentException;

/**
 * @Time 2022/12/26 15:00
 * @author zhulianyou
 */
trait Instance
{

    /**
     * 单例实体
     *
     * @var null
     */
    protected static $instance = null;

    /**
     * 获取单例
     *
     * @param array $options 初始化参数
     */
    public static function instance(array $options = [])
    {
        if (is_null(static::$instance)) {
            static::$instance = new static($options);
        }
        return static::$instance;
    }

    /**
     * 静态调用支持，以"_"开头加方法名调用非静态方法
     *
     * @param  string $method 方法名
     * @param array $params 参数
     * @return mixed
     *@see 例子： className::_methodName()
     */
    public static function __callStatic(string $method, array $params)
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        $call = mb_substr($method, 1);
        if (0 === mb_strpos($method, '_') && is_callable([static::$instance, $call])) {
            return call_user_func_array([static::$instance, $call], (array) $params);
        } else {
            throw new InvalidArgumentException("method not found => " . $method);
        }
    }

}