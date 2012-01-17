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
 * UpdateAction.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class UpdateAction extends BaseAction
{
    /**
     * {@inheritdoc}
     */
    protected function defineConfiguration()
    {
        $this
            ->setRoute('update', '/{id}', 'PUT')
            ->setController(array($this, 'controller'))
        ;
    }

    public function controller($id)
    {
        $model = $this->getModule()->getMolino()->findOneById($this->getModule()->getOption('modelClass'), $id);
        if (!$model) {
            return $this->getModule()->getExtension('serializer')->createSerializedNotFoundResponse();
        }

        $data = $this->get('request')->request->all();
        if ($this->getModule()->hasExtraFields($data)) {
            return $this->getModule()->createExtraFieldsErrorResponse();
        }
        foreach ($data as $name => $value) {
            $model->{'set'.ucfirst($name)}($value);
        }

        if (count($errors = $this->get('validator')->validate($model))) {
            return $this->getModule()->createValidationFailsErrorResponse($errors);
        }

        $this->getModule()->getMolino()->save($model);

        return $this->getModule()->getExtension('serializer')->createSerializedResponse($model);
    }
}
