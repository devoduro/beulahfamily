<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventQrCode;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QrCodeService
{
    /**
     * Generate QR code for an event
     */
    public function generateEventQrCode(Event $event, $expirationHours = 24)
    {
        // Check if active QR code already exists
        $existingQr = EventQrCode::where('event_id', $event->id)
                                ->active()
                                ->first();

        if ($existingQr) {
            return $existingQr;
        }

        // Generate new QR code
        $token = EventQrCode::generateToken();
        $qrUrl = route('attendance.scan', ['token' => $token]);

        // Generate QR code using native SVG backend (no Imagick required)
        $renderer = new ImageRenderer(
            new RendererStyle(300, 2),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeImage = $writer->writeString($qrUrl);

        // Save QR code image
        $fileName = "qr-codes/event-{$event->id}-" . time() . ".svg";
        Storage::disk('public')->put($fileName, $qrCodeImage);

        // Create QR code record
        $eventQr = EventQrCode::create([
            'event_id' => $event->id,
            'qr_code_token' => $token,
            'qr_code_path' => $fileName,
            'expires_at' => $expirationHours ? now()->addHours($expirationHours) : null,
            'is_active' => true,
            'scan_count' => 0
        ]);

        return $eventQr;
    }

    /**
     * Generate QR code for member identification
     */
    public function generateMemberQrCode($memberId)
    {
        $qrUrl = route('attendance.member-scan', ['member' => $memberId]);
        
        $renderer = new ImageRenderer(
            new RendererStyle(200, 1),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        return $writer->writeString($qrUrl);
    }

    /**
     * Validate QR code token
     */
    public function validateToken($token)
    {
        $eventQr = EventQrCode::where('qr_code_token', $token)->first();

        if (!$eventQr) {
            return [
                'valid' => false,
                'message' => 'Invalid QR code token'
            ];
        }

        if (!$eventQr->isValid()) {
            return [
                'valid' => false,
                'message' => 'QR code has expired or is inactive'
            ];
        }

        return [
            'valid' => true,
            'event_qr' => $eventQr,
            'event' => $eventQr->event
        ];
    }

    /**
     * Deactivate all QR codes for an event
     */
    public function deactivateEventQrCodes(Event $event)
    {
        EventQrCode::where('event_id', $event->id)
                  ->update(['is_active' => false]);
    }

    /**
     * Clean up expired QR codes
     */
    public function cleanupExpiredQrCodes()
    {
        $expiredQrCodes = EventQrCode::expired()->get();

        foreach ($expiredQrCodes as $qrCode) {
            // Delete QR code image file
            if ($qrCode->qr_code_path && Storage::disk('public')->exists($qrCode->qr_code_path)) {
                Storage::disk('public')->delete($qrCode->qr_code_path);
            }

            // Delete QR code record
            $qrCode->delete();
        }

        return $expiredQrCodes->count();
    }

    /**
     * Get QR code statistics
     */
    public function getQrCodeStats($eventId = null)
    {
        $query = EventQrCode::query();

        if ($eventId) {
            $query->where('event_id', $eventId);
        }

        return [
            'total_qr_codes' => $query->count(),
            'active_qr_codes' => $query->active()->count(),
            'expired_qr_codes' => $query->expired()->count(),
            'total_scans' => $query->sum('scan_count'),
        ];
    }
}
