# IBAN Validator (iban-validator)
Provides validation for [IBAN (International Bank Account Number)](https://www.iban.com).

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
