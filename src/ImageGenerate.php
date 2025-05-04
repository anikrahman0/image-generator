<?php

namespace Noobtrader\Imagegenerator;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class ImageGenerate {

    public static function generateImage($name)
    {
        $nameText = strtoupper(str_replace(' ', '', $name));
        $initialLength = config('profile-imagegenerator.name_initial_length', 2);
        $nameInitial = substr($nameText, 0, $initialLength);
        $fileName = $nameInitial . time() . '.png';

        $width = min(config('profile-imagegenerator.img_width', 200), 512);
        $height = min(config('profile-imagegenerator.img_height', 200), 512);

        $image = imagecreate($width, $height);
        $bgColor = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
        $bgRGB = imagecolorsforindex($image, $bgColor);
        $brightness = ($bgRGB['red'] * 299 + $bgRGB['green'] * 587 + $bgRGB['blue'] * 114) / 1000;
        $textColor = $brightness > 128 ? imagecolorallocate($image, 0, 0, 0) : imagecolorallocate($image, 255, 255, 255);

        $fontFile = public_path('imagegenerator/fonts/' . config('profile-imagegenerator.font_file'));
        if (!file_exists($fontFile)) {
            throw new \Exception("Font file not found: $fontFile");
        }

        $fontSize = config('profile-imagegenerator.font_size');
        $bbox = imagettfbbox($fontSize, 0, $fontFile, $nameInitial);
        $textWidth = abs($bbox[4] - $bbox[0]);
        $textHeight = abs($bbox[5] - $bbox[1]);
        $x = ($width - $textWidth) / 2;
        $y = ($height + $textHeight) / 2;

        imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontFile, $nameInitial);

        $path = trim(config('profile-imagegenerator.save_img_path'), '/');
        $pathType = config('profile-imagegenerator.storage_disk');
        $fullFilePath = $path . '/' . $fileName;

        
        if ($pathType === 'do_spaces' || $pathType === 's3' || $pathType === 'minio') {
            // cloud storage (disk name is same as path)
            // Save to temporary path
            $tempPath = storage_path('app/temp/' . $fileName);

            if (!File::exists(dirname($tempPath))) {
                File::makeDirectory(dirname($tempPath), 0777, true, true);
            }

            imagepng($image, $tempPath); // Save image
            imagedestroy($image); // Free up memory
            // Upload to DigitalOcean Spaces
            $uploaded = Storage::disk($pathType)->putFileAs(
                '', // keep empty unless you're nesting bucket root
                new \Illuminate\Http\File($tempPath),
                $fullFilePath,
                'public'
            );
            // dd($tempPath, $uploaded, $fullFilePath);

            // Optionally check upload success
            if (!$uploaded) {
                throw new \Exception('File upload failed');
            }

            // Delete local temp file
            File::delete($tempPath);

            // Return the public URL
            return $fullFilePath;
        } else {
            // local storage
            $localPath = public_path($fullFilePath);
            if (!File::exists(dirname($localPath))) {
                File::makeDirectory(dirname($localPath), 0777, true, true);
            }

            imagepng($image, $localPath);
            imagedestroy($image);

            return $fullFilePath;
        }
    }
}
