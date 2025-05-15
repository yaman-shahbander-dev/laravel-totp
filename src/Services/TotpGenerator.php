<?php

namespace TotpGenerator\Services;

use Illuminate\Support\Str;
use InvalidArgumentException;
use TotpGenerator\Contracts\TotpGeneratorContract;

class TotpGenerator implements TotpGeneratorContract
{
    const BASE32_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    public function generate(string $base32Secret, ?int $timestamp = null): string
    {
        $secret = $this->decodeBase32($base32Secret);

        if (empty($secret)) {
            throw new InvalidArgumentException('Invalid TOTP secret.');
        }

        $digits = config('totp.default_digits') ?? 6;
        if ($digits < 4 || $digits > 8) {
            throw new InvalidArgumentException('Digits must be between 4 and 8.');
        }

        $period = config('totp.default_period') ?? 30;
        $timestamp = $timestamp ?? time();

        $counter = (int) floor($timestamp / $period);

        $binaryCounter = $this->counterToBinary($counter);

        $hash = hash_hmac('sha1', $binaryCounter, $secret, true);
        
        $otp = $this->truncateHash($hash, $digits);

        return Str::padLeft($otp, $digits, '0');
    }

    public function verify(string $code, string $base32Secret, int $window = null): bool
    {
        $period = config('totp.default_period') ?? 30;
        $window = $window ?? config('totp.verification_window');

        for ($i = -$window; $i <= $window; $i++) {
            $timestamp = time() + ($i * $period);
            $generated = $this->generate($base32Secret, $timestamp);
            
            if (hash_equals($generated, $code)) {
                return true;
            }
        }

        return false;
    }

    protected function decodeBase32(string $base32): string
    {
        $base32 = Str::upper(trim($base32));
        
        $base32 = Str::replace('=', '', $base32);

        if (!preg_match('/^[' . self::BASE32_ALPHABET . ']+$/', $base32)) {
            throw new InvalidArgumentException('Invalid Base32 characters.');
        }

        $buffer = 0;

        $bufferSize = 0;

        $binary = '';

        foreach (str_split($base32) as $char) {
            $buffer <<= 5;

            $buffer |= strpos(self::BASE32_ALPHABET, $char);

            $bufferSize += 5;

            if ($bufferSize >= 8) {
                $bufferSize -= 8;

                $binary .= chr(($buffer >> $bufferSize) & 0xFF);
            }
        }

        return $binary;
    }

    protected function counterToBinary(int $counter): string
    {
        return pack('J', $counter); // 64-bit big-endian
    }

    protected function truncateHash(string $hash, int $digits): string
    {
        $offset = ord(substr($hash, -1)) & 0x0F;

        $binary = substr($hash, $offset, 4);

        return (string) (
            ((ord($binary) & 0x7F) << 24) |
            ((ord($binary[1]) & 0xFF) << 16) |
            ((ord($binary[2]) & 0xFF) << 8) |
            (ord($binary[3]) & 0xFF)
        ) % (10 ** $digits);
    }
}