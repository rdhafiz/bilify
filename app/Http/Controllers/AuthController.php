<?php

namespace App\Http\Controllers;

use App\Constants\UserType;
use App\Models\Companies;
use App\Models\User;
use App\Repositories\AuthRepository;
use App\Repositories\CompaniesRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 500, 'errors' => $validator->errors()]);
        }
        $userInfo = User::where('email', $requestData['email'])->first();
        if (!$userInfo instanceof User) {
            return response()->json(['status' => 500, 'errors' => ['email' => ['Invalid credential! Please try again']]], 200);
        }
        if ($userInfo->activation_code != null) {
            AuthRepository::sendVerificationEmail($userInfo);
            return response()->json(['status' => 300, 'message' => 'Your account is not verified yet. we send a mail again. Please verify your account'], 200);
        } else {
            if (Hash::check($requestData['password'], $userInfo->password)) {
                $access_token = $userInfo->createToken('authToken')->accessToken;
                return response()->json(['status' => 200, 'access_token' => $access_token, 'user' => User::parseData($userInfo)]);
            }
        }
        return response()->json(['status' => 500, 'errors' => ['password' => ['Password is not correct! Please try again.']]], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function socialLogin(Request $request): JsonResponse
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'social_provider' => 'required|string',
            'social_provider_id' => 'required|integer',
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 500, 'errors' => $validator->errors()]);
        }
        $userInfo = User::where('email', $requestData['email'])
            ->where('social_provider', $requestData['social_provider'])
            ->where('social_provider_id', $requestData['social_provider_id'])
            ->first();

        if (!$userInfo instanceof User) {
            $password = rand(1000, 99999);
            $userData = [
                'first_name' => $requestData['first_name'],
                'last_name' => $requestData['last_name'] ?? null,
                'social_provider' => $requestData['social_provider'],
                'social_provider_id' => $requestData['social_provider_id'],
                'email' => $requestData['email'],
                'password' => bcrypt($password),
            ];
            $userInfo = AuthRepository::save($userData);
            if (!$userInfo instanceof User) {
                return response()->json(['status' => 500, 'message' => 'Cannot social login'], 200);
            }
        }
        $access_token = $userInfo->createToken('authToken')->accessToken;
        return response()->json(['status' => 200, 'access_token' => $access_token, 'user' => User::parseData($userInfo)]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function registration(Request $request): JsonResponse
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'user_type' => 'required|integer',
            'company_name' =>'required_if:user_type,2',
            'company_size' =>'required_if:user_type,2',
            'company_address' =>'required_if:user_type,2',
            'company_city' =>'required_if:user_type,2',
            'company_country' =>'required_if:user_type,2',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 500, 'errors' => $validator->errors()]);
        }
        $activationCode = rand(100000, 999999);
        $userData = [
            'first_name' => $requestData['first_name'],
            'last_name' => $requestData['last_name'] ?? null,
            'email' => $requestData['email'],
            'password' => bcrypt($requestData['password']),
            'phone' => $requestData['phone'] ?? null,
            'gender' => $requestData['gender'] ?? null,
            'address' => $requestData['address'] ?? null,
            'city' => $requestData['city'] ?? null,
            'country' => $requestData['country'] ?? null,
            'user_type' => $requestData['user_type'],
            'activation_code' => $activationCode,
        ];
        $userInfo = AuthRepository::save($userData);
        if (!$userInfo instanceof User) {
            return response()->json(['status' => 500, 'message' => 'Cannot register user'], 200);
        }
        if ($userInfo['user_type'] == UserType::Company) {
            $companyData = [
                'company_name' => $requestData['company_name'],
                'company_size' => $requestData['company_size'],
                'company_address' => $requestData['company_address'],
                'company_city' => $requestData['company_city'],
                'company_country' => $requestData['company_country'],
                'user_id' => $userInfo['id'],
            ];
            $companyInfo = CompaniesRepository::save($companyData);
            if (!$companyInfo instanceof Companies) {
                User::where('id', $userInfo['id'])->forceDelete();
                return response()->json(['status' => 500, 'message' => 'Cannot save company'], 200);
            }
        }
        AuthRepository::sendVerificationEmail($userInfo);
        return response()->json(['status' => 200, 'message' => 'A verification mail has been sent to your email, Please verify the email to login'], 200);
    }
    public function forget(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 500, 'errors' => $validator->errors()]);
        }
        $userInfo = User::where('email', $requestData['email'])->first();
        if (!$userInfo instanceof User) {
            return response()->json(['status' => 500, 'errors' => ['email' => ['Invalid Email']]], 200);
        }
    }
}