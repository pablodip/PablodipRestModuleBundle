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
 * CreateAction.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class CreateAction extends BaseAction
{
    /**
     * {@inheritdoc}
     */
    protected function defineConfiguration()
    {
        $this
            ->setRoute('create', '/', 'POST')
            ->setController(array($this, 'controller'))
        ;
    }

    public function controller()
    {
        $model = $this->getModule()->getMolino()->createModel($this->getModule()->getOption('modelClass'));

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

        $this->getModule()->getMolino()->saveModel($model);

        return $this->getModule()->getExtension('serializer')->createSerializedResponse($model);
    }
}
