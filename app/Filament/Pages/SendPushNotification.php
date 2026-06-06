<?php

namespace App\Filament\Pages;

use App\Models\Guest;
use App\Models\User;
use App\Notifications\WisdomPushNotification;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SendPushNotification extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-bell';
    protected static ?string $navigationGroup = 'Notifications';
    protected static string  $view            = 'filament.pages.send-push-notification';

    // Use a single array to hold all form data
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title_text')
                    ->required()
                    ->label('العنوان'),
                Textarea::make('body_text')
                    ->required()
                    ->label('النص')
                    ->rows(3),
            ])
            ->statePath('data');
    }

    public function send(): void
    {
        $data = $this->form->getState();

        $notification = new WisdomPushNotification(
            $data['title_text'],
            $data['body_text']
        );

        $sent = 0;
        foreach (User::all() as $user) {
            $user->notify($notification);
            $sent++;
        }
        foreach (Guest::all() as $guest) {
            $guest->notify($notification);
            $sent++;
        }

        Notification::make()
            ->title("تم الإرسال إلى {$sent} مشترك")
            ->success()
            ->send();

        $this->form->fill();
    }
}
