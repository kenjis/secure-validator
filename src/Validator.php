<?php

namespace Kenjis\Validation;

use Sirius\Filtration\Filtrator;

class Validator
{
    private $validator;
    private $filter;
    
    public function __construct()
    {
        $this->validator = new ValidatorEx();
        $this->filter    = new Filtrator();
    }

    /**
     * Add validation rule
     * 
     * @param type $selector
     * @param type $name
     * @param type $options
     * @param type $messageTemplate
     * @param type $label
     * @return \Kenjis\Validation\Validator
     */
    public function add($selector, $name = null, $options = null, $messageTemplate = null, $label = null)
    {
        if (func_num_args() == 1) {
            $this->validator->add($selector);
            return $this;
        }

        $this->validator->add(
            $selector, $name, $options, $messageTemplate, $label
        );
        
        return $this;
    }

    /**
     * Remove validation rule
     * 
     * @param type $selector
     * @param boolean $name
     * @param type $options
     * @return \Kenjis\Validation\Validator
     */
    public function remove($selector, $name = true, $options = null)
    {
        $this->validator->remove($selector, $name, $options);
        
        return $this;
    }

    /**
     * Add filtering rule
     * 
     * @param string $selector            data selector
     * @param mixed $callbackOrFilterName rule name or true if all rules should be deleted for that selector
     * @param mixed $options              rule options, necessary for rules that depend on params for their ID
     * @param bool $recursive             
     * @param int $priority               
     * @return \Kenjis\Validation\Validator
     */
    public function filter($selector, $callbackOrFilterName = null, $options = null, $recursive = false, $priority = 0)
    {
        $this->filter->add(
            $selector, $callbackOrFilterName, $options, $recursive, $priority
        );
        
        return $this;
    }

    /**
     * Validate data
     * 
     * @param mixed $data array to be validated
     * @return bool
     */
    public function validate($data = null)
    {
        $filtered = $this->filter->filter($data);
        return $this->validator->validate($filtered);
    }

    /**
     * Get validated data
     * 
     * @param string $item
     * @return string|array
     */
    public function getValidated($item = null)
    {
        return $this->validator->getValidated($item);
    }

    /**
     * Get messages
     * 
     * @param type $item
     * @return array
     */
    public function getMessages($item = null)
    {
        return $this->validator->getMessages($item);
    }

    public function getInputValue($item)
    {
        return $this->validator->getInputValue($item);
    }
}
