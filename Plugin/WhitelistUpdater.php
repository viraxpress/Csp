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

namespace ViraXpress\CSP\Plugin;

use Magento\Csp\Api\Data\PolicyInterface;
use Magento\Csp\Model\Collector\CspWhitelistXmlCollector;
use Magento\Framework\Config\DataInterface as ConfigData;
use Magento\Csp\Model\Policy\FetchPolicyFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

class WhitelistUpdater
{
    const CONFIG_ENABLE = 'viraxpress_config/cspwhitelisting/enabled';
    const CONFIG_POLICIES = 'viraxpress_config/cspwhitelisting/policycustomizations';

    /** @var ConfigData */
    private $configDataReader;

    /** @var FetchPolicyFactory */
    private $policyFactory;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /**
     * @param ConfigData $configDataReader
     * @param ScopeConfigInterface $scopeConfig
     * @param FetchPolicyFactory $policyFactory
     */
    public function __construct(
        ConfigData $configDataReader,
        ScopeConfigInterface $scopeConfig,
        FetchPolicyFactory $policyFactory
    ) {
        $this->configDataReader = $configDataReader;
        $this->scopeConfig = $scopeConfig;
        $this->policyFactory = $policyFactory;
    }

    /**
     * Around plugin to modify CSP whitelist policies.
     *
     * @param CspWhitelistXmlCollector $subject
     * @param callable $proceed
     * @param PolicyInterface[] $defaultPolicies
     * @return array
     */
    public function aroundCollect(
        CspWhitelistXmlCollector $subject,
        callable $proceed,
        array $defaultPolicies = []
    ): array {
        if (!$this->isModuleActive()) {
            return $proceed($defaultPolicies);
        }

        $customWhitelist = $this->getWhitelistPolicies();
        $cspConfigData = $this->configDataReader->get(null);

        $updatedPolicies = $this->updatePolicies($cspConfigData, $customWhitelist, $defaultPolicies);

        return $updatedPolicies;
    }

    /**
     * Check if the module is enabled.
     *
     * @return bool
     */
    private function isModuleActive(): bool
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_ENABLE);
    }

    /**
     * Get custom whitelist policies.
     *
     * @return array
     */
    private function getWhitelistPolicies(): array
    {
        $data = [];
        $policies = $this->scopeConfig->getValue(self::CONFIG_POLICIES);
        if (!$policies) {
            return $data;
        }

        $policiesArray = json_decode($policies, true);
        foreach ($policiesArray as $policy) {
            $data[$policy['policy']][] = $policy['value'];
        }

        return $data;
    }

    /**
     * Update policies by merging custom whitelist with CSP configuration.
     *
     * @param array $cspConfigData
     * @param array $customWhitelist
     * @param array $defaultPolicies
     * @return array
     */
    private function updatePolicies(array $cspConfigData, array $customWhitelist, array $defaultPolicies): array
    {
        $updatedPolicies = $defaultPolicies;

        foreach ($cspConfigData as $policyKey => $policyData) {
            // Merge custom whitelist into policy hosts
            $mergedHosts = $this->mergeCustomWhitelist($policyKey, $policyData['hosts'], $customWhitelist);

            // Build and add the updated policy
            $updatedPolicies[] = $this->buildPolicy($policyKey, $mergedHosts, $policyData['hashes']);
        }

        return $updatedPolicies;
    }

    /**
     * Merge custom whitelist data into existing hosts.
     *
     * @param string $policyKey
     * @param array $hosts
     * @param array $customWhitelist
     * @return array
     */
    private function mergeCustomWhitelist(string $policyKey, array $hosts, array $customWhitelist): array
    {
        if (isset($customWhitelist[$policyKey])) {
            $hosts = array_merge($hosts, $customWhitelist[$policyKey]);
        }
        return $hosts;
    }

    /**
     * Build a CSP policy object.
     *
     * @param string $policyKey
     * @param array $hosts
     * @param array $hashes
     * @return PolicyInterface
     */
    private function buildPolicy(string $policyKey, array $hosts, array $hashes): PolicyInterface
    {
        return $this->policyFactory->create([
            'id' => $policyKey,
            'noneAllowed' => false,
            'hostSources' => $hosts,
            'schemeSources' => [],
            'selfAllowed' => false,
            'inlineAllowed' => false,
            'evalAllowed' => false,
            'nonceValues' => [],
            'hashValues' => $hashes,
            'dynamicAllowed' => false,
            'eventHandlersAllowed' => false
        ]);
    }
}
