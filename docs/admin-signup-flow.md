# Admin Signup Flow Dokümantasyonu

## Genel Bakış
Bu dokümantasyon, hastane yöneticilerinin public form ile hastane ve admin hesabını tek adımda oluşturma sürecini açıklar.

## Akış

### 1. Public Form Erişimi
- URL: `/hospital-signup`
- Controller: `HospitalSignupController@showSignupForm`
- View: `resources/views/auth/hospital-signup.blade.php`

### 2. Form Yapısı
Form iki bölümden oluşur:

#### Admin Bilgileri
- `admin_name`: Yönetici adı (zorunlu)
- `admin_email`: Yönetici e-posta (zorunlu, unique)
- `password`: Şifre (zorunlu, min 8 karakter)
- `password_confirmation`: Şifre tekrarı (zorunlu)

#### Hastane Bilgileri
- `hospital_name`: Hastane adı (zorunlu)
- `hospital_email`: Hastane e-posta (zorunlu, unique)
- `phone_country_code`: Ülke kodu (opsiyonel, default: +90)
- `phone`: Telefon (opsiyonel)
- `city`: Şehir (zorunlu)
- `country`: Ülke (zorunlu, default: Türkiye)
- `address`: Adres (zorunlu)
- `website`: Web sitesi (opsiyonel, URL formatı)
- `description`: Açıklama (opsiyonel)
- `logo`: Logo dosyası (opsiyonel, max 2MB, image)

### 3. Form İşleme
- Controller: `HospitalSignupController@signup`
- Request: `HospitalSignupRequest`
- Transaction kullanılır (hastane + admin aynı anda oluşturulur)

### 4. Veritabanı İşlemleri
```php
DB::transaction(function () {
    // 1. Hastane oluştur
    $hospital = Hospital::create([
        'status' => 'pending',
        'subscription_status' => 'trial'
    ]);
    
    // 2. Admin kullanıcısı oluştur
    $user = User::create([
        'role' => 'admin',
        'hospital_id' => $hospital->id,
        'status' => 'pending'
    ]);
    
    // 3. Logo yükle (varsa)
    if ($request->hasFile('logo')) {
        $logoService->uploadLogo($hospital, $request->file('logo'));
    }
});
```

### 5. E-posta Doğrulama
- `Registered` event tetiklenir
- E-posta doğrulama maili gönderilir
- Kullanıcı `/email/verify` sayfasına yönlendirilir

## Validasyon Kuralları

### Admin Bilgileri
- `admin_name`: required, string, max:255
- `admin_email`: required, email, unique:users
- `password`: required, string, min:8, confirmed

### Hastane Bilgileri
- `hospital_name`: required, string, max:255
- `hospital_email`: required, email, unique:hospitals
- `phone_country_code`: nullable, string, max:5
- `phone`: nullable, string, max:20
- `city`: required, string, max:100
- `country`: required, string, max:100
- `address`: required, string, max:1000
- `website`: nullable, url, max:255
- `description`: nullable, string, max:2000
- `logo`: nullable, image, mimes:jpeg,png,jpg,gif, max:2048

## Güvenlik
- CSRF koruması aktif
- File upload güvenliği (mime type, size kontrolü)
- Unique email kontrolü
- Transaction kullanımı (veri tutarlılığı)

## Hata Yönetimi
- Validation hataları form üzerinde gösterilir
- Database hataları rollback ile geri alınır
- Kullanıcı dostu hata mesajları

## Sonraki Adımlar
1. E-posta doğrulama
2. Trial başlatma
3. Dashboard erişimi
