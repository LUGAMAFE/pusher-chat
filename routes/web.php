<?php

use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChatSessionController;
use App\Http\Controllers\ChatController;

use Laravel\Fortify\Http\Controllers\PasswordController;
use App\Http\Controllers\Authorization\LoginController;
use App\Http\Controllers\Authorization\RegisterController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
use Laravel\Fortify\Http\Controllers\RecoveryCodeController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\TwoFactorQrCodeController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\ProfileInformationController;
use Laravel\Fortify\Http\Controllers\ConfirmablePasswordController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\ConfirmedPasswordStatusController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localizationRedirect','preferredLocaleRedirect']], function() {

    Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
        $enableViews = config('fortify.views', true);

        // Authentication...
        if ($enableViews) {
            Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                ->middleware(['guest'])
                ->name('login');
        }

        $limiter = config('fortify.limiters.login');
        $twoFactorLimiter = config('fortify.limiters.two-factor');

        Route::post('/login', [AuthenticatedSessionController::class, 'store'])
            ->middleware(array_filter([
                'guest',
                $limiter ? 'throttle:'.$limiter : null,
            ]));

        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');

        // Password Reset...
        if (Features::enabled(Features::resetPasswords())) {
            if ($enableViews) {
                Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                    ->middleware(['guest'])
                    ->name('password.request');

                Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                    ->middleware(['guest'])
                    ->name('password.reset');
            }

            Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->middleware(['guest'])
                ->name('password.email');

            Route::post('/reset-password', [NewPasswordController::class, 'store'])
                ->middleware(['guest'])
                ->name('password.update');
        }

        // // Registration...
        if (Features::enabled(Features::registration())) {
            if ($enableViews) {
                Route::get('/'.LaravelLocalization::transRoute('routes.register'), [RegisteredUserController::class, 'create'])
                    ->middleware(['guest'])
                    ->name('register');
            }

            Route::post('/'.LaravelLocalization::transRoute('routes.register'), [RegisteredUserController::class, 'store'])
                ->middleware(['guest']);
        }

        // Email Verification...
        if (Features::enabled(Features::emailVerification())) {
            if ($enableViews) {
                Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
                    ->middleware(['auth'])
                    ->name('verification.notice');
            }

            Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['auth', 'signed', 'throttle:6,1'])
                ->name('verification.verify');

            Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth', 'throttle:6,1'])
                ->name('verification.send');
        }

        // Profile Information...
        if (Features::enabled(Features::updateProfileInformation())) {
            Route::put('/user/profile-information', [ProfileInformationController::class, 'update'])
                ->middleware(['auth'])
                ->name('user-profile-information.update');
        }

        // Passwords...
        if (Features::enabled(Features::updatePasswords())) {
            Route::put('/user/password', [PasswordController::class, 'update'])
                ->middleware(['auth'])
                ->name('user-password.update');
        }

        // Password Confirmation...
        if ($enableViews) {
            Route::get('/user/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->middleware(['auth'])
                ->name('password.confirm');
        }

        Route::get('/user/confirmed-password-status', [ConfirmedPasswordStatusController::class, 'show'])
            ->middleware(['auth'])
            ->name('password.confirmation');

        Route::post('/user/confirm-password', [ConfirmablePasswordController::class, 'store'])
            ->middleware(['auth']);

        // Two Factor Authentication...
        if (Features::enabled(Features::twoFactorAuthentication())) {
            if ($enableViews) {
                Route::get('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'create'])
                    ->middleware(['guest'])
                    ->name('two-factor.login');
            }

            Route::post('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'store'])
                ->middleware(array_filter([
                    'guest',
                    $twoFactorLimiter ? 'throttle:'.$twoFactorLimiter : null,
                ]));

            $twoFactorMiddleware = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
                ? ['auth', 'password.confirm']
                : ['auth'];

            Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store'])
                ->middleware($twoFactorMiddleware);

            Route::delete('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy'])
                ->middleware($twoFactorMiddleware);

            Route::get('/user/two-factor-qr-code', [TwoFactorQrCodeController::class, 'show'])
                ->middleware($twoFactorMiddleware);

            Route::get('/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'index'])
                ->middleware($twoFactorMiddleware);

            Route::post('/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'store'])
                ->middleware($twoFactorMiddleware);
        }
    });

    Route::get('/', function () {
        return redirect('login');
    });

    Route::get('/home', [HomeController::class, 'index'])->name('home.index');
    Route::get('/admin', [HomeController::class, 'admin'])->name('home.admin');
    Route::get('/chat', [HomeController::class, 'chat'])->name('home.chat');

    Route::post('/getFriends', [HomeController::class, 'getFriends']);
    Route::post('/session/create', [ChatSessionController::class, 'create']);
    Route::post('/session/{session}/chats', [ChatController::class, 'chats']);
    Route::post('/session/{session}/read', [ChatController::class, 'read']);
    Route::post('/session/{session}/clear', [ChatController::class, 'clear']);
    Route::post('/send/{session}', [ChatController::class, 'send']);

    Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
        // Registration...
        // Login...
        if (Features::enabled(Features::registration())) {

            $limiter = config('fortify.limiters.login');
            $twoFactorLimiter = config('fortify.limiters.two-factor');

            Route::get('/login', [LoginController::class, 'create'])
                ->middleware(['guest'])
                ->name('login');

            Route::post('/login', [LoginController::class, 'store'])
                ->middleware(array_filter([
                    'guest',
                    $limiter ? 'throttle:'.$limiter : null,
                ]));

            Route::post('/logout', [LoginController::class, 'destroy'])
                ->name('logout');
        }
    });
});
