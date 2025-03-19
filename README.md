# ProfileImageGenerator

## Introduction
The **ProfileImageGenerator** package is a Laravel package for generating customizable images. It provides configuration options for image dimensions, fonts, and more.

---

## Supported Versions
- Laravel 10
- Laravel 11

---

## Installation

### Step 1: Install via Composer
```bash
composer require noobtrader/image-generator
```

---

### Step 2: Publish Vendor Assets
#### Laravel 11

1. Add the service provider in `bootstrap/providers.php`:

   ```php
   <?php

   return [
       App\Providers\AppServiceProvider::class,
       \Noobtrader\Imagegenerator\ImageGenerateServiceProvider::class
   ];
   ```

2. Run the vendor publish command:

   ```bash
   php artisan vendor:publish --provider="Noobtrader\Imagegenerator\ImageGenerateServiceProvider"
   ```
   - Select the **Noobtrader\Imagegenerator\ImageGenerateServiceProvider** (option `7` in the list).

   Output:
   ```
   Copying file [vendor/noobtrader/imagegenerator/config/imagegenerator.php] to [config/profile-imagegenerator.php]  DONE
   Copying directory [vendor/noobtrader/imagegenerator/src/resources/fonts] to [public/imagegenerator/fonts]
   ```

#### Laravel 10

1. Add the service provider in `config/app.php` under `providers`:

   ```php
   'providers' => ServiceProvider::defaultProviders()->merge([
       App\Providers\AppServiceProvider::class,
       App\Providers\AuthServiceProvider::class,
       App\Providers\EventServiceProvider::class,
       App\Providers\RouteServiceProvider::class,
       Noobtrader\Imagegenerator\ImageGenerateServiceProvider::class
   ])->toArray(),
   ```

2. Add the facade alias in `config/app.php` under `aliases`:

   ```php
   'aliases' => Facade::defaultAliases()->merge([
       'ImageGenerate' => Noobtrader\Imagegenerator\Facades\ImageGenerateFacade::class
   ])->toArray(),
   ```

3. Run the vendor publish command:

   ```bash
   php artisan vendor:publish
   ```

---

## Configuration

After publishing the vendor assets, the default configuration file `profile-imagegenerator.php` is created in the `config` folder.

### Default Configuration (`config/profile-imagegenerator.php`):
```php
<?php

return [
    'save_img_path' => env('IMAGE_PATH', '/imagegenerator/images'),
    'img_width' => env('IMAGE_WIDTH', '200'),
    'img_height' => env('IMAGE_HEIGHT', '200'),
    'font_size' => env('FONT_SIZE', '60'),
    'font_file' => env('FONT_FILE', 'LobsterTwo-Regular.ttf'),
    'name_initial_length' => env('NAME_INITIAL_LENGTH', 2),
];
```

### Customizing Configuration via `.env`
You can override the configuration by adding the following variables to your `.env` file:
```env
IMAGE_PATH="imagegenerator/images"
IMAGE_WIDTH="200"
IMAGE_HEIGHT="200"
FONT_SIZE="60"
FONT_FILE="LobsterTwo-Regular.ttf"
NAME_INITIAL_LENGTH="2"
```

---

## Using Custom Fonts

1. Download the desired font file (e.g., `LobsterTwo-Regular.ttf`).
2. Place the font file in the `public/imagegenerator/fonts` directory.
3. Update the `.env` file:

   ```env
   FONT_FILE="LobsterTwo-Regular.ttf"
   ```

---

## Usage

Generate an image using the `generateImage` method:

```php
use ImageGenerate;

$image = ImageGenerate::generateImage('Anik');
```

### Git Ignore Instruction

To prevent Git from tracking the generated images, add the following line to your application's `.gitignore` file:

`/public/imagegenerator/images/`

This ensures that dynamically generated images are not included in version control.


---

## License
This package is licensed under [MIT License](LICENSE).


---

## GitHub Repository
You can find the GitHub repository for this package [here](https://github.com/anikrahman0/image-generator.git).


---

