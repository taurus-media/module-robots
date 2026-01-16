<?php

namespace Taurus\Robots\Plugin;

use Taurus\Robots\Model\FilterableAttributeProvider;

class Robots
{
    /**
     * @var FilterableAttributeProvider
     */
    private FilterableAttributeProvider $attributeProvider;

    /**
     * @param FilterableAttributeProvider $attributeProvider
     */
    public function __construct(
        FilterableAttributeProvider $attributeProvider
    ) {
        $this->attributeProvider = $attributeProvider;
    }

    /**
     * @param \Magento\Robots\Model\Robots $subject
     * @param string $result
     * @return string
     */
    public function afterGetData(\Magento\Robots\Model\Robots $subject, string $result): string
    {
        foreach ($this->attributeProvider->getCollection() as $attribute) {
            $code = $attribute->getAttributeCode();
            $rules[$code] = sprintf('Disallow: /*%s=*', $code);
        }

        if (!$rules) {
            return $result;
        }

        ksort($rules);

        array_unshift($rules, 'User-agent: *');
        $rulesString = implode(PHP_EOL, $rules);

        if (preg_match('/User-agent:\s*\*/i', $result)) {
            return preg_replace(
                '/User-agent:\s*\*/',
                $rulesString,
                $result,
                1
            );
        }

        return $rulesString . PHP_EOL . $result;
    }
}
