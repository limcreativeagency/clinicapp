<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HospitalSignupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Admin bilgileri
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            
            // Hastane bilgileri
            'hospital_name' => 'required|string|max:255',
            'hospital_email' => 'required|email|unique:hospitals,email',
            'phone_country_code' => 'nullable|string|max:5',
            'phone' => 'nullable|string|max:20',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'address' => 'required|string|max:1000',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:2000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'admin_name.required' => 'Yönetici adı zorunludur.',
            'admin_email.required' => 'Yönetici e-posta adresi zorunludur.',
            'admin_email.email' => 'Geçerli bir yönetici e-posta adresi giriniz.',
            'admin_email.unique' => 'Bu yönetici e-posta adresi zaten kullanılmaktadır.',
            'password.required' => 'Şifre zorunludur.',
            'password.min' => 'Şifre en az 8 karakter olmalıdır.',
            'password.confirmed' => 'Şifre onayı eşleşmiyor.',
            
            'hospital_name.required' => 'Hastane adı zorunludur.',
            'hospital_email.required' => 'Hastane e-posta adresi zorunludur.',
            'hospital_email.email' => 'Geçerli bir hastane e-posta adresi giriniz.',
            'hospital_email.unique' => 'Bu hastane e-posta adresi zaten kullanılmaktadır.',
            'city.required' => 'Şehir zorunludur.',
            'country.required' => 'Ülke zorunludur.',
            'address.required' => 'Adres zorunludur.',
            'website.url' => 'Geçerli bir web sitesi adresi giriniz.',
            'logo.image' => 'Logo bir resim dosyası olmalıdır.',
            'logo.mimes' => 'Logo JPEG, PNG, JPG veya GIF formatında olmalıdır.',
            'logo.max' => 'Logo dosyası 2MB\'dan büyük olamaz.',
        ];
    }
}
