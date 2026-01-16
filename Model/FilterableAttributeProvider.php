<?php
/**
 * Copyright (c) 2026. Taurus. All rights reserved
 */

namespace Taurus\Robots\Model;

use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory;
use Magento\Catalog\Model\Product;

class FilterableAttributeProvider
{
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $attributeCollectionFactory;

    /**
     * @var EavConfig
     */
    private EavConfig $eavConfig;

    /**
     * @param CollectionFactory $attributeCollectionFactory
     * @param EavConfig $eavConfig
     */
    public function __construct(
        CollectionFactory $attributeCollectionFactory,
        EavConfig $eavConfig
    ) {
        $this->attributeCollectionFactory = $attributeCollectionFactory;
        $this->eavConfig = $eavConfig;
    }

    /**
     * Get filterable product attributes collection
     *
     * @return Collection
     */
    public function getCollection(): Collection
    {
        $entityTypeId = (int) $this->eavConfig
            ->getEntityType(Product::ENTITY)
            ->getEntityTypeId();

        $collection = $this->attributeCollectionFactory->create();
        $collection->addFieldToFilter('entity_type_id', $entityTypeId);

        $collection->getSelect()->join(
            ['cea' => $collection->getTable('catalog_eav_attribute')],
            'cea.attribute_id = main_table.attribute_id',
            ['is_filterable']
        );

        $collection->getSelect()->where('cea.is_filterable != 0'); // select only attributes with the "Use in Layered Navigation" != "No"

        return $collection;
    }
}
