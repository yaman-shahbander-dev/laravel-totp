<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use TotpGenerator\Services\TotpGenerator;
use InvalidArgumentException;

class TotpGeneratorTest extends TestCase
{
    private TotpGenerator $totp;

    protected function setUp(): void
    {
        parent::setUp();

        config('totp.default_period', 30);
        config('totp.default_digits', 6);
        config('totp.verification_window', 1);

        $this->totp = new TotpGenerator();
    }

    public function test_generate_valid_totp()
    {
        $secret = 'JBSWY3DPEHPK3PXP'; 
        $timestamp = 1688486400; 
        $otp = $this->totp->generate($secret, $timestamp);

        $this->assertIsString($otp);
        $this->assertEquals(6, strlen($otp)); 
    }

    public function test_generate_invalid_secret()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->totp->generate('', time()); 
    }

    public function test_verify_correct_code()
    {
        $secret = 'JBSWY3DPEHPK3PXP';
        $timestamp = 1688486400;
        $generatedCode = $this->totp->generate($secret, $timestamp);

        $this->assertTrue($this->totp->verify($generatedCode, $secret, 1));
    }

    public function test_verify_incorrect_code()
    {
        $secret = 'JBSWY3DPEHPK3PXP';
        $incorrectCode = '123456';

        $this->assertFalse($this->totp->verify($incorrectCode, $secret, 1));
    }
}
