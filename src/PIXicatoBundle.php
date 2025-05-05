<?php

namespace Phillarmonic\PIXicato;

use Phillarmonic\PIXicato\DependencyInjection\CompilerPass\TranslationCompilerPass;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Phillarmonic\PIXicato\DependencyInjection\PIXicatoExtension;

class PIXicatoBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new PIXicatoExtension();
        }
        return $this->extension;
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new TranslationCompilerPass());
    }
}
