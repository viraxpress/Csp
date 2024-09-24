<?php
/**
 * ViraXpress - https://www.viraxpress.com
 *
 * LICENSE AGREEMENT
 *
 * This file is part of the ViraXpress package and is licensed under the ViraXpress license agreement.
 * You can view the full license at:
 * https://www.viraxpress.com/license
 *
 * By utilizing this file, you agree to comply with the terms outlined in the ViraXpress license.
 *
 * DISCLAIMER
 *
 * Modifications to this file are discouraged to ensure seamless upgrades and compatibility with future releases.
 *
 * @category    ViraXpress
 * @package     ViraXpress_CSP
 * @author      ViraXpress
 * @copyright   © 2024 ViraXpress (https://www.viraxpress.com/)
 * @license     https://www.viraxpress.com/license
 */

namespace ViraXpress\CSP\Block\Adminhtml\Form\Field;

use Ewall\CSP\Model\Source\PolicyType;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

class PolicyTypeSelector extends Select
{
    /** @var PolicyType */
    private $policyTypeSource;

    /**
     * @param Context $context
     * @param PolicyType $policyTypeSource
     * @param array $data
     */
    public function __construct(
        Context $context,
        PolicyType $policyTypeSource,
        array $data = []
    ) {
        $this->policyTypeSource = $policyTypeSource;
        parent::__construct($context, $data);
    }

    /**
     * Set "name" attribute for <select> element
     *
     * @param string $name
     * @return $this
     */
    public function setInputName(string $name): PolicyTypeSelector
    {
        return $this->setName($name);
    }

    /**
     * Set "id" attribute for <select> element
     *
     * @param string $id
     * @return $this
     */
    public function setInputId(string $id): PolicyTypeSelector
    {
        return $this->setId($id);
    }

    /**
     * Render block HTML
     *
     * @return string
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->policyTypeSource->toOptionArray());
        }
        return parent::_toHtml();
    }
}
