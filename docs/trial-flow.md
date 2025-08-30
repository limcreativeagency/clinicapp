# Trial Flow Dokümantasyonu

## Genel Bakış
Bu dokümantasyon, hastane yöneticilerinin e-posta doğrulaması sonrası 14 günlük deneme sürecini açıklar.

## Akış

### 1. Hastane Kaydı
- Admin `/hospital-signup` sayfasından hastane ve yönetici hesabını oluşturur
- Hastane `status: pending`, `subscription_status: trial` olarak kaydedilir
- Admin kullanıcısı `status: pending` olarak oluşturulur
- E-posta doğrulama maili gönderilir

### 2. E-posta Doğrulama
- Admin e-posta doğrulamasını yapar
- `StartTrialOnEmailVerification` listener tetiklenir
- Hastane trial başlatılır:
  - `trial_started_at = now()`
  - `trial_ends_at = now() + 14 days`
  - `status = active`
  - `subscription_status = trial`
- Admin kullanıcısı aktif edilir: `status = active`

### 3. Trial Süreci
- Dashboard'da kalan gün sayısı gösterilir
- `Hospital::getRemainingTrialDays()` metodu kullanılır
- Trial süresi boyunca tüm özellikler aktif

### 4. Trial Sonu
- `php artisan trials:expire` komutu günlük çalıştırılır
- Süresi dolan hastaneler `subscription_status = expired` yapılır
- CRUD işlemleri kilitlenir
- "Paket satın alın" uyarısı gösterilir

## Komutlar

### Trial Süresi Kontrolü
```bash
php artisan trials:expire
```

### Cron Job (Önerilen)
```bash
# /etc/crontab
0 0 * * * cd /path/to/project && php artisan trials:expire
```

## Model Metodları

### Hospital Model
- `isInTrial()`: Trial durumunda mı?
- `isTrialExpired()`: Trial süresi dolmuş mu?
- `getRemainingTrialDays()`: Kalan gün sayısı
- `startTrial()`: Trial başlat
- `expireTrial()`: Trial bitir

### User Model
- `isActive()`: Kullanıcı aktif mi?
- `updateLastLogin()`: Son giriş zamanını güncelle

## Event Listener
- `StartTrialOnEmailVerification`: E-posta doğrulama sonrası trial başlatır
- `EventServiceProvider`'da kayıtlı

## Güvenlik
- Trial süresi dolduğunda tüm yönetim işlemleri engellenir
- Sadece admin kullanıcıları için trial başlatılır
- E-posta doğrulaması zorunludur
