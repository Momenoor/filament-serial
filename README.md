# Filament Serial Field for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/momenoor/filament-serial.svg?style=flat-square)](https://packagist.org/packages/momenoor/filament-serial)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/momenoor/filament-serial/fix-php-code-style.yml?label=code%20style&style=flat-square)](https://github.com/momenoor/filament-serial/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/momenoor/filament-serial.svg?style=flat-square)](https://packagist.org/packages/momenoor/filament-serial)

A Filament V3 form macro for `TextInput` that allows you to easily format and manage serial numbers with optional prefixes, suffixes, separators, and padded digits.

---

## ✨ Features

- Format serial numbers in Filament forms.
- Support for:
    - Prefix and/or suffix
    - Custom separator
    - Fixed digit zero-padded numeric part
- Automatically formats on blur and focus
- Saves only numeric part to database
- Built-in Alpine.js interactivity
- Simple integration with Filament `TextInput`

---

## 📦 Installation

You can install the package via Composer:

```bash
composer require momenoor/filament-serial
```
This package auto-registers itself. No need to publish anything.

## ⚙️ Usage

```php
use Filament\Forms\Components\TextInput;

TextInput::make('serial_number')
    ->label('Serial Number')
    ->serial(
        prefix: 'INV',     // Optional string or Closure
        suffix: 'UAE',     // Optional string or Closure
        separator: '-',    // Default is "-"
        length: 6          // Default is 8
    );
```
### Example

| Interaction | Value Example         |
|-------------|-----------------------|
| On blur     | `INV-000123-UAE`      |
| On focus    | `123`                 |
| In database | `000123`              |

---

## 🛠 Parameters

| Parameter   | Type            | Description                                               |
|-------------|-----------------|-----------------------------------------------------------|
| `prefix`    | string\|Closure | Text before the numeric part                              |
| `suffix`    | string\|Closure | Text after the numeric part                               |
| `separator` | string          | String between segments (default: `-`)                    |
| `length`    | int             | Number of digits in the numeric part (default: `8`)       |

---

## ✅ Compatibility

- Laravel 10+
- Filament 3.x
- PHP 8.1+

---

## 📄 Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on recent changes.

---

## 🤝 Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

---

## 🔐 Security

If you discover any security-related issues, please review [the security policy](../../security/policy).

---

## 🙌 Credits

- [Momen Noor](https://github.com/momenoor)
- [All Contributors](../../contributors)

---

## 📜 License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.


