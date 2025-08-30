@component('mail::message')
# Merhaba!

E-posta adresinizi doğrulamak için aşağıdaki butona tıklayın.

@component('mail::button', ['url' => $url])
E-posta Adresini Doğrula
@endcomponent

Eğer bir hesap oluşturmadıysanız, başka bir işlem yapmanıza gerek yoktur.

Saygılarımızla,<br>
{{ config('app.name') }}

@component('mail::subcopy')
Eğer "E-posta Adresini Doğrula" butonuna tıklamakta sorun yaşıyorsanız, aşağıdaki URL'yi web tarayıcınıza kopyalayıp yapıştırın: {{ $url }}
@endcomponent
@endcomponent
