<?php

declare(strict_types=1);

namespace Effectra\Core\Auth;



/**
 * Class SignedUrl
 *
 * Represents a utility class for generating and validating signed URLs.
 */
class SignedUrl
{
    /**
     * @var string $endpoint The endpoint to append to the base URL when generating a signed URL.
     */
    protected string $endpoint;

    /**
     * SignedUrl constructor.
     *
     * @param string $endpoint The endpoint to append to the base URL when generating a signed URL.
     */
    public function __construct(string $endpoint = 'verify')
    {
        $this->endpoint = $endpoint;
    }

    /**
     * Generates a signed URL with the provided parameters.
     *
     * @param string|int   $userId          The user ID to include in the URL route parameters.
     * @param string       $email           The email address to include in the URL route parameters.
     * @param \DateTime    $expirationDate  The expiration date and time for the signed URL.
     *
     * @return string The generated signed URL.
     */
    public function generate(string|int $userId, string $email, \DateTime $expirationDate)
    {
        // Get the expiration timestamp from the DateTime object
        $expiration = $expirationDate->getTimestamp();
    
        // Generate route parameters
        $routeParams = ['id' => $userId, 'hash' => sha1($email)];
    
        // Generate query parameters
        $queryParams = ['expiration' => $expiration];
    
        // Get the base URL from the environment
        $baseUrl = trim($_ENV['APP_URL'] ?? '', '/');
    
        // Build the URL with route and query parameters
        $url = $baseUrl . $this->endpoint . join('/', $routeParams) . http_build_query($queryParams);
    
        // Get the secret key from the environment
        $secretKey = $_ENV['APP_KEY'];
    
        // Calculate the signature using HMAC-SHA256
        $signature = hash_hmac('sha256', $url, $secretKey);
    
        // Append the signature to the query parameters
        return $baseUrl . $this->endpoint . http_build_query($queryParams + ['signature' => $signature]);
    }

    /**
     * Validates a signed URL.
     *
     * @param string $signedUrl The signed URL to validate.
     *
     * @return bool True if the signed URL is valid, false otherwise.
     * @throws \RuntimeException If the signed URL is invalid or has expired.
     */
    public function validate(string $signedUrl): bool
    {
        $urlParts = parse_url($signedUrl);
    
        // Ensure the URL contains the required components
        if (!isset($urlParts['query'])) {
            throw new \RuntimeException('Invalid signed URL: missing query parameters.');
        }
    
        // Extract the query parameters from the URL
        parse_str($urlParts['query'], $queryParams);
    
        // Ensure the required parameters are present
        if (!isset($queryParams['expiration'], $queryParams['signature'])) {
            throw new \RuntimeException('Invalid signed URL: missing required parameters.');
        }
    
        // Retrieve the expiration and signature values
        $expiration = (int) $queryParams['expiration'];
        $signature = $queryParams['signature'];
    
        // Check if the URL has expired
        if ($expiration < time()) {
            throw new \RuntimeException('Signed URL has expired.');
        }
    
        // Retrieve the base URL from the signed URL
        $baseUrl = $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'];
    
        // Reconstruct the original URL without the signature parameter
        $originalUrl = $baseUrl . '?' . http_build_query(array_diff_key($queryParams, ['signature' => '']));
    
        // Retrieve the secret key from the environment
        $secretKey = $_ENV['APP_KEY'];
    
        // Calculate the expected signature for the original URL
        $expectedSignature = hash_hmac('sha256', $originalUrl, $secretKey);
    
        // Compare the expected signature with the provided signature
        if (!hash_equals($expectedSignature, $signature)) {
            throw new \RuntimeException('Invalid signed URL: signature mismatch.');
        }
    
        return true;
    }
    
}
