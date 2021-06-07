<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Hash;
use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;


use App\Http\Controllers\Authorization\LoginController;
use App\Http\Controllers\Authorization\RegisterController;
use App\Http\Controllers\Authorization\ResetPasswordController;
use App\Http\Controllers\Authorization\ForgotPasswordController;
use App\Http\Controllers\Authorization\EmailVerificationController;


class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);

        Fortify::loginView(function () {
            $loginController  = new LoginController();
            return $loginController->create();
        });

        Fortify::registerView(function () {
            $registerController  = new RegisterController();
            return $registerController->create();
        });

        Fortify::requestPasswordResetLinkView(function () {
            $forgotPasswordController  = new ForgotPasswordController();
            return $forgotPasswordController->create();
        });

        Fortify::resetPasswordView(function () {
            $resetPasswordController  = new ResetPasswordController();
            return $resetPasswordController->create();
        });


        Fortify::verifyEmailView(function () {
            $emailVerificationController  = new EmailVerificationController();
            return $emailVerificationController->__invoke();
        });


        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->username)
            ->orWhere('username', $request->username)
            ->first();

            if ($user &&
                Hash::check($request->password, $user->password)) {
                return $user;
            }
        });
    }

    /**
     * Configure the permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
