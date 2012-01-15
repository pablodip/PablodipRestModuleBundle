<?php

/*
 * This file is part of the PablodipRestModuleBundle package.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pablodip\RestModuleBundle\ModelManager\Mandango;

use Pablodip\RestModuleBundle\Module\RestModule;
use Pablodip\ModuleBundle\Extension\ModelManager\MandangoExtension;

/**
 * MandangoRestModule.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
abstract class MandangoRestModule extends RestModule
{
    /**
     * {@inheritdoc}
     */
    protected function preConfigure()
    {
        parent::preConfigure();

        $extension = new MandangoExtension();
        $extension->apply($this);
    }
}
