<?php declare(strict_types=1);

namespace FatCode\OpenApi\Http;

use FatCode\Enum;

/**
 * @method static CONTINUE
 * @method static SWITCHING_PROTOCOLS
 * @method static PROCESSING
 * @method static OK
 * @method static CREATED
 * @method static ACCEPTED
 * @method static NON_AUTHORITATIVE_INFORMATION
 * @method static NO_CONTENT
 * @method static RESET_CONTENT
 * @method static PARTIAL_CONTENT
 * @method static MULTI_STATUS
 * @method static ALREADY_REPORTED
 * @method static IM_USED
 * @method static MULTIPLE_CHOICES
 * @method static MOVED_PERMANENTLY
 * @method static FOUND
 * @method static SEE_OTHER
 * @method static NOT_MODIFIED
 * @method static USE_PROXY
 * @method static TEMPORARY_REDIRECT
 * @method static PERMANENTLY_REDIRECT
 * @method static BAD_REQUEST
 * @method static UNAUTHORIZED
 * @method static PAYMENT_REQUIRED
 * @method static FORBIDDEN
 * @method static NOT_FOUND
 * @method static METHOD_NOT_ALLOWED
 * @method static NOT_ACCEPTABLE
 * @method static PROXY_AUTHENTICATION_REQUIRED
 * @method static REQUEST_TIMEOUT
 * @method static CONFLICT
 * @method static GONE
 * @method static LENGTH_REQUIRED
 * @method static PRECONDITION_FAILED
 * @method static REQUEST_ENTITY_TOO_LARGE
 * @method static REQUEST_URI_TOO_LONG
 * @method static UNSUPPORTED_MEDIA_TYPE
 * @method static REQUESTED_RANGE_NOT_SATISFIABLE
 * @method static EXPECTATION_FAILED
 * @method static I_AM_A_TEAPOT
 * @method static MISDIRECTED_REQUEST
 * @method static UNPROCESSABLE_ENTITY
 * @method static LOCKED
 * @method static FAILED_DEPENDENCY
 * @method static UPGRADE_REQUIRED
 * @method static PRECONDITION_REQUIRED
 * @method static TOO_MANY_REQUESTS
 * @method static REQUEST_HEADER_FIELDS_TOO_LARGE
 * @method static CONNECTION_CLOSED_WITHOUT_RESPONSE
 * @method static UNAVAILABLE_FOR_LEGAL_REASONS
 * @method static CLIENT_CLOSED_REQUEST
 * @method static INTERNAL_SERVER_ERROR
 * @method static NOT_IMPLEMENTED
 * @method static BAD_GATEWAY
 * @method static SERVICE_UNAVAILABLE
 * @method static GATEWAY_TIMEOUT
 * @method static VERSION_NOT_SUPPORTED
 * @method static VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL
 * @method static INSUFFICIENT_STORAGE
 * @method static LOOP_DETECTED
 * @method static NOT_EXTENDED
 * @method static NETWORK_AUTHENTICATION_REQUIRED
 * @method static NETWORK_CONNECT_TIMEOUT
 */
class HttpStatusCode extends Enum
{
    public const CONTINUE = 100;
    public const SWITCHING_PROTOCOLS = 101;
    public const PROCESSING = 102;
    public const OK = 200;
    public const CREATED = 201;
    public const ACCEPTED = 202;
    public const NON_AUTHORITATIVE_INFORMATION = 203;
    public const NO_CONTENT = 204;
    public const RESET_CONTENT = 205;
    public const PARTIAL_CONTENT = 206;
    public const MULTI_STATUS = 207;
    public const ALREADY_REPORTED = 208;
    public const IM_USED = 226;
    public const MULTIPLE_CHOICES = 300;
    public const MOVED_PERMANENTLY = 301;
    public const FOUND = 302;
    public const SEE_OTHER = 303;
    public const NOT_MODIFIED = 304;
    public const USE_PROXY = 305;
    public const TEMPORARY_REDIRECT = 307;
    public const PERMANENTLY_REDIRECT = 308;
    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const PAYMENT_REQUIRED = 402;
    public const FORBIDDEN = 403;
    public const NOT_FOUND = 404;
    public const METHOD_NOT_ALLOWED = 405;
    public const NOT_ACCEPTABLE = 406;
    public const PROXY_AUTHENTICATION_REQUIRED = 407;
    public const REQUEST_TIMEOUT = 408;
    public const CONFLICT = 409;
    public const GONE = 410;
    public const LENGTH_REQUIRED = 411;
    public const PRECONDITION_FAILED = 412;
    public const REQUEST_ENTITY_TOO_LARGE = 413;
    public const REQUEST_URI_TOO_LONG = 414;
    public const UNSUPPORTED_MEDIA_TYPE = 415;
    public const REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    public const EXPECTATION_FAILED = 417;
    public const I_AM_A_TEAPOT = 418;
    public const MISDIRECTED_REQUEST = 421;
    public const UNPROCESSABLE_ENTITY = 422;
    public const LOCKED = 423;
    public const FAILED_DEPENDENCY = 424;
    public const UPGRADE_REQUIRED = 426;
    public const PRECONDITION_REQUIRED = 428;
    public const TOO_MANY_REQUESTS = 429;
    public const REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    public const CONNECTION_CLOSED_WITHOUT_RESPONSE = 444;
    public const UNAVAILABLE_FOR_LEGAL_REASONS = 451;
    public const CLIENT_CLOSED_REQUEST = 499;
    public const INTERNAL_SERVER_ERROR = 500;
    public const NOT_IMPLEMENTED = 501;
    public const BAD_GATEWAY = 502;
    public const SERVICE_UNAVAILABLE = 503;
    public const GATEWAY_TIMEOUT = 504;
    public const VERSION_NOT_SUPPORTED = 505;
    public const VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;
    public const INSUFFICIENT_STORAGE = 507;
    public const LOOP_DETECTED = 508;
    public const NOT_EXTENDED = 510;
    public const NETWORK_AUTHENTICATION_REQUIRED = 511;
    public const NETWORK_CONNECT_TIMEOUT = 599;

    /**
     * Map of standard HTTP status code/reason phrases
     *
     * @var array
     */
    private const PHRASES = [
        self::CONTINUE => 'Continue',
        self::SWITCHING_PROTOCOLS => 'Switching Protocols',
        self::PROCESSING => 'Processing',
        self::OK => 'OK',
        self::CREATED => 'Created',
        self::ACCEPTED => 'Accepted',
        self::NON_AUTHORITATIVE_INFORMATION => 'Non-Authoritative Information',
        self::NO_CONTENT => 'No Content',
        self::RESET_CONTENT => 'Reset Content',
        self::PARTIAL_CONTENT => 'Partial Content',
        self::MULTI_STATUS => 'Multi-status',
        self::ALREADY_REPORTED => 'Already Reported',
        self::IM_USED => 'I\'m used',
        self::MULTIPLE_CHOICES => 'Multiple Choices',
        self::MOVED_PERMANENTLY => 'Moved Permanently',
        self::FOUND => 'Found',
        self::SEE_OTHER => 'See Other',
        self::NOT_MODIFIED => 'Not Modified',
        self::USE_PROXY => 'Use Proxy',
        self::TEMPORARY_REDIRECT => 'Temporary Redirect',
        self::PERMANENTLY_REDIRECT => 'Permanent Redirect',
        self::BAD_REQUEST => 'Bad Request',
        self::UNAUTHORIZED => 'Unauthorized',
        self::PAYMENT_REQUIRED => 'Payment Required',
        self::FORBIDDEN => 'Forbidden',
        self::NOT_FOUND => 'Not Found',
        self::METHOD_NOT_ALLOWED => 'Method Not Allowed',
        self::NOT_ACCEPTABLE => 'Not Acceptable',
        self::PROXY_AUTHENTICATION_REQUIRED => 'Proxy Authentication Required',
        self::REQUEST_TIMEOUT => 'Request Time-out',
        self::CONFLICT => 'Conflict',
        self::GONE => 'Gone',
        self::LENGTH_REQUIRED => 'Length Required',
        self::PRECONDITION_FAILED => 'Precondition Failed',
        self::REQUEST_ENTITY_TOO_LARGE => 'Request Entity Too Large',
        self::REQUEST_URI_TOO_LONG => 'Request-URI Too Large',
        self::UNSUPPORTED_MEDIA_TYPE => 'Unsupported Media Property',
        self::REQUESTED_RANGE_NOT_SATISFIABLE => 'Requested range not satisfiable',
        self::EXPECTATION_FAILED => 'Expectation Failed',
        self::I_AM_A_TEAPOT => 'I\'m a teapot',
        self::MISDIRECTED_REQUEST => 'Misdirected Request',
        self::UNPROCESSABLE_ENTITY => 'Unprocessable Entity',
        self::LOCKED => 'Locked',
        self::FAILED_DEPENDENCY => 'Failed Dependency',
        self::UPGRADE_REQUIRED => 'Upgrade Required',
        self::PRECONDITION_REQUIRED => 'Precondition Required',
        self::TOO_MANY_REQUESTS => 'Too Many Requests',
        self::REQUEST_HEADER_FIELDS_TOO_LARGE => 'Request Header Fields Too Large',
        self::CONNECTION_CLOSED_WITHOUT_RESPONSE => 'Connection Closed Without Response',
        self::UNAVAILABLE_FOR_LEGAL_REASONS => 'Unavailable For Legal Reasons',
        self::CLIENT_CLOSED_REQUEST => 'Client Closed Request',
        self::INTERNAL_SERVER_ERROR => 'Internal Server Error',
        self::NOT_IMPLEMENTED => 'Not Implemented',
        self::BAD_GATEWAY => 'Bad Gateway',
        self::SERVICE_UNAVAILABLE => 'Service Unavailable',
        self::GATEWAY_TIMEOUT => 'Gateway Time-out',
        self::VERSION_NOT_SUPPORTED => 'HTTP Version not supported',
        self::VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL => 'Variant Also Negotiates',
        self::INSUFFICIENT_STORAGE => 'Insufficient Storage',
        self::LOOP_DETECTED => 'Loop Detected',
        self::NOT_EXTENDED => 'Not Extended',
        self::NETWORK_AUTHENTICATION_REQUIRED => 'Network Authentication Required',
        self::NETWORK_CONNECT_TIMEOUT => 'Network Connect Timeout Error',
    ];

    public function getPhrase() : string
    {
        return self::PHRASES[$this->getValue()];
    }
}
