# laravel-totp
A Laravel package for generating and verifying Time-Based One-Time Passwords (TOTP) compatible with RFC 6238. Provides both facade and contract-based implementations.

# Features
- Generate TOTP codes
- Verify TOTP codes with configurable window
- Base32 encoding/decoding utilities
- Configurable parameters (period, digits, verification window)
- Laravel facade and service provider integration

# Installation
- Install via Composer:
```bash
composer require yaman-shahbander-dev/laravel-totp
```

- Publish the configuration file (optional):
```bash
php artisan vendor:publish --provider="TotpGenerator\Providers\TotpServiceProvider" --tag="config"
```

# Configuration
Default values in config/totp.php:
```php
'default_period' => 30,      // Time step in seconds
'default_digits' => 6,       // Number of digits in OTP
'verification_window' => 1,  // Number of periods to check before/after
```

# Usage
Using the Facade
```php
use TotpGenerator\Facades\Totp;

// Generate a random secret (store this securely)
$secret = Str::random(16);
$base32Secret = Totp::encodeBase32($secret);

// Generate current TOTP
$code = Totp::generate($base32Secret);

// Verify a code
$isValid = Totp::verify($userCode, $base32Secret);

// With custom window
$isValid = Totp::verify($userCode, $base32Secret, 2);
```

Using Dependency Injection
```php
use TotpGenerator\Contracts\TotpGeneratorContract;

class AuthController {
    public function __construct(
        protected TotpGeneratorContract $totp
    ) {}

    public function verifyCode(Request $request)
    {
        $isValid = $this->totp->verify(
            $request->code,
            $user->totp_secret
        );
    }
}
```

Direct Usage
```php
$totp = app(TotpGeneratorContract::class);
$code = $totp->generate($base32Secret);
```

Base32 Utilities
```php
// Encode binary to Base32
$base32Secret = Totp::encodeBase32(random_bytes(16));

// Decode Base32 to binary
$binarySecret = Totp::decodeBase32($base32Secret);
```

# Security Considerations
- Always store secrets securely (encrypted at rest)
- Use secure random bytes for secret generation
- Consider rate limiting verification attempts
- The package uses SHA-1 by default (compatible with most authenticator apps)

# License
MIT License (see [LICENSE](https://mit-license.org/) file)

