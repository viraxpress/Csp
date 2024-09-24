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

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\BlockInterface;

class PolicyCustomizations extends AbstractFieldArray
{
    /**
     * Renderer for the policy type column.
     *
     * @var BlockInterface
     */
    private $typeRenderer;

    /**
     * Prepare the columns for the array field.
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _prepareToRender()
    {
        // Adding the policy Type column with a custom renderer
        $this->addColumn('policy', [
            'label' => __('Policy Type'),
            'class' => 'required-entry',
            'renderer' => $this->getPolicyTypeRenderer(),
        ]);

        // Adding the Domain column
        $this->addColumn('value', ['label' => __('Domain'), 'class' => 'required-entry']);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * Prepare the data for the array field row.
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @param DataObject $row
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        $options = [];
        $policy = $row->getPolicy();
        if ($policy !== null) {
            $options['option_' . $this->getPolicyTypeRenderer()->calcOptionHash($policy)] = 'selected="selected"';
        }
        $row->setData('option_extra_attrs', $options);
    }

    /**
     * Get the renderer for the policy type column.
     *
     * @return BlockInterface
     * @throws LocalizedException
     */
    private function getPolicyTypeRenderer(): BlockInterface
    {
        if (!$this->typeRenderer) {
            try {
                // Creating the renderer block for the policy type
                $this->typeRenderer = $this->getLayout()->createBlock(
                    PolicyTypeSelector::class,
                    '',
                    ['data' => ['is_render_to_js_template' => true]]
                );
            } catch (LocalizedException $e) {
                // Handle the exception (if any) and return the renderer
                return $this->typeRenderer;
            }
        }
        return $this->typeRenderer;
    }
}
