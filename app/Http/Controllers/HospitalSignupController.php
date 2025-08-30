<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\User;
use App\Http\Requests\HospitalSignupRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use App\Services\HospitalLogoService;

class HospitalSignupController extends Controller
{
    protected $logoService;

    public function __construct(HospitalLogoService $logoService)
    {
        $this->logoService = $logoService;
    }

    /**
     * Show the hospital signup form
     */
    public function showSignupForm()
    {
        return view('auth.hospital-signup');
    }

    /**
     * Handle hospital signup
     */
    public function signup(HospitalSignupRequest $request)
    {
        try {
            DB::beginTransaction();

            // Create hospital
            $hospitalData = $request->only([
                'hospital_name',
                'hospital_email',
                'phone_country_code',
                'phone',
                'city',
                'country',
                'address',
                'website',
                'description'
            ]);

            $hospital = Hospital::create([
                'name' => $hospitalData['hospital_name'],
                'email' => $hospitalData['hospital_email'],
                'phone_country_code' => $hospitalData['phone_country_code'],
                'phone' => $hospitalData['phone'],
                'city' => $hospitalData['city'],
                'country' => $hospitalData['country'],
                'address' => $hospitalData['address'],
                'website' => $hospitalData['website'],
                'description' => $hospitalData['description'],
                'status' => Hospital::STATUS_PENDING,
                'subscription_status' => Hospital::SUBSCRIPTION_TRIAL,
            ]);

            // Create admin user
            $user = User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->password),
                'role' => User::ROLE_ADMIN,
                'hospital_id' => $hospital->id,
                'phone_country_code' => $request->phone_country_code,
                'phone' => $request->phone,
                'status' => User::STATUS_PENDING,
            ]);

            // Update hospital with created_by
            $hospital->update(['created_by' => $user->id]);

            // Handle logo upload if provided
            if ($request->hasFile('logo')) {
                $this->logoService->uploadLogo($hospital, $request->file('logo'));
            }

            DB::commit();

            // Send email verification
            event(new Registered($user));

            return redirect()->route('verification.notice')
                ->with('success', 'Hastane kaydınız başarıyla oluşturuldu. E-posta doğrulaması yaparak 14 günlük deneme sürenizi başlatabilirsiniz.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->withErrors(['error' => 'Kayıt sırasında bir hata oluştu. Lütfen tekrar deneyiniz.']);
        }
    }
}
