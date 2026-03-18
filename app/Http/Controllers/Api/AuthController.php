<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{User, Role};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Validator};
use App\Traits\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;
    
    public function login(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($v->fails()) return $this->validationError($v->errors());

        if (! $token = auth('api')->attempt($request->only('email','password'))) {
            return $this->unauthorized('Invalid email or password.');
        }

        $user = auth('api')->user();

        if (! $user->is_active) {
            auth('api')->logout();
            return $this->forbidden('Your account has been deactivated.');
        }

        return $this->success($this->tokenPayload($token, $user), 'Login successful.');
    }

    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|max:15|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:customer,provider',
        ]);

        if ($v->fails()) return $this->validationError($v->errors());

        $role = Role::where('slug', $request->role)->first();
        $user = User::create([...$request->only('name','email','phone'), 
                            'role_id'  => $role->id,
                            'password' => Hash::make($request->password)]);

        $token = auth('api')->login($user);

        return $this->created($this->tokenPayload($token, $user), 'Registration successful.');
    }

    public function me()
    {
        return $this->success(auth('api')->user()->load('role','providerProfile.skills'));
    }

    public function refresh()
    {
        try {
            return $this->success($this->tokenPayload(auth('api')->refresh(), auth('api')->user()));
        } catch (\Exception $e) {
            return $this->unauthorized('Token expired. Please login again.');
        }
    }

    public function logout()
    {
        auth('api')->logout();
        return $this->success(null, 'Logged out successfully.');
    }

    private function tokenPayload(string $token, User $user): array
    {
        return [
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
            'user'         => [
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'phone'       => $user->phone,
                'role'        => $user->role->slug,
                'avatar'      => $user->avatar,
                'is_verified' => $user->is_verified,
            ],
        ];
    }
}