
# CI4Rest

Advanced REST Controller yang dikembangkan untuk mempermudah dalam penerapan REST API

HARAP DIPERHATIKAN:
- Semua Pengaturan yang terdapat pada App.php bersifat GLOBAL untuk semua child class
- Setiap pengaturan akan berlaku di semua controller yang menggunakan extends RestController sebagai pengaturan default.
- Anda dapat override / replace pengaturan pada method atau controller anda masing-masing dengan pengaturan yang berbeda-beda
- jika Anda ingin menerapkan pengaturan pada 1 child class termasuk semua method didalamnya, Anda dapat meletakkan pada __constructor
- Semua pengaturan pada file config dapat Anda setting secara global tanpa edit config ini pada file .env Anda ( E.g: app.force_https = true )

## Installation

Install dengan menggunakan composer

```bash
  composer require ay4t/CI4Rest
```
atau menggunakan github repository dengan github token:

```bash
composer config minimum-stability dev
composer config repositories.CI4Rest vcs git@github.com:ay4t/CI4Rest.git
composer require ay4t/ci4rest:main-dev
```
    
## Contoh Penggunaan

```bash
public function index()
{
    
    /** request harus melalui protocol https */
    $this->config->force_https  = true;

    /** menggunakan otentikasi JWT */
    $this->config->rest_auth    = 'JWT';

    /** untuk mengatur output format */
    $this->config->rest_default_format        = 'json';

    /** setting true secara otomatis mendapatkan token JWT yang baru */
    $this->use_JWT_refresh_token = true;

    /** filter khusus untuk method yang hanya diperbolehkan */
    $this->config->rest_allowed_method = ['get', 'post'];

    /** filter jika ingin menggunakan hanya akses dengan AJAX / X-Requested-With:XMLHttpRequest header */
    $this->config->rest_ajax_only = true;

    /** memberikan header response untuk CORS */
    $this->config->check_cors = true;
    
    /** untuk menambahkan response secara manual */
    $this->addResponse('some_response', 'some_value');

    /** untuk menambahkan response secara manual dengan array */
    $this->addResponse([
        'some_response2' => 'some_value2',
        'some_response3' => 'some_value3',
    ]);

    return parent::index();
}
```

