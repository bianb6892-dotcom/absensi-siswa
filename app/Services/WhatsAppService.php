<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public static function kirim(string $noHp, string $pesan): bool
    {
        $token = config('services.whatsapp.token');
        if (empty($token)) {
            return false;
        }

        $noHp = self::normalize($noHp);
        if ($noHp === '') {
            return false;
        }

        try {
            $gateway = config('services.whatsapp.gateway', 'fonnte');

            if ($gateway === 'wablas') {
                $url = rtrim(config('services.whatsapp.url', ''), '/').'/api/send-message';
                $response = Http::timeout(15)
                    ->withHeaders(['Authorization' => $token])
                    ->asForm()
                    ->post($url, [
                        'phone' => $noHp,
                        'message' => $pesan,
                    ]);
            } else {
                // Fonnte (default)
                $url = config('services.whatsapp.url', 'https://api.fonnte.com/send');
                $response = Http::timeout(15)
                    ->withHeaders(['Authorization' => $token])
                    ->asForm()
                    ->post($url, [
                        'target' => $noHp,
                        'message' => $pesan,
                        'countryCode' => '62',
                    ]);
            }

            return $response->successful();
        } catch (\Throwable $e) {
            return false;
        }
    }

    private static function normalize(string $noHp): string
    {
        $no = preg_replace('/\D+/', '', $noHp);

        if (str_starts_with($no, '0')) {
            $no = '62'.substr($no, 1);
        } elseif (str_starts_with($no, '8')) {
            $no = '62'.$no;
        }

        return $no;
    }
}
