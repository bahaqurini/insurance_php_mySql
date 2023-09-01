<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit27bbf8c6fc725364d7d22fc1a22f4afe
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit27bbf8c6fc725364d7d22fc1a22f4afe::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit27bbf8c6fc725364d7d22fc1a22f4afe::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit27bbf8c6fc725364d7d22fc1a22f4afe::$classMap;

        }, null, ClassLoader::class);
    }
}