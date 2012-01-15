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
 * ShowAction.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class ShowAction extends BaseAction
{
    /**
     * {@inheritdoc}
     */
    protected function defineConfiguration()
    {
        $this
            ->setRoute('show', '/{id}', 'GET')
            ->setController(array($this, 'controller'))
        ;
    }

    public function controller($id)
    {
        $model = $this->getModule()->getMolino()->findOneById($this->getModule()->getOption('modelClass'), $id);
        if (!$model) {
            return $this->getModule()->getExtension('serializer')->createSerializedNotFoundResponse();
        }

        return $this->getModule()->getExtension('serializer')->createSerializedResponse($model);
    }
}
