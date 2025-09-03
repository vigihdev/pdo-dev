<?php


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Dotenv\Dotenv;
use Vigihdev\PdoDev\ServiceLocator;

$container = new ContainerBuilder();
$loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/config'));
$loader->load('services.yaml');

// Load Dotenv
(new Dotenv())->load(__DIR__ . '/.env');

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
