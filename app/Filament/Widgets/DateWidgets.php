<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class DateWidgets extends Widget
{
    protected static string $view = 'filament.widgets.tanggalan';
    protected int | string | array $columnSpan = 'full'; // <-- Properti ini penting untuk layout
}