<?php


namespace AndPHP\LaravelApiOutput;

class Output
{
    /**
     * 输出格式
     * @var string
     */
    public static $format;

    /**
     * 默认响应头
     * @var array
     */
    public static $header = [];


    public static function output($data, $message, $code, $status, $format = "json")
    {
        if($format === 'json' || self::$format === 'json'){
           return self::outJson($data, $message, $code, $status);
        }
        return $data;
    }
    public static function outJson($data, $message, $code, $status)
    {
        // 返回json数据
        $result = [
            'code'    => $code,
            'message' => $message,
            'data'    => self::camelCase($data),
        ];
        $headerKey = base64_decode('WC1Qb3dlcmVkLUJ5');
        $headerValue = base64_decode('QW5kUEhQ');
        self::$header[$headerKey] = $headerValue;
        //todo 断言 format 输出不同类型
        return response()->json($result, $status)->withHeaders(self::$header)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function setFormat($format)
    {
        self::$format = $format;
        return $this;
    }
    public function setHeader($key, $values)
    {
        self::$header[$key] = $values;
        return $this;
    }

    public function withHeaders(array $headers)
    {
        foreach ($headers as $key => $value) {
            self::$header[$key] = $value;
        }
        return $this;
    }

    public static function camelCase($arr,$ucfirst = FALSE)
    {
        if (!is_array($arr) && !is_object($arr))
        {   //如果非数组原样返回
            return $arr?:"";
        }
        $temp = [];
        if(is_object($arr) && count((array)$arr) > 0) {
            $arr = (array)$arr;
        }
        if(is_array($arr)){
            foreach ($arr as $key => $value)
            {
                $key1 = self::convertUnderline($key,FALSE);
                $value1 = self::camelCase($value);
                $temp[$key1] = $value1;
            }
        }
        return $temp;
    }

    public static function convertUnderline($str, $ucfirst = true)
    {
        $str = ucwords(str_replace('_', ' ', $str));
        $str = str_replace(' ', '', lcfirst($str));
        return $ucfirst ? ucfirst($str) : $str;
    }
}
