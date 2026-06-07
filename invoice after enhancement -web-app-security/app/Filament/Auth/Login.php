<?php

namespace App\Filament\Auth;

use App\Models\User;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Filament\Actions\ActionGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Redirect;
use Filament\Forms\Components\RichEditor;
use App\Http\Responses\Auth\LoginResponse;
use Filament\Pages\Auth\Login as BaseAuth;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Validation\ValidationException;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Illuminate\Support\Facades\RateLimiter; // ◄ Enforces the security registry

class Login extends BaseAuth
{
    /**
     * Get the form for the resource.
     */
    protected static string $layout = 'filament-panels::components.layout.base';

    protected array $extraBodyAttributes = ['class' => 'login-page'];

    public function getHeading(): string
    {
        return __('Sign In');
    }

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $this->form->fill([
            'email' => 'superadmin@test.com',
            'password' => 'superadmin1234',
        ]);
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->label(__('Username'))
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    public function form(Form $form): Form
    {
        return $form;
    }
/**
     * Authenticate the user.
     */
    public function authenticate(): ?LoginResponse
    {
        // 🛑 HARD SECURITY WALL: Check if the session is currently locked
        if (session()->has('locked_until') && now()->lessThan(session('locked_until'))) {
            $seconds = now()->diffInSeconds(session('locked_until'));

            Notification::make()
                ->title(__('Account Temporarily Locked Out'))
                ->body(__("Security constraint active. Too many failed attempts. Please retry in {$seconds} seconds."))
                ->danger()
                ->persistent()
                ->send();

            return null; // ◄ KILLS the process completely. No login possible!
        }

        $data = $this->form->getState();
        $check = $this->loginProcess($data);
        
        if (!$check) {
            return null;
        }
        
        $user = Filament::auth()->user();

        if (
            ($user instanceof FilamentUser) &&
            (!$user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }

    /**
     * Handle custom application user sign-in records and ban checks.
     */
    public function loginProcess($data)
    {
        // 🛑 DOUBLE PROTECTION WALL: Check session lock status inside process
        if (session()->has('locked_until') && now()->lessThan(session('locked_until'))) {
            return false; 
        }

        $user = User::where("email", $data['email'])->first();
        
        if ($user && Hash::check($data['password'], $user->password)) {
            if ($user->ban == 1) {
                Notification::make()
                    ->title(__('User banned'))
                    ->danger()
                    ->send();
                return false;
            }

            // SUCCESS: Clear session failure tracking completely
            session()->forget(['login_attempts', 'locked_until']);

            Auth::login($user);
            return true;
        }
        
        // FAILURE LOGIC: Track attempts inside native PHP Session memory
        $attempts = session()->get('login_attempts', 0) + 1;
        session()->put('login_attempts', $attempts);

        if ($attempts >= 5) {
            // Hard lock the session for 60 seconds from right now
            session()->put('locked_until', now()->addSeconds(60));
            
            Notification::make()
                ->title(__('Security Limit Reached'))
                ->body(__('Too many failed attempts. This gateway has been temporarily locked for 60 seconds.'))
                ->danger()
                ->persistent()
                ->send();
        } else {
            Notification::make()
                ->title(__('Wrong Username or Password'))
                ->body(__("Attempt {$attempts} of 5 before lockout."))
                ->danger()
                ->send();
        }

        return false;
    }

    public function hasLogo(): bool
    {
        return true;
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('Back')
                ->url('/')
                ->extraAttributes(['wire:navigate' => 'true', 'style' => 'width:30%;', 'class' => 'bg-gray-400']),
            
            $this->getAuthenticateFormAction()
                ->extraAttributes(['style' => 'width:60%;']),
        ];
    }

    protected function hasFullWidthFormActions(): bool
    {
        return false;
    }
}