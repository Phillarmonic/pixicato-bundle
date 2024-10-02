<?php

namespace Phillarmonic\PIXicato;

use Phillarmonic\PIXicato\DependencyInjection\CompilerPass\TranslationCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Phillarmonic\PIXicato\DependencyInjection\PIXicatoExtension;

class PIXicatoBundle extends Bundle
{
    public function getContainerExtension(): ?\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new PIXicatoExtension();
        }
        return $this->extension;
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new TranslationCompilerPass());
    }
}
