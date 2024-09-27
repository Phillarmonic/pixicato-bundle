<?php

namespace Phillarmonic\PIXicato\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TranslationCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('translator.default')) {
            return;
        }

        $translator = $container->getDefinition('translator.default');
        $translator->addMethodCall('addResource', [
            'yaml',
            __DIR__.'/../../Resources/translations/validators.en.yaml',
            'en',
            'validators'
        ]);
        $translator->addMethodCall('addResource', [
            'yaml',
            __DIR__.'/../../Resources/translations/validators.pt_BR.yaml',
            'pt_BR',
            'validators'
        ]);
        $translator->addMethodCall('addResource', [
            'yaml',
            __DIR__.'/../../Resources/translations/validators.fr.yaml',
            'fr',
            'validators'
        ]);
        $translator->addMethodCall('addResource', [
            'yaml',
            __DIR__.'/../../Resources/translations/validators.de.yaml',
            'de',
            'validators'
        ]);
        $translator->addMethodCall('addResource', [
            'yaml',
            __DIR__.'/../../Resources/translations/validators.ja.yaml',
            'ja',
            'validators'
        ]);
    }
}