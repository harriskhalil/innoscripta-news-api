<?php

namespace App\Http\Controllers;

use App\Actions\SendMail;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) :JsonResponse
    {
        try {
            $validated_data = $request->validated();
            $user = User::create($validated_data);
            $user['token']=$user->createToken('web')->plainTextToken;
            return  $this->response('User Registered Successfully',$user);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }

    public  function login(LoginRequest $request):JsonResponse
    {
        try {
            $validated = $request->validated();
            if (Auth::guard('web')->attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
                $user = auth()->user();
                $user['token']=$user->createToken('web')->plainTextToken;
                return  $this->response('User logged in',$user);
            }
            return $this->error('Invalid Credentials', 401);
        }catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }

    public  function logout():JsonResponse
    {
        /** @var \Laravel\Sanctum\PersonalAccessToken $token */
        $token = auth()->user()->currentAccessToken();
        $token->delete();
        return $this->response('User Logged out Successfully');

    }

    public function send_reset_password_token(Request $request, SendMail $sendMail) :JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email|exists:users,email',
        ]);
        try {
            $user = User::where('email', $request->email)->first();

            if (isset($user)) {
                PasswordReset::where('email', $user->email)->delete();
                $token = Hash::make($user->email.Str::random(20));

                $password_reset_data = [
                    'email' => $user->email,
                    'token' => $token,
                    'created_at'=>now()
                ];
                PasswordReset::insert($password_reset_data);

                $mail_data['email'] = $user->email;
                $mail_data['receiver_name'] = $user->name;
                $mail_data['subject'] = "Password  Reset";
                $mail_data['view'] = "mail.send_reset_password_otp";

                $mail_data['link'] = env('APP_URL') . 'api/auth/password/update?token=' . $token;

                $sendMail->send_mail($mail_data);

                return $this->response('Password verification link has sent to your email. Please open your mailbox and verify it. Thanks');
            } else {
                throw ValidationException::withMessages(['email' => "Email isn't recognized."]);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    public function update_user_password(Request $request):JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|confirmed|min:8'
        ]);

        try {
            $reset_data = PasswordReset::where('token', $request->token)->first();

            if (isset($reset_data) && Carbon::now()->diffInHours($reset_data->created_at) <= 24) {
                User::where('email', $reset_data->email)->update([
                    'password' => Hash::make($request->password)
                ]);
                PasswordReset::where('email', $reset_data->email)->delete();

                $user = User::where('email', $reset_data->email)->first();

                $credentials['email'] = $user->email;
                $credentials['password'] = $request->password;

                if (Auth::guard('web')->attempt($credentials)) {

                    $data['user'] = auth()->user();

                    $data['token'] = $data['user']->createToken('web')->plainTextToken;


                    return $this->response('Logged In', $data);

                }else {
                    return $this->error('Invalid Credentials', "401");
                }
            } else {
                return $this->response('Invalid password reset request.Please Try again.', '', 422, 'Error');
            }

        } catch (\Exception $error) {
            return $this->response($error->getMessage(), '', 422, 'Error');
        }
    }
}
