<?php

namespace Phillarmonic\PIXicato\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class PIXicatoExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        // If you need to perform any additional configuration, you can do it here
        // For example, you might want to process configuration options:
        // $configuration = new Configuration();
        // $config = $this->processConfiguration($configuration, $configs);
    }
}
