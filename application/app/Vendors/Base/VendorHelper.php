<?php

namespace App\Vendors\Base;

use App\Helpers\Helper;
use Illuminate\Support\Str;
use Spatie\PdfToImage\Pdf;
use thiagoalessio\TesseractOCR\TesseractOCR;

class VendorHelper
{

    private static $vendors = [
        [
            'name' => 'McDonald',
            'class' => 'McDonald',
            'hosts' => [
                'ca.mcdonalds.com'
            ]
        ],
        [
            'name' => 'Green P',
            'class' => 'Green P',
            'hosts' => [
                'greenp.com'
            ]
        ],
        [
            'name' => 'Zara',
            'class' => 'Zara',
            'hosts' => [
                'zara.com'
            ]
        ],
        [
            'name' => 'Apple',
            'class' => 'Apple',
            'hosts' => [
                'apple.com'
            ]
        ],
        [
            'name' => 'Sephora',
            'class' => 'Sephora',
            'hosts' => [
                'sephora.com'
            ]
        ],
        [
            'name' => 'SportChek',
            'class' => 'SportChek',
            'hosts' => [
                'sportchek.ca',
                'sportcheksurvey.ca',
            ]
        ],
        [
            'name' => 'PHO NGOC YEN',
            'class' => 'PHONGOCYEN',
            'hosts' => [
                'ngocyenrestaurant.com'
            ]
        ],
        [
            'name' => 'IKEA',
            'class' => 'IKEA',
            'hosts' => [
                'ikea.com',
                'ikea'
            ]
        ],
        [
            'name' => 'El Catrin Destileria',
            'class' => 'Elcatrin',
            'hosts' => [
                'elcatrin.ca'
            ]
        ],
        [
            'name' => 'Moneris',
            'class' => 'Moneris',
            'hosts' => [
                'moneris.com'
            ]
        ],
        [
            'name' => "Riley's",
            'class' => 'Riley',
            'hosts' => [
                'riley'
            ]
        ],
        [
            'name' => "Bestbuy",
            'class' => 'Bestbuy',
            'hosts' => [
                'bestbuy.ca'
            ]
        ],
        [
            'name' => "Bath and Body Works",
            'class' => 'BathBodyWorks',
            'hosts' => [
                'bathandbodyworks.ca'
            ]
        ],
    ];

    public static function getVendor($plainText)
    {
        foreach (self::$vendors as $vendor) {
            foreach ($vendor['hosts'] as $host) {
                if (Str::contains(Str::lower($plainText), Str::lower($host))) {
                    return $vendor;
                }
            }
        }
        return null;
    }

    public static function getVendorClass($vendorClass)
    {
        $vendorClass = preg_replace('/\s+/', '', $vendorClass);
        return "App\Vendors\\Market\\$vendorClass";
    }

    public static function getTempDir()
    {
        $tempPath = storage_path('app/tmp');
        if (!is_dir($tempPath)) {
            mkdir($tempPath, 0777, true);
        }
        return $tempPath;
    }

    public static function pdfOCR($pdfPath)
    {
        $result = null;
        try {
            $tmpDir = self::getTempDir();
            $pdf = new Pdf($pdfPath);
            $pdf->setResolution(300);
            $images = $pdf->saveAllPagesAsImages($tmpDir);
            if (count($images) > 0) {
                $result = '';
            }
            foreach ($images as $imagePath) {
                $tesseract = new TesseractOCR($imagePath);
                // $tesseract->setLanguage('eng'); 
                $result .= $tesseract->run() . '\n';
            }
            foreach ($images as $imagePath) {
                unlink($imagePath);
            }
        } catch (\Exception $e) {
            Helper::saveLogs("pdfOcr did not work", 'pdfocr', [
                'exception' => $e,
                'pdfPath' => $pdfPath
            ]);
        }
        return $result;
    }

}