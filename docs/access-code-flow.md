# Access Code Flow Dokümantasyonu

## Genel Bakış
Bu dokümantasyon, Süper Admin'in KVKK gereği hasta verilerine erişim için onay kodu sürecini açıklar.

## Akış

### 1. Erişim Talebi
- Süper Admin hastane listesinde "Erişim Talebi" butonuna tıklar
- `AccessCodeController@requestAccess` tetiklenir
- 6 haneli rastgele kod oluşturulur
- Kod 15 dakika geçerli olur
- Hastane admin'ine e-posta ile kod gönderilir

### 2. Kod Doğrulama
- Süper Admin kodu panelde girer
- `AccessCodeController@verifyCode` tetiklenir
- Kod doğrulanırsa 15 dakikalık erişim açılır
- Session'da erişim bilgisi saklanır

### 3. Veri Erişimi
- 15 dakika boyunca hasta verilerine erişim sağlanır
- Her erişim loglanır
- Süre dolunca erişim otomatik kapanır

## Veritabanı Yapısı

### Access Codes Tablosu
```sql
CREATE TABLE access_codes (
    id BIGINT PRIMARY KEY,
    hospital_id BIGINT,
    super_admin_id BIGINT,
    code VARCHAR(6),
    expires_at TIMESTAMP,
    used_at TIMESTAMP NULL,
    status ENUM('pending', 'used', 'expired'),
    created_ip VARCHAR(45),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Access Logs Tablosu
```sql
CREATE TABLE access_logs (
    id BIGINT PRIMARY KEY,
    access_code_id BIGINT NULL,
    hospital_id BIGINT,
    user_id BIGINT NULL,
    action VARCHAR(255),
    status ENUM('success', 'failed'),
    details TEXT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## Controller Metodları

### AccessCodeController

#### requestAccess()
- Rate limiting: 5 deneme/saat
- Süper Admin kontrolü
- 6 haneli kod oluşturma
- E-posta gönderme
- Log kaydı

#### verifyCode()
- Rate limiting: 10 deneme/saat
- Kod doğrulama
- Session'da erişim saklama
- Başarılı/başarısız log

#### hasAccess() (Static)
- Session kontrolü
- Süre kontrolü
- Hastane ID kontrolü

#### logAccess() (Static)
- Erişim loglaması
- IP ve User Agent kaydı

## Güvenlik Önlemleri

### Rate Limiting
- Erişim talebi: 5 deneme/saat
- Kod doğrulama: 10 deneme/saat
- IP bazlı kontrol

### Kod Güvenliği
- 6 haneli rastgele kod
- 15 dakika geçerlilik
- Tek kullanımlık
- Süper Admin'e özel

### Erişim Kontrolü
- Session bazlı erişim
- Otomatik süre kontrolü
- Hastane bazlı izolasyon

## Loglama

### Log Türleri
- `request`: Erişim talebi
- `verify`: Kod doğrulama
- `access`: Veri erişimi
- `expire`: Kod süresi dolma

### Log Detayları
- IP adresi
- User Agent
- Başarı/başarısızlık durumu
- Zaman damgası
- Kullanıcı bilgisi

## Kullanım Örnekleri

### Erişim Kontrolü
```php
if (AccessCodeController::hasAccess($hospitalId)) {
    // Hasta verilerine erişim
    AccessCodeController::logAccess($hospitalId, $accessCodeId, $request);
} else {
    abort(403, 'Erişim yetkiniz yok');
}
```

### Policy Entegrasyonu
```php
public function viewPatients(User $user, Hospital $hospital): bool
{
    if ($user->isSuperAdmin()) {
        return AccessCodeController::hasAccess($hospital->id);
    }
    return $user->hospital_id === $hospital->id;
}
```

## E-posta Entegrasyonu
- Mail template oluşturulacak
- Kod bilgisi e-postada gönderilecek
- Hastane admin'i kodu Süper Admin'e iletir

## Monitoring
- Tüm erişim denemeleri loglanır
- Başarısız denemeler takip edilir
- Şüpheli aktiviteler tespit edilir
- Raporlama için veri hazır
