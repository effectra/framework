<?php

declare(strict_types=1);

namespace Effectra\Core\Security;

/**
 * Class EncryptUrl
 *
 * Represents a utility class for encrypting and decrypting query parameters sent via URL.
 */
class EncryptUrl
{

    /**
     * Generates an encrypted URL with the provided parameters.
     *
     * @param mixed     $data          The data to be encrypted.
     * @param \DateTime $expiration    The expiration date and time for the encrypted URL.
     * @param string    $url           The URL to append the encrypted data as query parameters.
     * @param string    $secretKey     The secret key used for encryption.
     *
     * @return string The generated encrypted URL.
     */
    public function generate(mixed $data, \DateTime $expiration, string $url, string $secretKey)
    {
        // Convert data to JSON format
        $jsonData = json_encode($data);

        // Encrypt the JSON data using AES-256-CBC encryption
        $encryptedData = openssl_encrypt($jsonData, 'AES-256-CBC', $secretKey, 0, substr(md5($secretKey), 0, 16));

        // Generate the expiration timestamp
        $expirationTimestamp = $expiration->getTimestamp();

        // Append encrypted data and expiration timestamp as query parameters to the URL
        $encryptedUrl = $url . '?' . http_build_query(['data' => $encryptedData, 'expiration' => $expirationTimestamp]);

        return $encryptedUrl;
    }

    /**
     * Validates and decrypts an encrypted URL.
     *
     * @param string $encryptedUrl  The encrypted URL to validate and decrypt.
     * @param string $secretKey     The secret key used for decryption.
     *
     * @return mixed The decrypted data.
     * @throws \RuntimeException If the encrypted URL is invalid or has expired.
     */
    public function validate(string $encryptedUrl, string $secretKey): mixed
    {
        // Parse the URL to retrieve query parameters
        $urlParts = parse_url($encryptedUrl);
        parse_str($urlParts['query'], $queryParams);

        // Ensure the required parameters are present
        if (!isset($queryParams['data'], $queryParams['expiration'])) {
            throw new \RuntimeException('Invalid encrypted URL: missing required parameters.');
        }

        // Retrieve encrypted data and expiration timestamp
        $encryptedData = $queryParams['data'];
        $expirationTimestamp = (int) $queryParams['expiration'];

        // Check if the URL has expired
        if ($expirationTimestamp < time()) {
            throw new \RuntimeException('Encrypted URL has expired.');
        }

        // Decrypt the data using AES-256-CBC decryption
        $decryptedData = openssl_decrypt($encryptedData, 'AES-256-CBC', $secretKey, 0, substr(md5($secretKey), 0, 16));

        // Parse the JSON data
        $data = json_decode($decryptedData, true);

        // Return the decrypted data
        return $data;
    }
}
