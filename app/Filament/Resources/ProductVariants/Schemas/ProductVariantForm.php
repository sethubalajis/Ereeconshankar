<?php

namespace App\Filament\Resources\ProductVariants\Schemas;

use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductBarcode;
use App\Models\ProductVariant;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProductVariantForm
{
    public static function configure(Schema $schema): Schema
    {
        $attributeComponents = self::buildAttributeComponents();

        return $schema
            ->components(array_merge([
                Select::make('product_id')->relationship('product', 'name')
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get): void {
                        self::updateSkuAndBarcode($set, $get);
                        self::updateVariantPrices($set, $get);
                    })
                    ->required(),
<<<<<<< HEAD
                \Filament\Forms\Components\Select::make('color_id')->relationship('color', 'name'),
                \Filament\Forms\Components\Select::make('size_id')->relationship('size', 'name'),
=======
            ], $attributeComponents, [
>>>>>>> origin/main
                TextInput::make('sku')
                    ->label('SKU')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, ?string $state): void {
                        $set('barcode', self::generateBarcodeFromSku($state));
                    })
                    ->required(),
                TextInput::make('mrp')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('selling_price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('₹'),
                TextInput::make('cost_price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('₹'),
                TextInput::make('barcode')
                    ->readOnly(),
                Toggle::make('is_active')
                    ->required(),
            ]));
    }

    public static function syncAttributeInputs(ProductVariant $variant, array $attributeInputs): void
    {
        $inputs = collect($attributeInputs)
            ->mapWithKeys(function (mixed $input, mixed $key): array {
                $attributeId = (int) $key;

                if ($attributeId <= 0 || ! is_array($input)) {
                    return [];
                }

                return [$attributeId => $input];
            });

        if ($inputs->isEmpty()) {
            $variant->attributeValues()->sync([]);

            return;
        }

        $attributes = ProductAttribute::query()
            ->whereIn('id', $inputs->keys())
            ->get(['id', 'type'])
            ->keyBy('id');

        $attributeValueIds = [];

        foreach ($inputs as $attributeId => $input) {
            $attribute = $attributes->get($attributeId);

            if (! $attribute) {
                continue;
            }

            $type = Str::lower((string) $attribute->type);

            if (in_array($type, ['select', 'color'], true)) {
                $valueId = (int) ($input['attribute_value_id'] ?? 0);

                if ($valueId > 0) {
                    $attributeValueIds[] = $valueId;
                }

                continue;
            }

            $value = self::normalizeTypedValue($type, $input['value'] ?? null);

            if ($value === null) {
                continue;
            }

            $attributeValue = AttributeValue::query()->firstOrCreate([
                'product_attribute_id' => $attributeId,
                'value' => $value,
            ]);

            $attributeValueIds[] = (int) $attributeValue->id;
        }

        $variant->attributeValues()->sync(array_values(array_unique($attributeValueIds)));
    }

    public static function syncBarcodeRecord(ProductVariant $variant): void
    {
        if (($variant->barcode ?? '') === '') {
            return;
        }

        ProductBarcode::query()->updateOrCreate(
            ['product_variant_id' => $variant->getKey()],
            [
                'product_id' => $variant->product_id,
                'barcode' => $variant->barcode,
                'barcode_type' => 'EAN13',
            ]
        );
    }

    public static function prepareAttributeInputsForRecord(ProductVariant $variant): array
    {
        $inputs = [];

        foreach ($variant->attributeValues()->with('productAttribute:id,type')->get() as $attributeValue) {
            $attribute = $attributeValue->productAttribute;

            if (! $attribute) {
                continue;
            }

            $attributeId = (int) $attribute->id;
            $type = Str::lower((string) $attribute->type);

            if (in_array($type, ['select', 'color'], true)) {
                $inputs[$attributeId] = ['attribute_value_id' => (int) $attributeValue->id];

                continue;
            }

            if ($type === 'boolean') {
                $value = Str::lower(trim((string) $attributeValue->value));
                $inputs[$attributeId] = ['value' => in_array($value, ['1', 'true', 'yes'], true)];

                continue;
            }

            $inputs[$attributeId] = ['value' => $attributeValue->value];
        }

        return ['attribute_inputs' => $inputs];
    }

    private static function buildAttributeComponents(): array
    {
        return ProductAttribute::query()
            ->orderBy('name')
            ->get(['id', 'name', 'type'])
            ->map(fn (ProductAttribute $attribute) => self::makeAttributeComponent($attribute))
            ->all();
    }

    private static function makeAttributeComponent(ProductAttribute $attribute): mixed
    {
        $path = 'attribute_inputs.' . $attribute->id;
        $type = Str::lower((string) $attribute->type);

        if (in_array($type, ['select', 'color'], true)) {
            $options = AttributeValue::query()
                ->where('product_attribute_id', $attribute->id)
                ->orderBy('value')
                ->pluck('value', 'id')
                ->all();

            return Select::make($path . '.attribute_value_id')
                ->label($attribute->name)
                ->options($options)
                ->searchable()
                ->preload()
                ->live()
                ->afterStateUpdated(function (Set $set, Get $get): void {
                    self::updateSkuAndBarcode($set, $get);
                });
        }

        if ($type === 'number') {
            return TextInput::make($path . '.value')
                ->label($attribute->name)
                ->numeric()
                ->live(onBlur: true)
                ->afterStateUpdated(function (Set $set, Get $get): void {
                    self::updateSkuAndBarcode($set, $get);
                });
        }

        if ($type === 'boolean') {
            return Toggle::make($path . '.value')
                ->label($attribute->name)
                ->live()
                ->afterStateUpdated(function (Set $set, Get $get): void {
                    self::updateSkuAndBarcode($set, $get);
                });
        }

        if ($type === 'date') {
            return DatePicker::make($path . '.value')
                ->label($attribute->name)
                ->live(onBlur: true)
                ->afterStateUpdated(function (Set $set, Get $get): void {
                    self::updateSkuAndBarcode($set, $get);
                });
        }

        return TextInput::make($path . '.value')
            ->label($attribute->name)
            ->live(onBlur: true)
            ->afterStateUpdated(function (Set $set, Get $get): void {
                self::updateSkuAndBarcode($set, $get);
            });
    }

    private static function updateSkuAndBarcode(Set $set, Get $get): void
    {
        $sku = self::generateSku($get('product_id'), $get('attribute_inputs'));
        $set('sku', $sku);
        $set('barcode', self::generateBarcodeFromSku($sku));
    }

    private static function updateVariantPrices(Set $set, Get $get): void
    {
        $basePrice = Product::query()->whereKey($get('product_id'))->value('base_price');

        if ($basePrice === null) {
            return;
        }

        $set('mrp', $basePrice);
        $set('selling_price', $basePrice);
        $set('cost_price', $basePrice);
    }

    private static function generateSku(mixed $productId, mixed $attributeInputs): string
    {
        $productCode = Product::query()->whereKey($productId)->value('code');

        $productPart = Str::upper((string) Str::of((string) $productCode)
            ->replaceMatches('/[^A-Za-z0-9]/', '')
            ->toString());

        if ($productPart === '') {
            $productPart = 'VAR';
        }

        $inputs = collect($attributeInputs)
            ->mapWithKeys(function (mixed $input, mixed $key): array {
                $attributeId = (int) $key;

                if ($attributeId <= 0 || ! is_array($input)) {
                    return [];
                }

                return [$attributeId => $input];
            });

        if ($inputs->isEmpty()) {
            return $productPart;
        }

        $attributes = ProductAttribute::query()
            ->whereIn('id', $inputs->keys())
            ->get(['id', 'code', 'type'])
            ->keyBy('id');

        $selectedValueIds = $inputs
            ->pluck('attribute_value_id')
            ->filter(fn (mixed $id): bool => is_numeric($id))
            ->map(fn (mixed $id): int => (int) $id)
            ->values();

        $selectedValues = AttributeValue::query()
            ->whereIn('id', $selectedValueIds)
            ->pluck('value', 'id');

        $attributeParts = [];

        foreach ($inputs->sortKeys() as $attributeId => $input) {
            $attribute = $attributes->get($attributeId);

            if (! $attribute) {
                continue;
            }

            $type = Str::lower((string) $attribute->type);
            $rawValue = null;

            if (in_array($type, ['select', 'color'], true)) {
                $selectedId = (int) ($input['attribute_value_id'] ?? 0);
                $rawValue = $selectedValues[$selectedId] ?? null;
            } else {
                $rawValue = self::normalizeTypedValue($type, $input['value'] ?? null);
            }

            if ($rawValue === null || $rawValue === '') {
                continue;
            }

            $codePart = Str::upper((string) Str::of((string) ($attribute->code ?: ('AT' . $attributeId)))
                ->replaceMatches('/[^A-Za-z0-9]/', '')
                ->toString());

            $valuePart = Str::upper((string) Str::of((string) $rawValue)
                ->replaceMatches('/[^A-Za-z0-9]/', '')
                ->limit(6, '')
                ->toString());

            if ($codePart === '' || $valuePart === '') {
                continue;
            }

            $attributeParts[] = $codePart . $valuePart;
        }

        if (empty($attributeParts)) {
            return $productPart;
        }

        return Str::limit($productPart . '-' . implode('-', $attributeParts), 80, '');
    }

    private static function generateBarcodeFromSku(?string $sku): string
    {
        $base = Str::upper((string) Str::of((string) $sku)
            ->replaceMatches('/[^A-Za-z0-9]/', '')
            ->toString());

        if ($base === '') {
            $base = 'VARIANT';
        }

        // Derive 12 digits from SKU hash, then append EAN-13 checksum.
        $seed = sprintf('%u', crc32($base));
        $digits12 = str_pad(substr($seed, 0, 12), 12, '0');

        $sum = 0;
        foreach (str_split($digits12) as $index => $digit) {
            $sum += (int) $digit * (($index % 2 === 0) ? 1 : 3);
        }

        $checkDigit = (10 - ($sum % 10)) % 10;

        return $digits12 . (string) $checkDigit;
    }

    private static function normalizeTypedValue(string $type, mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($type === 'boolean') {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'Yes' : 'No';
        }

        if ($type === 'date') {
            return (string) Str::of((string) $value)->trim();
        }

        if ($type === 'number') {
            return (string) $value;
        }

        return (string) Str::of((string) $value)->trim();
    }
}
