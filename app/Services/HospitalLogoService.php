<?php

namespace App\Services;

use App\Models\Hospital;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class HospitalLogoService
{
    /**
     * Upload logo for hospital
     */
    public function uploadLogo(Hospital $hospital, UploadedFile $file): string
    {
        // Eski logoyu sil
        $this->deleteLogo($hospital);

        // Yeni logoyu yükle
        $path = $file->store('hospitals/logos', 'public');
        
        // Hospital'ı güncelle
        $hospital->update(['logo_path' => $path]);

        return $path;
    }

    /**
     * Delete logo for hospital
     */
    public function deleteLogo(Hospital $hospital): bool
    {
        if ($hospital->logo_path && Storage::disk('public')->exists($hospital->logo_path)) {
            Storage::disk('public')->delete($hospital->logo_path);
            $hospital->update(['logo_path' => null]);
            return true;
        }

        return false;
    }

    /**
     * Get logo URL for hospital
     */
    public function getLogoUrl(Hospital $hospital): ?string
    {
        if (!$hospital->logo_path) {
            return null;
        }

        return Storage::disk('public')->url($hospital->logo_path);
    }
}
