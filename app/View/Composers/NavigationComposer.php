<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Menu\Menu;
use App\Menu\MenuItem;
use Illuminate\View\View;

final class NavigationComposer
{
    public function compose(View $view): void
    {
        $menu = Menu::make()
            ->add(MenuItem::make(route('home'), 'Главная'))
            ->addIf(true, MenuItem::make(route('catalog'), 'Каталог'))
            ->addIf(true, MenuItem::make(route('catalog'), 'Корзина'));
        $view->with('menu', $menu);
    }
}