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

use Pablodip\ModuleBundle\Action\BaseRouteAction;

/**
 * DeleteAction.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class DeleteAction extends BaseRouteAction
{
    /**
     * {@inheritdoc}
     */
    protected function defineConfiguration()
    {
        $this
            ->setRoute('delete', '/{id}', 'DELETE')
            ->setController(array($this, 'controller'))
        ;
    }

    public function controller($id)
    {
        $model = $this->getMolino()->findOneById($this->getModule()->getOption('model_class'), $id);
        if (!$model) {
            return $this->getModule()->getExtension('serializer')->createSerializedNotFoundResponse();
        }

        $this->getMolino()->delete($model);

        return $this->getModule()->getExtension('serializer')->createSerializedResponse('', 204);
    }
}
