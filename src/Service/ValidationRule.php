<?php
namespace Emotion\Service;

class ValidationRule
{
    /**
     * @var string
     */
    private $fieldName;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $ruleType;


    public function __construct(
        $fieldName,
        $message,
        $ruleType
    ) {
        $this->fieldName = $fieldName;
        $this->message = $message;
        $this->ruleType = $ruleType;
    }

    /**
     * @return string
     */
    public function getFieldName() : string
    {
        return $this->fieldName;
    }

    /**
     * @return string
     */
    public function getMessage() : string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getRuleType() : string
    {
        return $this->ruleType;
    }
}