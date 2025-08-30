<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HospitalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Policy'lerde kontrol edilecek
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'phone_country_code' => 'nullable|string|max:5',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:hospitals,email',
            'tax_number' => 'nullable|string|max:50',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'website' => 'nullable|url|max:255',
            'address' => 'required|string|max:1000',
            'description' => 'nullable|string|max:2000',
            'notes' => 'nullable|string|max:2000',
            'status' => 'sometimes|in:pending,active,suspended',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Update durumunda email unique kuralını güncelle
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['email'] = 'required|email|unique:hospitals,email,' . $this->hospital;
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Klinik adı zorunludur.',
            'email.required' => 'E-posta adresi zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.unique' => 'Bu e-posta adresi zaten kullanılmaktadır.',
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
