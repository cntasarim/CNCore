# Güvenli PHP MVC Framework

Güvenlik odaklı, basit ve güçlü bir PHP MVC framework.

## Özellikler

- **MVC Mimarisi**: Model, View ve Controller'lar ile temiz kod ayrımı
- **Güvenlik Odaklı**: Yaygın güvenlik açıklarına karşı koruma:
  - PDO ve prepared statements ile SQL Injection koruması
  - Otomatik çıktı temizleme ile XSS koruması
  - CSRF token üretimi ve doğrulaması
  - Güvenli dosya yükleme işlemleri
  - Gelişmiş oturum güvenliği
  - Bcrypt ile şifre hashleme
  - Content Security Policy uygulaması
- **Routing Sistemi**: İsimlendirilmiş rotalar ve middleware desteği
- **Veritabanı Katmanı**: Güvenlik odaklı basit ORM benzeri fonksiyonellik
- **Middleware**: İsteği işlemek için ara katman sistemi
- **Form Doğrulama**: Dahili doğrulama ve hata yönetimi
- **Oturum Yönetimi**: Güvenli oturum yönetimi ve flash mesajları
- **Çevresel Değişkenler**: .env dosyası ile yapılandırma yönetimi
- **Önbellek Sistemi**: File ve Redis desteği
- **Veritabanı Soyutlama**: PDO tabanlı güvenli veritabanı işlemleri

## Gereksinimler

- PHP 8.0 veya üzeri
- MySQL/MariaDB
- Apache (mod_rewrite aktif) veya Nginx
- Redis (isteğe bağlı)

## Kurulum

1. Projeyi klonlayın
2. Composer bağımlılıklarını yükleyin:
   ```bash
   composer install
   ```
3. `.env.example` dosyasını `.env` olarak kopyalayın:
   ```bash
   cp .env.example .env
   ```
4. `.env` dosyasını düzenleyin:
   - Veritabanı bağlantı bilgilerini
   - Uygulama ayarlarını
   - Redis ayarlarını (kullanılacaksa)
   - Güvenlik ayarlarını
5. Veritabanı şemasını oluşturun
6. Web tarayıcısından uygulamaya erişin

## Veritabanı Kurulumu

Veritabanınızda aşağıdaki tabloyu oluşturun:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    last_login DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Dizin Yapısı

```
/
├── app/              # Uygulama dosyaları
│   ├── controllers/  # Controller sınıfları
│   ├── models/       # Model sınıfları
│   ├── views/        # View şablonları
│   ├── middleware/   # Middleware sınıfları
│   ├── core/         # Çekirdek framework dosyaları
├── config/           # Yapılandırma dosyaları
├── public/           # Genel erişime açık dosyalar
│   ├── index.php     # Uygulama giriş noktası
│   ├── assets/       # Statik dosyalar (CSS, JS, resimler)
│   ├── uploads/      # Yüklenen dosyalar
├── .env             # Çevresel değişkenler
├── .env.example     # Örnek çevresel değişkenler
├── .htaccess        # Apache yapılandırması
```

## Rota Oluşturma

`config/routes.php` dosyasında rota tanımlayın:

```php
use App\Core\Router;
use App\Middleware\AuthMiddleware;

// Temel rota
Router::get('/hakkimizda', 'HomeController@about', 'about');

// Parametreli rota
Router::get('/kullanici/{id}', 'UserController@show', 'user.show');

// Middleware'li rota
Router::get('/panel', 'DashboardController@index', 'dashboard', [new AuthMiddleware()]);
```

## Controller Oluşturma

`app/controllers/` dizininde controller sınıfları oluşturun:

```php
<?php
namespace App\Controllers;

use App\Core\Controller;

class OrnekController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Örnek Sayfa',
            'message' => 'Merhaba Dünya!'
        ];
        
        $this->view('ornek.index', $data);
    }
}
```

## Model Oluşturma

`app/models/` dizininde model sınıfları oluşturun:

```php
<?php
namespace App\Models;

use App\Core\Model;

class Ornek extends Model
{
    protected $table = 'ornekler';
    
    // Özel metodlar ekleyin
}
```

## View Oluşturma

`app/views/` dizininde view dosyaları oluşturun:

```php
<?php
$pageTitle = 'Örnek Sayfa';
ob_start();
?>

<div class="card">
    <div class="card-body">
        <h1><?= $e($message) ?></h1>
    </div>
</div>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/views/layouts/main.php';
?>
```

## Güvenlik Tavsiyeleri

- View'larda çıktıları her zaman `$e()` fonksiyonu ile temizleyin
- Formlarda mutlaka `$csrf()` ile CSRF token ekleyin
- Tüm veritabanı sorguları için prepared statements kullanın
- Kullanıcı girdilerini doğrulayın
- Dosya yüklemeleri için dahili güvenlik özelliklerini kullanın

## Lisans

Bu proje açık kaynak yazılımdır.