<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Mandango\MandangoBundle\MandangoBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Pablodip\ModuleBundle\PablodipModuleBundle(),
            new Pablodip\RestModuleBundle\PablodipRestModuleBundle(),
            new Pablodip\RestModuleTestBundle\PablodipRestModuleTestBundle(),
        );

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config.yml');
    }
}
