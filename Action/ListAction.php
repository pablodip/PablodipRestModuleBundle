<?php

/*
 * This file is part of the PablodipRestModuleBundle package.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pablodip\RestModuleBundle\Action;

use Pablodip\ModuleBundle\Action\BaseAction;

/**
 * ListAction.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class ListAction extends BaseAction
{
    /**
     * {@inheritdoc}
     */
    protected function defineConfiguration()
    {
        $this
            ->setRoute('list', '/', 'GET')
            ->setController(array($this, 'controller'))
        ;
    }

    public function controller()
    {
        $models = $this->getModule()->getMolino()->createSelectQuery($this->getModule()->getOption('modelClass'))->all();

        return $this->getModule()->getExtension('serializer')->createSerializedResponse($models);
    }
}
