<?php

declare(strict_types=1);

namespace Marissen\eCurring\Exception;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ApiException extends Exception
{
    /**
     * @var string
     */
    protected $field;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var array
     */
    protected $links = [];

    /**
     * @param string $message
     * @param int $code
     * @param string|null $field
     * @param ResponseInterface $response
     * @param Throwable $previous
     * @throws ApiException
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        string $field = null,
        ResponseInterface $response = null,
        Throwable $previous = null
    ) {
        if (!empty($field)) {
            $this->field = (string) $field;
            $message .= sprintf(". Field: %s", $this->field);
        }

        if (!empty($response)) {
            $this->response = $response;

            $object = static::parseResponseBody($this->response);

            if (isset($object->_links)) {
                foreach ($object->_links as $key => $value) {
                    $this->links[$key] = $value;
                }
            }
        }

        if ($this->hasLink('documentation')) {
            $message .= sprintf('. Documentation: %s', $this->getDocumentationUrl());
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * @param GuzzleException|RequestException $guzzleException
     * @param Throwable $previous
     * @return ApiException
     * @throws ApiException
     */
    public static function createFromGuzzleException(GuzzleException $guzzleException, Throwable $previous = null)
    {
        // Not all Guzzle Exceptions implement hasResponse() / getResponse()
        if (method_exists($guzzleException, 'hasResponse') && method_exists($guzzleException, 'getResponse')) {
            if ($guzzleException->hasResponse()) {
                return static::createFromResponse($guzzleException->getResponse());
            }
        }

        return new static($guzzleException->getMessage(), $guzzleException->getCode(), null, null, $previous);
    }

    /**
     * @param ResponseInterface $response
     * @param Throwable|null $previous
     * @return ApiException
     * @throws ApiException
     */
    public static function createFromResponse(ResponseInterface $response, Throwable $previous = null)
    {
        $object = static::parseResponseBody($response);

        $field = null;
        if (!empty($object->field)) {
            $field = $object->field;
        }

        $object = $object->errors[0];

        return new static(
            sprintf('Error executing API call (%s: %s): %s', $object->status, $object->title, $object->detail),
            $response->getStatusCode(),
            $field,
            $response,
            $previous
        );
    }

    /**
     * @return string|null
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return string|null
     */
    public function getDocumentationUrl()
    {
        return $this->getUrl('documentation');
    }

    /**
     * @return string|null
     */
    public function getDashboardUrl()
    {
        return $this->getUrl('dashboard');
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    public function hasResponse(): bool
    {
        return $this->response !== null;
    }

    public function hasLink(string $key): bool
    {
        return array_key_exists($key, $this->links);
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getLink(string $key)
    {
        if ($this->hasLink($key)) {
            return $this->links[$key];
        }

        return null;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getUrl(string $key)
    {
        if ($this->hasLink($key)) {
            return $this->getLink($key)->href;
        }

        return null;
    }

    /**
     * @param ResponseInterface $response
     * @return mixed
     * @throws ApiException
     */
    protected static function parseResponseBody(ResponseInterface $response)
    {
        $body = (string) $response->getBody();

        $object = @json_decode($body);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new static(sprintf('Unable to decode Mollie response: "%s".', $body));
        }

        return $object;
    }
}
