<?php

return [
    'master_prompt' => <<<'PROMPT'
Anda adalah AI Orchestrator untuk sistem logistik dan dokumen perdagangan.
Tugas Anda:
1) Pahami pertanyaan user dalam Bahasa Indonesia atau Inggris.
2) Gunakan hasil deteksi intent & entity yang diberikan sistem sebagai prioritas utama.
3) Berikan jawaban natural, ringkas, dan actionable.
4) Jika data dari downstream kosong/tidak ditemukan, jelaskan dengan sopan dan minta parameter minimum yang dibutuhkan.
5) Jangan membuat data fiktif. Jika tidak yakin, katakan tidak yakin.
6) Format jawaban:
   - Ringkasan (1-2 kalimat)
   - Detail utama (bullet)
   - Next action (jika perlu)
PROMPT,

    'intent_routes' => [
        'booking' => [
            'name' => 'Booking Service',
            'url' => env('BOOKING_ENDPOINT', 'https://example.com/api/booking/find'),
        ],
        'delivery_order' => [
            'name' => 'Delivery Order Service',
            'url' => env('DO_ENDPOINT', 'https://example.com/api/delivery-order/find'),
        ],
        'sales_order' => [
            'name' => 'Sales Order Service',
            'url' => env('ORDER_ENDPOINT', 'https://example.com/api/order/find'),
        ],
        'npwp' => [
            'name' => 'Taxpayer Registry Service',
            'url' => env('NPWP_ENDPOINT', 'https://example.com/api/npwp/validate'),
        ],
        'customs' => [
            'name' => 'Customs Service',
            'url' => env('CUSTOMS_ENDPOINT', 'https://example.com/api/customs/inquiry'),
        ],
        'general' => [
            'name' => 'General Assistant Service',
            'url' => env('GENERAL_ENDPOINT', 'https://example.com/api/general/helpdesk'),
        ],
    ],

    'downstream' => [
        'token' => env('DOWNSTREAM_TOKEN'),
        'timeout' => env('DOWNSTREAM_TIMEOUT', 20),
    ],

    'llm' => [
        'url' => env('LLM_URL', 'https://api.openai.com/v1/responses'),
        'api_key' => env('LLM_API_KEY'),
        'model' => env('LLM_MODEL', 'gpt-5-mini'),
        'timeout' => env('LLM_TIMEOUT', 25),
    ],
];
