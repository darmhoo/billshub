<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use App\Models\Faq as FaqModel;

class Faq extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationLabel = 'Faqs';

    protected static string $view = 'filament.app.pages.faq';

    protected static ?string $title = 'Frequent Asked Questions';

    protected static ?int $navigationSort = 10;
    protected static ?string $navigationGroup = 'Services';

    protected function getViewData(): array
    {
        $faqs = FaqModel::query()->get();
        // dd($user);
        return [
            'faqs' => $faqs
        ];
    }

}
