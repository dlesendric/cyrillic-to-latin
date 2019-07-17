<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit722e5c232ea298237adc2a5075f06ca0
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Sepia\\Test\\' => 11,
            'Sepia\\PoParser\\' => 15,
        ),
        'D' => 
        array (
            'DLS\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Sepia\\Test\\' => 
        array (
            0 => __DIR__ . '/..' . '/sepia/po-parser/tests',
        ),
        'Sepia\\PoParser\\' => 
        array (
            0 => __DIR__ . '/..' . '/sepia/po-parser/src',
        ),
        'DLS\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit722e5c232ea298237adc2a5075f06ca0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit722e5c232ea298237adc2a5075f06ca0::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
