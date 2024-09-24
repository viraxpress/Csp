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

declare(strict_types=1);

namespace ViraXpress\CSP\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class PolicyType extends AbstractSource
{
    const POLICY_CONNECT_SRC = 'connect-src';
    const POLICY_FONT_SRC = 'font-src';
    const POLICY_FORM_ACTION_SRC = 'form-action';
    const POLICY_FRAME_ANCESTORS_SRC = 'frame-ancestors';
    const POLICY_FRAME_SRC = 'frame-src';
    const POLICY_IMG_SRC = 'img-src';
    const POLICY_MANIFEST_SRC = 'manifest-src';
    const POLICY_DEFAULT = 'default-src';
    const POLICY_MEDIA_SRC = 'media-src';
    const POLICY_OBJECT_SRC = 'object-src';
    const POLICY_SCRIPT_SRC = 'script-src';
    const POLICY_STYLE_SRC = 'style-src';

    /**
     * Retrieve all options for policy types
     *
     * @return array[]|null
     */
    public function getAllOptions(): ?array
    {
        if ($this->_options === null) {
            $this->_options = [
                ['label' => __('connect-src') , 'value' => self::POLICY_CONNECT_SRC],
                ['label' => __('font-src') , 'value' => self::POLICY_FONT_SRC],
                ['label' => __('form-action') , 'value' => self::POLICY_FORM_ACTION_SRC],
                ['label' => __('frame-ancestors') , 'value' => self::POLICY_FRAME_ANCESTORS_SRC],
                ['label' => __('frame-src') , 'value' => self::POLICY_FRAME_SRC],
                ['label' => __('img-src') , 'value' => self::POLICY_IMG_SRC],
                ['label' => __('manifest-src') , 'value' => self::POLICY_MANIFEST_SRC],
                ['label' => __('default-src') , 'value' => self::POLICY_DEFAULT],
                ['label' => __('media-src') , 'value' => self::POLICY_MEDIA_SRC],
                ['label' => __('object-src') , 'value' => self::POLICY_OBJECT_SRC],
                ['label' => __('script-src') , 'value' => self::POLICY_SCRIPT_SRC],
                ['label' => __('style-src') , 'value' => self::POLICY_STYLE_SRC],
            ];
        }
        return $this->_options;
    }
}
