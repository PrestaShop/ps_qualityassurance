# Quality Assurance module

[![PHP tests](https://github.com/matks/ps_qualityassurance/actions/workflows/php.yml/badge.svg)](https://github.com/matks/ps_qualityassurance/actions/workflows/php.yml)
[![JS tests](https://github.com/matks/ps_qualityassurance/actions/workflows/js.yml/badge.svg)](https://github.com/matks/ps_qualityassurance/actions/workflows/js.yml)
[![Latest Stable Version](https://poser.pugx.org/PrestaShop/ps_qualityassurance/v)](//packagist.org/packages/PrestaShop/ps_qualityassurance)
[![Total Downloads](https://poser.pugx.org/PrestaShop/ps_qualityassurance/downloads)](//packagist.org/packages/PrestaShop/ps_qualityassurance)
[![GitHub license](https://img.shields.io/github/license/PrestaShop/ps_qualityassurance)](https://github.com/PrestaShop/ps_qualityassurance/LICENSE.md)

## About

This module was desiged to helps QA team to test hooks.

It provides a dedicated BO page where you can register to any hook inside PrestaShop and control what is being returned through this hook.

### How to use it

Install it, then use the new link in the menu "Quality Assurance" to reach the main configuration page.

From this page, you can
- see configured hooks
- register new hooks

## Requirements

Required only for development:

- npm
- composer

## Development tools

### Installation

Install all dependencies.
```
npm install
composer install
```

### Usage

```
npm run dev # Watch js/css files for changes
npm run build # Build for production
```

### Build a ZIP

If you want to install it into your shop by using standard module upload process, you need to build a ZIP archive.

Install npm and composer dependencies, then build the JavaScript assets for production. Then remove the unnecessary Javascript node_modules from the root folder, and build a ZIP archive from the folder.

## Contributing

PrestaShop modules are open source extensions to the [PrestaShop e-commerce platform][prestashop]. Everyone is welcome and even encouraged to contribute with their own improvements!

Just make sure to follow our [contribution guidelines][contribution-guidelines].

## License

This module is released under the [Academic Free License 3.0][AFL-3.0] 

[prestashop]: https://www.prestashop.com/
[contribution-guidelines]: https://devdocs.prestashop.com/1.7/contribute/contribution-guidelines/project-modules/
[AFL-3.0]: https://opensource.org/licenses/AFL-3.0
