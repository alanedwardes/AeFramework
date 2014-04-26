<?php
namespace AeFramework;

class HttpCode
{
	public $code;
	public function __construct($code)
	{
		$this->code = $code;
	}
	
	public static function codeToString($code)
	{
		$reflection = new \ReflectionClass(__CLASS__);
		$constants = $reflection->getConstants();
		$names = array_flip($constants);
		
		if (isset($names[$code]))
			return sprintf('%s %s', $code, Util::camelCaseToSpaces($names[$code]));
		
		return sprintf('%s', $code);
	}
	
	public function __toString()
	{
		return self::codeToString($this->code);
	}

	# 2xx Success
	const Ok = 200;
	const Created = 201;
	const Accepted = 202;
	const NonAuthoritativeInformation = 203;
	const NoContent = 204;
	const ResetContent = 205;
	const PartialContent = 206;
	const MutliStatus = 207;
	const AlreadyReported = 208;
	const IMUsed = 226;
	
	# 3xx Redirection
	const MultipleChoices = 300;
	const MovedPermanently = 301;
	const Found = 302;
	const SeeOther = 303;
	const NotModified = 304;
	const UseProxy = 305;
	const SwitchProxy = 306;
	const TemporaryRedirect = 307;
	const PermanentRedirect = 308;
	
	# 4xx Client Error
	const BadRequest = 400;
	const Unauthorized = 401;
	const PaymentRequired = 402;
	const Forbidden = 403;
	const NotFound = 404;
	const MethodNotAllowed = 405;
	const NotAcceptable = 406;
	const ProxyAuthenticationRequired = 407;
	const RequestTimeout = 408;
	const Conflict = 409;
	const Gone = 410;
	const LengthRequired = 411;
	const PreconditionFailed = 412;
	const RequestEntityTooLarge = 413;
	const RequestUriTooLong = 414;
	const UnsupportedMediaType = 415;
	const RequestRangeNotSatisfiable = 416;
	const ExpectationFailed = 417;
	const ImATeapot = 418;
	const AuthenticationTimeout = 419;
	const MethodFailed = 420;
	const UnprocessableEntity = 422;
	const Locked = 423;
	const FailedDependency = 424;
	const UnorderedCollection = 425;
	const UpgradeRequired = 426;
	const PreconditionRequired = 428;
	const TooManyRequests = 429;
	const RequestHeaderFieldsTooLarge = 431;
	const LoginTimeout = 440;
	const NoResponse = 444;
	const BlockedByWindowsParentalControls = 450;
	const UnavailableForLegalReasons = 451;
	const RequestHeaderTooLarge = 494;
	const CertError = 495;
	const NoCert = 496;
	const HttpToHttps = 497;
	const ClientClosedRequest = 499;
	
	# 5xx Server Error
	const InternalServerError = 500;
	const NotImplemented = 501;
	const BadGateway = 502;
	const ServiceUnavailable = 503;
	const GatewayTimouet = 504;
	const HttpVersionNotSupported = 505;
	const VariantAlsoNegotiates = 506;
	const InsufficientStorage = 507;
	const LoopDetected = 508;
	const NotExtended = 510;
	const NetworkAuthenticationRequired = 511;
}