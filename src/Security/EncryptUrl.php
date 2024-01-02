<?php

declare(strict_types=1);

namespace Effectra\Core\Security;

use Effectra\Core\Exceptions\ExpiredTimeException;
use Effectra\Http\Message\Uri;
use Psr\Http\Message\UriInterface;

/**
 * Class EncryptUrl
 *
 * Represents a utility class for encrypting and decrypting query parameters sent via URL.
 */
class EncryptUrl
{

    public function __construct(private readonly string $secretKey)
    {
       
    }

    /**
     * Generates an encrypted URL with the provided parameters.
     *
     * @param mixed     $data          The data to be encrypted.
     * @param \DateTime $expiration    The expiration date and time for the encrypted URL.
     * @param string    $url           The URL to append the encrypted data as query parameters.
     * @param string    $secretKey     The secret key used for encryption.
     *
     * @return UriInterface The generated encrypted URL.
     */
    public function set(mixed $data, \DateTime $expiration, string $url):UriInterface
    {
        $iv = openssl_random_pseudo_bytes(16);

        $jsonData = json_encode($data);

        $encryptedData = openssl_encrypt($jsonData, 'AES-256-CBC', $this->secretKey, 0, substr(md5($this->secretKey), 0, 16));

        $expirationTimestamp = $expiration->getTimestamp();

        $data = base64_encode($iv . $encryptedData);

        return (new Uri($url))->withQuery(http_build_query(['hash' => $data, 'expiration' => $expirationTimestamp]));
    }

    /**
     * parse and decrypts an encrypted URL.
     *
     * @param UriInterface|string $encryptedUrl  The encrypted URL to validate and decrypt.
     * @param string $secretKey     The secret key used for decryption.
     *
     * @return mixed The decrypted data.
     * @throws \RuntimeException If the encrypted URL is invalid.
     * @throws \ExpiredTimeException If the encrypted URL has expired.
     */
    public function get(UriInterface|string $encryptedUrl): mixed
    {
        if(is_string($encryptedUrl)){
            $encryptedUrl = new Uri($encryptedUrl);
        }
        parse_str($encryptedUrl->getQuery(), $queryParams);

        if (!isset($queryParams['hash'], $queryParams['expiration'])) {
            throw new \RuntimeException('Invalid encrypted URL: missing required parameters.');
        }

        $data = $queryParams['hash'];
       
        $encrypted = base64_decode($data);
        // $iv = substr($encrypted, 0, 16);
        $encryptedData = substr($encrypted, 16);
        $expirationTimestamp = (int) $queryParams['expiration'];

        if ($expirationTimestamp < time()) {
            throw new ExpiredTimeException('Encrypted URL has expired.');
        }

        $decryptedData = openssl_decrypt($encryptedData, 'AES-256-CBC', $this->secretKey, 0, substr(md5($this->secretKey), 0, 16));

        $data = json_decode($decryptedData, true);

        return $data;
    }
}
