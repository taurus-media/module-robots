# Taurus Robots

A Magento 2 module that automatically adds filterable product attributes to the `robots.txt` file to prevent search engines from crawling layered navigation filter combinations, which can lead to duplicate content issues.

## Description

The `Taurus_Robots` module extends the default Magento 2 Robots functionality. It identifies all product attributes configured to be "Use in Layered Navigation" (filterable) and adds a `Disallow` rule for each of them under the `User-agent: *` section.

For example, if you have a filterable attribute `color`, it will add:
`Disallow: /*color=*`

## Features

- Automatically detects filterable product attributes.
- Dynamically injects `Disallow` rules into the generated `robots.txt`.
- Sorts rules alphabetically for better readability.
- Ensures rules are placed under the global `User-agent: *` block.

## Installation

```bash
composer require taurus-media/module-robots
bin/magento module:enable Taurus_Robots
bin/magento setup:upgrade
bin/magento setup:static-content:deploy
```
