<?php

namespace Tests\Feature;

use App\Services\PromptIntentDetector;
use PHPUnit\Framework\TestCase;

class PromptIntentDetectorTest extends TestCase
{
    public function test_it_detects_npwp_entity(): void
    {
        $detector = new PromptIntentDetector();

        $result = $detector->detect('Mohon validasi NPWP 12.345.678.9-123.456 milik vendor kami.');

        $this->assertSame('npwp', $result['intent']);
        $this->assertSame('12.345.678.9-123.456', $result['entities']['npwp']);
    }
}
