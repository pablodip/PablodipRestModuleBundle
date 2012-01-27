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

use Pablodip\ModuleBundle\Module\Module;
use Pablodip\ModuleBundle\Extension\Model\ModelExtension;
use Pablodip\ModuleBundle\Extension\Serializer\SymfonySerializerExtension;
use Pablodip\RestModuleBundle\Action\ListAction;
use Pablodip\RestModuleBundle\Action\CreateAction;
use Pablodip\RestModuleBundle\Action\ShowAction;
use Pablodip\RestModuleBundle\Action\UpdateAction;
use Pablodip\RestModuleBundle\Action\DeleteAction;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * RestModule.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
abstract class RestModule extends Module
{
    /**
     * {@inheritdoc}
     */
    protected function registerExtensions()
    {
        return array_merge(parent::registerExtensions(), array(
            new ModelExtension(),
            $this->registerMolinoExtension(),
            $this->registerSerializerExtension(),
        ));
    }

    /**
     * Returns the molino extension.
     *
     * @return BaseMolinoExtension A molino extension.
     */
    abstract protected function registerMolinoExtension();

    /**
     * Returns the serializer extension.
     *
     * @return BaseSerializerExtension A serializer extension.
     */
    protected function registerSerializerExtension()
    {
        return new SymfonySerializerExtension();
    }

    /**
     * {@inheritdoc}
     */
    protected function defineConfiguration()
    {
        $this->addActions(array(
            new ListAction(),
            new CreateAction(),
            new ShowAction(),
            new UpdateAction(),
            new DeleteAction(),
        ));

        $this->addOptions(array(
            'extra_fields_message'        => 'Extra fields.',
            'extra_fields_error_code'     => 400,
            'validation_fails_message'    => 'Validation fails.',
            'validation_fails_error_code' => 400,
        ));
    }

    /**
     * Returns if there are extra fields in some data.
     *
     * @param array $data The data.
     *
     * @return Boolean If there are extra fields.
     */
    public function hasExtraFields(array $data)
    {
        return (Boolean) array_diff(array_keys($data), $this->getOption('model_fields')->keys());
    }

    /**
     * Create an extra fields error response.
     *
     * @return Response An extra fields error response.
     */
    public function createExtraFieldsErrorResponse()
    {
        $message = $this->getOption('extra_fields_message');
        $errorCode = $this->getOption('extra_fields_error_code');

        return $this->getExtension('serializer')->createSerializedResponse(array('message' => $message), $errorCode);
    }

    /**
     * Creates a validation fails error response.
     *
     * @param ConstraintViolationList $violationList The violation list.
     *
     * @return Response A validation fails error response.
     */
    public function createValidationFailsErrorResponse(ConstraintViolationList $violationList)
    {
        $message = $this->getOption('validation_fails_message');
        $errorCode = $this->getOption('validation_fails_error_code');

        $errors = array();
        foreach ($violationList as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        $content = array('message' => $message, 'errors' => $errors);

        return $this->getExtension('serializer')->createSerializedResponse($content, $errorCode);
    }
}
