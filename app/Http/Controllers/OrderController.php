<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderFormRequest;
use Database\Factories\BrandFactory;
use Domain\Order\Actions\NewOrderAction;
use Domain\Order\DTOs\NewOrderDTO;
use Domain\Order\Models\DeliveryType;
use Domain\Order\Models\PaymentMethod;
use Domain\Order\Processes\AssignCustomer;
use Domain\Order\Processes\AssignProducts;
use Domain\Order\Processes\ChangeStateToPending;
use Domain\Order\Processes\CheckProductQuantities;
use Domain\Order\Processes\ClearCart;
use Domain\Order\Processes\DecreaseProductsQuantities;
use Domain\Order\Processes\OrderProcess;
use DomainException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function index(): Factory|View|Application
    {
        $items = cart()->items();
        if ($items->isEmpty()) {
            throw new DomainException('Корзина пуста');
        }

        DB::beginTransaction();
        BrandFactory::new()->create([
            'title' => 'transaction 1',
        ]);
        DB::commit();

        return view('order.index', [
            'items'      => $items,
            'payments'   => PaymentMethod::query()->get(),
            'deliveries' => DeliveryType::query()->get(),
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function handle(OrderFormRequest $request, NewOrderAction $action): RedirectResponse
    {
        $dto = NewOrderDTO::fromRequest($request);
        $customer = $dto->customer;
        $order = $action($dto);

        (new OrderProcess($order))->processes([
            new CheckProductQuantities(),
            new AssignCustomer($customer),
            new AssignProducts(),
            new ChangeStateToPending(),
            new DecreaseProductsQuantities(),
            new ClearCart(),
        ])->run();

        return redirect()
            ->route('home');
    }
}
