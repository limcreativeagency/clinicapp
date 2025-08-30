<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'role' => ['required', Rule::in([
                User::ROLE_SUPER_ADMIN,
                User::ROLE_ADMIN,
                User::ROLE_DOCTOR,
                User::ROLE_REPRESENTATIVE,
                User::ROLE_PATIENT
            ])],
            'hospital_id' => 'nullable|exists:hospitals,id',
            'phone_country_code' => 'nullable|string|max:5',
            'phone' => 'nullable|string|max:20',
            'assigned_doctor_id' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $doctor = User::find($value);
                        if (!$doctor || $doctor->role !== User::ROLE_DOCTOR) {
                            $fail('Seçilen kullanıcı doktor rolünde olmalıdır.');
                        }
                    }
                }
            ],
            'assigned_representative_id' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $representative = User::find($value);
                        if (!$representative || $representative->role !== User::ROLE_REPRESENTATIVE) {
                            $fail('Seçilen kullanıcı temsilci rolünde olmalıdır.');
                        }
                    }
                }
            ],
            'status' => 'sometimes|in:pending,active,suspended',
            'password' => 'sometimes|string|min:8|confirmed',
        ];

        // Update durumunda email unique kuralını güncelle
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['email'] = 'required|email|unique:users,email,' . $this->user;
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        // Hospital ID validasyonu
        $rules['hospital_id'] = function ($attribute, $value, $fail) {
            $role = $this->input('role');
            
            if (in_array($role, [User::ROLE_ADMIN, User::ROLE_DOCTOR, User::ROLE_REPRESENTATIVE, User::ROLE_PATIENT])) {
                if (!$value) {
                    $fail('Bu rol için hastane seçimi zorunludur.');
                }
            } elseif ($role === User::ROLE_SUPER_ADMIN && $value) {
                $fail('Süper admin için hastane seçimi yapılamaz.');
            }
        };

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
            'name.required' => 'Ad zorunludur.',
            'email.required' => 'E-posta adresi zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.unique' => 'Bu e-posta adresi zaten kullanılmaktadır.',
            'role.required' => 'Rol seçimi zorunludur.',
            'role.in' => 'Geçersiz rol seçimi.',
            'hospital_id.exists' => 'Seçilen hastane bulunamadı.',
            'assigned_doctor_id.exists' => 'Seçilen doktor bulunamadı.',
            'assigned_representative_id.exists' => 'Seçilen temsilci bulunamadı.',
            'password.min' => 'Şifre en az 8 karakter olmalıdır.',
            'password.confirmed' => 'Şifre onayı eşleşmiyor.',
        ];
    }
}
