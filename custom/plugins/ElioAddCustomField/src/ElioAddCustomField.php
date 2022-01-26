<?php declare(strict_types=1);

namespace Elio\AddCustomField;

use Shopware\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Content\LandingPage\LandingPageDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\System\CustomField\Aggregate\CustomFieldSet\CustomFieldSetDefinition;
use Shopware\Core\System\CustomField\Aggregate\CustomFieldSet\CustomFieldSetEntity;
use Shopware\Core\System\CustomField\CustomFieldTypes;

class ElioAddCustomField extends Plugin
{
    public const CUSTOM_FIELD_SET_NAME = 'elio_category_extension_period';
    public const CUSTOM_FIELD_DATE_START_NAME = self::CUSTOM_FIELD_SET_NAME . '_date_start';
    public const CUSTOM_FIELD_DATE_END_NAME = self::CUSTOM_FIELD_SET_NAME . '_date_end';
    /**
     * Install plugin
     * @param InstallContext $installContext
     */
    public function install(InstallContext $installContext): void
    {
        $this->createCustomFields($installContext->getContext());
    }
    /**
     * Uninstall plugin
     * @param UninstallContext $uninstallContext
     */
    public function uninstall(UninstallContext $uninstallContext): void
    {
        if ($uninstallContext->keepUserData()) {
            return;
        }
        $this->removeCustomFields($uninstallContext->getContext());
    }

    /**
     * Create custom field set for setting a validity period
     * on category and landing page entities
     * @param Context $context
     */
    private function createCustomFields(Context $context): void
    {
        if ($this->getCustomFieldSet($context)) {
            return;
        }
        $this->customFieldSetRepository()->create([
            [
                'name' => self::CUSTOM_FIELD_SET_NAME,
                'config' => [
                    'label' => [
                        'en-GB' => 'Validity period',
                        'de-DE' => 'Gültigkeitszeitraum'
                    ]
                ],
                'customFields' => [
                    [
                        'name' => self::CUSTOM_FIELD_DATE_START_NAME,
                        'type' => CustomFieldTypes::DATETIME,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Start date (UTC)',
                                'de-DE' => 'Startdatum (UTC)'
                            ],
                            'customFieldPosition' => 1
                        ]
                    ],
                    [
                        'name' => self::CUSTOM_FIELD_DATE_END_NAME,
                        'type' => CustomFieldTypes::DATETIME,
                        'config' => [
                            'label' => [
                                'en-GB' => 'End date (UTC)',
                                'de-DE' => 'Enddatum (UTC)'
                            ],
                            'customFieldPosition' => 2
                        ]
                    ]
                ],
                'relations' => [
                    [
                        'entityName' => CategoryDefinition::ENTITY_NAME
                    ],
                    [
                        'entityName' => LandingPageDefinition::ENTITY_NAME
                    ]
                ],
            ]
        ], $context);
    }

    /*
    * Remove the custom field set created on installation
    * @param Context $context
    */
    private function removeCustomFields(Context $context): void
    {
        $customFieldSet = $this->getCustomFieldSet($context);
        if ($customFieldSet) {
            $this->customFieldSetRepository()->delete([
                [
                    'id' => $customFieldSet->getId()
                ]
            ], $context);
        }
    }
    /**
     * Get the custom field set repository
     * @return EntityRepositoryInterface
     */
    private function customFieldSetRepository(): EntityRepositoryInterface
    {
        /**
         * @var EntityRepositoryInterface $customFieldSetRepository
         */
        $customFieldSetRepository = $this->container->get(CustomFieldSetDefinition::ENTITY_NAME . '.repository');
        return $customFieldSetRepository;
    }
    /**
     * Get the custom field set created on installation
     * @param Context $context
     * @return CustomFieldSetEntity|null
     */
    private function getCustomFieldSet(Context $context): ?CustomFieldSetEntity
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', self::CUSTOM_FIELD_SET_NAME));
        return $this->customFieldSetRepository()->search($criteria, $context)->first();
    }
}