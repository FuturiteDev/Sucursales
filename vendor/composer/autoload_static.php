<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8c4ecf63fc3e33c164934d234a93541c
{
    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'OngoingErp\\Sucursales\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'OngoingErp\\Sucursales\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8c4ecf63fc3e33c164934d234a93541c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8c4ecf63fc3e33c164934d234a93541c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit8c4ecf63fc3e33c164934d234a93541c::$classMap;

        }, null, ClassLoader::class);
    }
}
