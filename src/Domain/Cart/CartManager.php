<?php

declare(strict_types=1);

namespace Domain\Cart;

use Domain\Cart\Contracts\CartIdentityStorageContract;
use Domain\Cart\Models\Cart;
use Domain\Cart\Models\CartItem;
use Domain\Cart\StorageIdentities\FakeIdentityStorage;
use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Support\ValueObjects\Price;

final class CartManager
{
    /**
     * @param  CartIdentityStorageContract  $identityStorage
     */
    public function __construct(
        protected CartIdentityStorageContract $identityStorage
    ) {}

    public static function fake(): void
    {
        app()->bind(
            CartIdentityStorageContract::class,
            FakeIdentityStorage::class
        );
    }

    /**
     * @return string
     */
    private function cacheKey(): string
    {
        return str('cart_' . $this->identityStorage->get())
            ->slug('_')
            ->value();
    }

    /**
     * @return void
     */
    private function forgetCache(): void
    {
        Cache::forget($this->cacheKey());
    }

    /**
     * @param  string  $id
     * @return string[]
     */
    private function storedData(string $id): array
    {
        $data = [
            'storage_id' => $id,
        ];
        if (auth()->check()) {
            $data['user_id'] = auth()->id();
        }

        return $data;
    }

    /**
     * @param  array  $optionValues
     * @return string
     */
    private function stringedOptionValues(array $optionValues = []): string
    {
        sort($optionValues);

        return implode(';', $optionValues);
    }

    public function updateStorageId(
        string $old,
        string $current
    ): void {
        Cart::query()
            ->where('storage_id', $old)
            ->update($this->storedData($current));
    }

    /**
     * @param  Product  $product
     * @param  int  $quantity
     * @param  array  $optionValues
     * @return Model|Builder
     */
    public function add(
        Product $product,
        int $quantity = 1,
        array $optionValues = []
    ): Model|Builder {
        $cart = Cart::query()
            ->updateOrCreate([
                'storage_id' => $this->identityStorage->get(),
            ],
                $this->storedData($this->identityStorage->get())
            );

        $cartItem = $cart->cartItems()
            ->updateOrCreate([
                'product_id'           => $product->getKey(),
                'string_option_values' => $this->stringedOptionValues($optionValues),
            ], [
                'price'                => $product->price,
                'quantity'             => DB::raw("quantity + $quantity"),
                'string_option_values' => $this->stringedOptionValues($optionValues),
            ]);

        $cartItem->optionValues()
            ->sync($optionValues);

        $this->forgetCache();

        return $cart;
    }

    /**
     * @return CartIdentityStorageContract
     */
    public function quantity(CartItem $item, int $quantity = 1): void
    {
        $item->update([
            'quantity' => $quantity,
        ]);
        $this->forgetCache();
    }

    /**
     * @param  CartItem  $item
     * @return void
     */
    public function delete(CartItem $item): void
    {
        $item->delete();
        $this->forgetCache();
    }

    /**
     * @return void
     */
    public function truncate(): void
    {
        if ($this->get()) {
            $this->get()->delete();
        }
        $this->forgetCache();
    }

    /**
     * @return Collection
     */
    public function cartItems(): Collection
    {
        if (!$this->get()) {
            return collect([]);
        }

        return $this->get()->cartItems;
        // ?->  проверка на null, а не false
        //return $this->get()?->cartItems ?? collect([]);
    }

    public function items(): Collection
    {
        if (!$this->get()) {
            return collect([]);
        }

        return CartItem::query()
            ->with(['product', 'optionValues.option'])
            ->whereBelongsTo($this->get())
            ->get();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->cartItems()->sum(static function ($item) {
            return $item->quantity;
        });
    }

    /**
     * @return Price
     */
    public function amount(): Price
    {
        return Price::make(
            $this->cartItems()->sum(static function ($item) {
                return $item->amount->raw();
            })
        );
    }

    /**
     * @return mixed
     */
    public function get(): mixed
    {
        return Cache::remember(
            $this->cacheKey(),
            now()->addHour(),
            function () {
                return Cart::query()
                    ->with('cartItems')
                    ->where('storage_id', $this->identityStorage->get())
                    ->when(auth()->check(),
                        fn(Builder $query) => $query->orWhere('user_id', auth()->id())
                    )
                    ->first() ?? false;
            });
    }
}