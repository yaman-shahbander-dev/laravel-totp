<?php

namespace TotpGenerator\Tests\Unit;

use TotpGenerator\Services\TotpGenerator;
use InvalidArgumentException;
use TotpGenerator\Tests\TestCase;

class TotpGeneratorTest extends TestCase
{
    private TotpGenerator $totp;

    protected function setUp(): void
    {
        parent::setUp();

        $this->totp = new TotpGenerator();
    }

    public function test_generate_valid_totp()
    {
        $secret = 'JBSWY3DPEHPK3PXP'; 

        $otp = $this->totp->generate($secret);

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

        $generatedCode = $this->totp->generate($secret);

        $this->assertTrue($this->totp->verify($generatedCode, $secret, 1));
    }

    public function test_verify_incorrect_code()
    {
        $secret = 'JBSWY3DPEHPK3PXP';
        
        $incorrectCode = '123456';

        $this->assertFalse($this->totp->verify($incorrectCode, $secret, 1));
    }
}
