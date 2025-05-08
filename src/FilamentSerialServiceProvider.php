<?php

namespace Momenoor\FilamentSerial;

use Closure;
use Filament\Forms\Components\TextInput;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentSerialServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-serial';

    public static string $viewNamespace = 'filament-serial';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name);
    }

    public function bootingPackage(): void
    {
        TextInput::macro('serial', function (
            \Closure|string|null $prefix = null,
            \Closure|string|null $suffix = null,
            string               $separator = '-',
            int                  $length = 8
        ): TextInput {
            /** @var TextInput $this */

            // Evaluate prefix and suffix immediately
            $evaluatedPrefix = $this->evaluate($prefix);
            $evaluatedSuffix = $this->evaluate($suffix);
            $length = max($this->getLength() ?? 0, $length);

            // Calculate total display length
            $sepCount = ($evaluatedPrefix ? 1 : 0) + ($evaluatedSuffix ? 1 : 0);
            $totalLength = strlen($evaluatedPrefix ?? '') +
                strlen($evaluatedSuffix ?? '') +
                strlen($separator) * $sepCount +
                $length;

            // Configure the input
            $this->maxLength($totalLength)
                ->rule(function () use ($length) {
                    return function (string $attribute, mixed $value, Closure $fail) use ($length) {
                        $numericPart = preg_replace('/[^0-9]/', '', $value);
                        if (strlen($numericPart) > $length) {
                            $fail("The numeric part must not exceed {$length} digits.");
                        }
                    };
                });

            $formatState = function ($state) use ($evaluatedPrefix, $evaluatedSuffix, $separator, $length) {
                if (empty($state)) return null;

                $numericPart = preg_replace('/[^0-9]/', '', $state);
                $padded = str_pad(substr($numericPart, 0, $length), $length, '0', STR_PAD_LEFT);

                return ($evaluatedPrefix ? $evaluatedPrefix . $separator : '') .
                    $padded .
                    ($evaluatedSuffix ? $separator . $evaluatedSuffix : '');
            };
            // State handling - store only numbers, display formatted
            $this->afterStateHydrated(function ($state) use ($evaluatedPrefix, $evaluatedSuffix, $separator, $length, $formatState) {
                // When loading from DB, ensure we have the raw number
                if (filled($state)) {
                    return $formatState($state);
                }
                return $state;
            });

            $this->dehydrateStateUsing(function ($state) use ($formatState) {
                // Store only the numeric part in database
                return filled($state) ? $formatState($state) : null;
            });

            $this->extraInputAttributes([
                'x-on:blur' => 'formatOnBlur($event)',
                'x-on:keyup.enter' => 'formatOnBlur($event)',
                'x-on:focus' => 'formatOnFocus($event)',
                'x-init' => 'formatOnBlur($event)',
                'x-data' => '{
            formatOnBlur(event) {
                const rawValue = event.target.value;
                const numericPart = rawValue.replace(/[^0-9]/g, "");
                const padded = numericPart.padStart(' . $length . ', "0");
                event.target.value = ' . json_encode($evaluatedPrefix ? $evaluatedPrefix . $separator : '') . ' + padded + ' . json_encode($evaluatedSuffix ? $separator . $evaluatedSuffix : '') . ';
                // Trigger Livewire update with raw value

            },
            formatOnFocus(event) {
                const formattedValue = event.target.value;
                const numericPart = formattedValue.replace(/[^0-9]/g, "");
                const cleanNumber = numericPart.replace(/^0+/, "");
                event.target.value = cleanNumber;
                // Ensure Livewire has the raw value

            }
        }'
            ]);

            return $this;
        });
    }
}
