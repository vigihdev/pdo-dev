<?php

$vendorDir = null;
// bootstrap.php - Most reliable approach
// Always try relative path first
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    $vendorDir = dirname(__DIR__ . '/vendor/autoload.php');
    require_once __DIR__ . '/vendor/autoload.php';
}
// Then try parent directory (for CLI tools)
elseif (file_exists(__DIR__ . '/../../autoload.php')) {
    $vendorDir = dirname(__DIR__ . '/../../autoload.php');
    require_once __DIR__ . '/../../autoload.php';
}
// Then try Composer's vendor directory
elseif (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    $vendorDir = dirname(__DIR__ . '/../autoload.php');
    require_once __DIR__ . '/../vendor/autoload.php';
}
// Finally, use include path
else {
    $includePaths = explode(PATH_SEPARATOR, get_include_path());
    foreach ($includePaths as $path) {
        $vendorPath = $path . '/vendor/autoload.php';
        if (file_exists($vendorPath)) {
            $vendorDir = $path . '/vendor';
            require_once $vendorPath;
            break;
        }
    }
}

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Dotenv\Dotenv;
use Vigihdev\PdoDev\ServiceLocator;

$container = new ContainerBuilder();
$loader = new YamlFileLoader($container, new FileLocator(dirname($vendorDir) . '/../../config'));
$loader->load('services.yaml');

// Load Dotenv
(new Dotenv())->load(dirname($vendorDir) . '/../../.env');

foreach ((new Dotenv())->parse(file_get_contents(__DIR__ . '/.env')) as $key => $value) {
    $key = mb_strtolower(preg_replace('/[\_]+/m', '.', $key));

    // ✅ ABSOLUTE PATH GUARANTEE
    if (!empty($value) && (is_dir($value) || file_exists($value))) {
        $realPath = realpath($value);
        if ($realPath !== false) {
            $value = $realPath;
        }
    }
    $container->setParameter($key, $value);
}

$container->compile();
ServiceLocator::setContainer($container);
