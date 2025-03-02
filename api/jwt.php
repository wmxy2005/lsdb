<?php
 
class JWT
{
    private static $secretKey = 'C8JoDqG2nRuQxwVA'; // 加密使用的秘钥
    private static $algo = 'HS256';  // 使用的算法
 
    /**
     * 生成JWT
     * 
     * @param array $payload 数据负载
     * @return string
     */
    public static function generateJWT($payload)
    {
        // 1. 生成header
        $header = json_encode(['alg' => self::$algo, 'typ' => 'JWT']);
        $base64Header = self::base64UrlEncode($header);
 
        // 2. 生成payload
        $base64Payload = self::base64UrlEncode(json_encode($payload));
 
        // 3. 生成signature
        $signature = hash_hmac('sha256', "$base64Header.$base64Payload", self::$secretKey, true);
        $base64Signature = self::base64UrlEncode($signature);
 
        // 4. 组合JWT
        $jwt = "$base64Header.$base64Payload.$base64Signature";
 
        return $jwt;
    }
 
    /**
     * 验证JWT
     * 
     * @param string $token JWT令牌
     * @return array|bool 如果验证成功返回payload，否则返回false
     */
    public static function verifyJWT($token)
    {
        // 1. 拆分JWT
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }
 
        list($base64Header, $base64Payload, $base64Signature) = $parts;
 
        // 2. 解码Header和Payload
        $header = json_decode(self::base64UrlDecode($base64Header), true);
        $payload = json_decode(self::base64UrlDecode($base64Payload), true);
        $signature = self::base64UrlDecode($base64Signature);
 
        // 3. 验证签名
        $expectedSignature = hash_hmac('sha256', "$base64Header.$base64Payload", self::$secretKey, true);
        if (!hash_equals($signature, $expectedSignature)) {
            return false;
        }
 
        // 4. 验证过期时间
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false;
        }
 
        return $payload;
    }
 
    /**
     * Base64URL编码
     * 
     * @param string $data
     * @return string
     */
    private static function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
 
    /**
     * Base64URL解码
     * 
     * @param string $data
     * @return string
     */
    private static function base64UrlDecode($data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}

/* // 测试JWT生成
$payload = [
    'user_id' => 123,
    'username' => 'john_doe',
    'exp' => time() + 3600  // 过期时间1小时
];
 
$jwt = JWT::generateJWT($payload);
echo "Generated JWT: " . $jwt . PHP_EOL;
 
// 测试JWT验证
$decodedPayload = JWT::verifyJWT($jwt);
if ($decodedPayload) {
    echo "JWT is valid! Payload: " . print_r($decodedPayload, true) . PHP_EOL;
} else {
    echo "Invalid JWT!" . PHP_EOL;
} */
?>