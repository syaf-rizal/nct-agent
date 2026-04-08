<?php

namespace App\Services;

use App\Support\IntentType;

class PromptIntentDetector
{
    public function detect(string $content): array
    {
        $normalized = ' ' . mb_strtolower($content) . ' ';

        $entities = [
            'booking_number' => $this->extract('/\b(?:book(?:ing)?[-\s:]*)?(\d{6,12})\b/i', $content),
            'delivery_order_number' => $this->extract('/\b(?:do|delivery\s*order)[-\s:]*(\w{4,20})\b/i', $content),
            'sales_order_number' => $this->extract('/\b(?:so|sales\s*order|order)[-\s:]*(\w{4,20})\b/i', $content),
            'npwp' => $this->extract('/\b(\d{2}\.\d{3}\.\d{3}\.\d-\d{3}\.\d{3}|\d{15,16})\b/', $content),
            'customs_code' => $this->extract('/\b(?:bc\s*\d{1,2}(?:\.\d+)?|hs\s*code|customs|kepabeanan|bea\s*cukai)\b/i', $content),
        ];

        $intent = match (true) {
            str_contains($normalized, ' booking ') || !empty($entities['booking_number']) => IntentType::BOOKING,
            str_contains($normalized, ' delivery order ') || str_contains($normalized, ' do ') || !empty($entities['delivery_order_number']) => IntentType::DELIVERY_ORDER,
            str_contains($normalized, ' sales order ') || str_contains($normalized, ' nomor order ') || !empty($entities['sales_order_number']) => IntentType::SALES_ORDER,
            str_contains($normalized, ' npwp ') || !empty($entities['npwp']) => IntentType::NPWP,
            str_contains($normalized, ' customs ') || str_contains($normalized, ' bea cukai ') || !empty($entities['customs_code']) => IntentType::CUSTOMS,
            default => IntentType::GENERAL,
        };

        return [
            'intent' => $intent->value,
            'entities' => array_filter($entities),
        ];
    }

    private function extract(string $pattern, string $content): ?string
    {
        preg_match($pattern, $content, $matches);

        return $matches[1] ?? $matches[0] ?? null;
    }
}
