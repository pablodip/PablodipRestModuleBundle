<?php

/*
 * This file is part of the PablodipRestModuleBundle package.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pablodip\RestModuleBundle\Module;

use Pablodip\ModuleBundle\Extension\Molino\DoctrineORMMolinoExtension;

/**
 * DoctrineORMRestModule.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
abstract class DoctrineORMRestModule extends RestModule
{
    /**
     * {@inheritdoc}
     */
    protected function registerMolinoExtension()
    {
        return new DoctrineORMMolinoExtension();
    }
}
