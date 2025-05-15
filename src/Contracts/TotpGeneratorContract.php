<?php

namespace TotpGenerator\Contracts;

interface TotpGeneratorContract
{
    /**
     * Generate a TOTP code.
     *
     * @param string $base32Secret
     * @param int|null $timestamp
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function generate(string $base32Secret, ?int $timestamp = null): string;

    /**
     * Verify a TOTP code.
     *
     * @param string $code
     * @param string $base32Secret
     * @param int $window
     * @return bool
     */
    public function verify(string $code, string $base32Secret, int $window = null): bool;

    /**
     * Encode a binary string to Base32
     */
    public function encodeBase32(string $binary): string;

    /**
     * Decode a Base32 string to binary
     * @throws InvalidArgumentException
     */
    public function decodeBase32(string $base32): string;
}