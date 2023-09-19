<?php

namespace App\Form;

use Symfony\Component\Form\FormInterface;

class FormHandler
{
    public ?array $errors;

    public FormInterface $form;

    public function __construct(FormInterface $form)
    {
        $this->form = $form;
    }

    public function getEveryFields(): array
    {
        $fields = [];
        foreach ($this->form->all() as $field) {
            $fields[] = $field->getName();
        }
        return $fields;
    }

    public function handle($data): bool
    {
        if ($data == null) {
            $this->errors = ['Error' => 'Invalid data','fields' => $this->getEveryFields()];
            return false;
        }
        // Submit form with JSON data
        $this->form->submit($data);
        if (!$this->form->isSubmitted() || !$this->form->isValid()) {
            // Handle form validation errors and return error response
            foreach ($this->form->getErrors(true, true) as $error) {
                $fieldName = $error->getOrigin() ? $error->getOrigin()->getName() : '_global';
                $this->errors[$fieldName][] = $error->getMessage();
            }
            return false;
        }
        return true;
    }
}
