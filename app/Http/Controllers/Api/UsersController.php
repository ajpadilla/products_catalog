<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreUserRequest;
use App\User;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    use JsonResponse;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UsersController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $users = $this->userRepository->search([])->get();
            return self::success([
                'users' => $users
            ]);
        }catch (Exception $exception){
            logger($exception->getMessage());
            return self::internalServerError($exception->getMessage());
        }
    }

    /**
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();
            /** @var User $user */
            $user = $this->userRepository->create([
                'first_name'    => $request->get('first_name'),
                'last_name'     => $request->get('last_name'),
                'email'         => $request->get('email'),
                'password'      => $request->get('password')
            ]);
            DB::commit();

            return self::success([
                'message'   => 'User created successfully.',
                'user'  => $user
            ]);
        }catch (Exception $exception){
            DB::rollBack();
            logger($exception->getMessage());
            return self::internalServerError($exception->getMessage());
        }
    }

}
