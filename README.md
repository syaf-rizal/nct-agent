# Laravel 13 Prompt Router Bundle

Bundle project Laravel 13 untuk endpoint API berbasis natural language yang:
- menerima input prompt bebas,
- mendeteksi intent/entity (Nomor Booking / DO / Order / NPWP / Customs),
- meneruskan request ke endpoint downstream sesuai intent,
- lalu membalas ke user dengan bahasa natural menggunakan LLM.

## Rekomendasi LLM

Untuk use-case ini, rekomendasi utama:
1. **GPT-5 mini**: biaya lebih efisien, latensi rendah, tetap kuat untuk extraction + response natural.
2. **GPT-5**: akurasi reasoning lebih tinggi untuk prompt ambigu/kompleks.
3. **Fallback**: siapkan mode regex/rule-based jika LLM timeout agar API tetap responsif.

Strategi praktis produksi:
- `gpt-5-mini` sebagai default.
- Auto-escalate ke `gpt-5` hanya saat confidence intent rendah atau user multi-intent.

## Arsitektur

1. `POST /api/v1/prompt-router`
2. `PromptIntentDetector` mendeteksi intent + entity awal (rule/regex).
3. `EndpointOrchestrator` memanggil endpoint downstream sesuai intent.
4. `NaturalLanguageResponder` memanggil LLM dengan **master prompt**.
5. API mengembalikan payload struktur + jawaban natural.

## Master Prompt

Master prompt disimpan di `config/prompt_router.php` key `master_prompt` agar mudah di-tune tanpa ubah kode service.

## Struktur Utama

- `app/Http/Controllers/Api/PromptRouterController.php`
- `app/Http/Requests/PromptRouterRequest.php`
- `app/Services/PromptIntentDetector.php`
- `app/Services/EndpointOrchestrator.php`
- `app/Services/NaturalLanguageResponder.php`
- `config/prompt_router.php`
- `routes/api.php`

## Contoh Request

```bash
curl -X POST http://localhost:8000/api/v1/prompt-router \
  -H "Content-Type: application/json" \
  -d '{
    "content": "Tolong cek status booking 91827364 dan pastikan terkait customs BC 2.3"
  }'
```

## Contoh Response Ringkas

```json
{
  "success": true,
  "intent": "booking",
  "entities": {
    "booking_number": "91827364",
    "customs_code": "customs"
  },
  "forwarded_to": {
    "name": "Booking Service",
    "url": "https://example.com/api/booking/find"
  },
  "data": {"status": "in_transit"},
  "answer": "Booking 91827364 sedang diproses ..."
}
```

## Catatan

Project ini adalah **bundle source code** Laravel 13 (siap disesuaikan), namun instalasi dependency membutuhkan akses ke registry Composer.
