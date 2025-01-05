<?php

namespace Noobtrader\Imagegenerator;

use Illuminate\Support\Facades\File;


class ImageGenerate {

    public static function generateImage($name){
        // Prepare the name for the text and file
        $nameText = strtoupper($name);
        $nameText = str_replace(' ', '', $nameText);
        $initialLength = config('profile-imagegenerator.name_initial_length', 2); // Default to 2 if not set
        $nameInitial = substr($nameText, 0, $initialLength); // For display
        $fileName = $nameInitial.time(). '.png'; // For saving

        // Image dimensions
        $width = min(config('profile-imagegenerator.img_width', 200), 512); // Max width 512
        $height = min(config('profile-imagegenerator.img_height', 200), 512); // Max height 512

        // Create the image
        $image = imagecreate($width, $height);

        // Random background color
        $background_color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));

        // Determine text color based on background brightness
        $background_rgb = imagecolorsforindex($image, $background_color);
        $background_brightness = ($background_rgb['red'] * 299 + $background_rgb['green'] * 587 + $background_rgb['blue'] * 114) / 1000;

        $text_color = ($background_brightness > 128) 
            ? imagecolorallocate($image, 0, 0, 0) // Dark text color for light background
            : imagecolorallocate($image, 255, 255, 255); // Light text color for dark background

        // Set the font file path and font size
        $fontFile = public_path('imagegenerator/fonts/' . config('profile-imagegenerator.font_file', 'LobsterTwo-Regular.ttf'));
        // Validate the font file
        if (!file_exists($fontFile)) {
            throw new \Exception("Font file not found: " . $fontFile);
        }

        // Check the file extension
        if (pathinfo($fontFile, PATHINFO_EXTENSION) !== 'ttf') {
            throw new \Exception("Invalid font file: '{$fontFile}'. Only .ttf files are supported.");
        }
        $fontSize = config('profile-imagegenerator.font_size', 60);

        // Text position
        $bbox = imagettfbbox($fontSize, 0, $fontFile, $nameInitial);
        $textWidth = abs($bbox[4] - $bbox[0]);
        $textHeight = abs($bbox[5] - $bbox[1]);
        $x = ($width - $textWidth) / 2;
        $y = ($height + $textHeight) / 2;

        // Add the text
        imagettftext($image, $fontSize, 0, $x, $y, $text_color, $fontFile, $nameInitial);

        // Save the image as PNG
        $imageDir = public_path(config('profile-imagegenerator.save_img_path', 'imagegenerator/images') . '/');

        if (!File::exists($imageDir)) {
            File::makeDirectory($imageDir, 0777, true, true);
        }
        imagepng($image, $imageDir . $fileName);

        // Destroy the image to free up memory
        imagedestroy($image);

        return "Image generated for $name and saved to public/images/";
    }

}