<?php

namespace Phillarmonic\PIXicato\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TranslationCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('translator.default')) {
            return;
        }

        $translator = $container->getDefinition('translator.default');

        $languages = ['en', 'pt_BR', 'fr', 'de', 'ja'];
        $domains = ['validators', 'messages'];

        foreach ($languages as $lang) {
            foreach ($domains as $domain) {
                $translator->addMethodCall('addResource', [
                    'yaml',
                    __DIR__."/../../translations/{$lang}/{$domain}.{$lang}.yaml",
                    $lang,
                    $domain
                ]);
            }
        }
    }
}