# IBAN Validator (iban-validator)
Provides validation for [ISO 13616-1:2007 International Bank Account Numbers (IBAN)](https://www.iban.com).

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist beastbytes/iban-validator
```

or add

```json
"beastbytes/iban-validator": "^1.0.0"
```

to the require section of your composer.json.

An IbanDataInterface implementation is also required, e.g. beastbytes/iban-data-php
