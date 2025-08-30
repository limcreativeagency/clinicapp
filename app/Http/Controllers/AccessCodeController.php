<?php

namespace App\Http\Controllers;

use App\Models\AccessCode;
use App\Models\AccessLog;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class AccessCodeController extends Controller
{
    /**
     * Request access code for a hospital
     */
    public function requestAccess(Request $request, Hospital $hospital)
    {
        // Rate limiting
        $key = 'access_request_' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['error' => 'Çok fazla deneme yaptınız. Lütfen daha sonra tekrar deneyiniz.']);
        }

        // Check if user is super admin
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        try {
            // Create access code
            $accessCode = AccessCode::createCode(
                $hospital->id,
                auth()->id(),
                $request->ip()
            );

            // Log the request
            AccessLog::logRequest(
                $hospital->id,
                auth()->id(),
                $request->ip(),
                $request->userAgent()
            );

            // Send email to hospital admin
            $admin = $hospital->admins()->first();
            if ($admin) {
                // Mail gönderme işlemi burada yapılacak
                // Mail::to($admin->email)->send(new AccessCodeRequestMail($accessCode));
            }

            RateLimiter::hit($key);

            return back()->with('success', 'Erişim kodu hastane yöneticisine gönderildi.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erişim kodu oluşturulurken bir hata oluştu.']);
        }
    }

    /**
     * Verify access code
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
            'hospital_id' => 'required|exists:hospitals,id'
        ]);

        $code = $request->code;
        $hospitalId = $request->hospital_id;

        // Rate limiting
        $key = 'code_verify_' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            return back()->withErrors(['error' => 'Çok fazla deneme yaptınız. Lütfen daha sonra tekrar deneyiniz.']);
        }

        // Check if user is super admin
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $accessCode = AccessCode::where('code', $code)
            ->where('hospital_id', $hospitalId)
            ->where('super_admin_id', auth()->id())
            ->where('status', AccessCode::STATUS_PENDING)
            ->where('expires_at', '>', now())
            ->first();

        if (!$accessCode) {
            RateLimiter::hit($key);
            
            // Log failed verification
            AccessLog::logVerification(
                $hospitalId,
                auth()->id(),
                0,
                false,
                $request->ip(),
                $request->userAgent(),
                ['code' => $code]
            );

            return back()->withErrors(['error' => 'Geçersiz veya süresi dolmuş kod.']);
        }

        // Mark code as used
        $accessCode->markAsUsed();

        // Log successful verification
        AccessLog::logVerification(
            $hospitalId,
            auth()->id(),
            $accessCode->id,
            true,
            $request->ip(),
            $request->userAgent()
        );

        // Store access in session for 15 minutes
        session([
            'hospital_access' => [
                'hospital_id' => $hospitalId,
                'access_code_id' => $accessCode->id,
                'expires_at' => now()->addMinutes(15)->timestamp
            ]
        ]);

        RateLimiter::clear($key);

        return redirect()->route('hospitals.patients', $hospitalId)
            ->with('success', 'Erişim kodu doğrulandı. 15 dakika boyunca hasta verilerine erişebilirsiniz.');
    }

    /**
     * Check if user has access to hospital data
     */
    public static function hasAccess(int $hospitalId): bool
    {
        $access = session('hospital_access');
        
        if (!$access) {
            return false;
        }

        if ($access['hospital_id'] !== $hospitalId) {
            return false;
        }

        if ($access['expires_at'] < now()->timestamp) {
            session()->forget('hospital_access');
            return false;
        }

        return true;
    }

    /**
     * Log data access
     */
    public static function logAccess(int $hospitalId, int $accessCodeId, Request $request = null): void
    {
        $access = session('hospital_access');
        
        if ($access && $access['hospital_id'] === $hospitalId) {
            AccessLog::logAccess(
                $hospitalId,
                auth()->id(),
                $accessCodeId,
                $request ? $request->ip() : null,
                $request ? $request->userAgent() : null
            );
        }
    }
}
