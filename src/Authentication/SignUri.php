<?php

use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class SignedUrl
{
    private $secretKey;
    private $uriFactory;

    public function __construct(string $secretKey, UriFactoryInterface $uriFactory)
    {
        $this->secretKey = $secretKey;
        $this->uriFactory = $uriFactory;
    }

    /**
     * Generates a signed URL with user information and expiration date.
     *
     * @param string|int $userId The user ID.
     * @param string $email The user email.
     * @param \DateTime $expirationDate The expiration date for the signed URL.
     *
     * @return UriInterface The generated signed URL.
     */
    public function generate($userId, string $email, \DateTime $expirationDate): UriInterface
    {
        $data = [
            'userId' => $userId,
            'email' => $email,
        ];

        $payload = base64_encode(json_encode($data));
        $signature = hash_hmac('sha256', $payload, $this->secretKey);

        $baseUrl = trim($_ENV['APP_URL'] ?? '', '/');

        $uri = $this->uriFactory->createUri($urlPath) // Change this to your actual route
            ->withQuery(http_build_query(['expiration' => $expirationDate->getTimestamp(), 'signature' => $signature]));

        return $uri;
    }

    /**
     * Validates a signed URL.
     *
     * @param UriInterface $signedUrl The signed URL to validate.
     *
     * @return bool True if the signed URL is valid, false otherwise.
     * @throws \RuntimeException If the signed URL is invalid or has expired.
     */
    public function validate(UriInterface $signedUrl): bool
    {
        parse_str($signedUrl->getQuery(), $queryParams);

        $payload = $queryParams['payload'] ?? '';
        $signature = $queryParams['signature'] ?? '';

        $decodedPayload = json_decode(base64_decode($payload), true);

        if (!$decodedPayload || !isset($decodedPayload['userId'], $decodedPayload['email'], $decodedPayload['expirationDate'])) {
            throw new \RuntimeException('Invalid payload in the signed URL');
        }

        $expectedSignature = hash_hmac('sha256', $payload, $this->secretKey);

        if (!hash_equals($signature, $expectedSignature)) {
            throw new \RuntimeException('Invalid signature in the signed URL');
        }

        $expirationDate = new \DateTime($decodedPayload['expirationDate']);
        $currentDate = new \DateTime();

        if ($currentDate > $expirationDate) {
            throw new \RuntimeException('The signed URL has expired');
        }

        return true;
    }
}

