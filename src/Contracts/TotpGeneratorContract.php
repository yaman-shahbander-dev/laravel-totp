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
}