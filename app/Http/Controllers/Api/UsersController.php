<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\User;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Traits\JsonResponse;
use Carbon\Carbon;
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
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = $this->userRepository->create(
                $request->get('identification_type_id'),
                $request->get('first_name'),
                $request->get('last_name'),
                $request->get('email'),
                $request->get('phone'),
                $request->get('birthday'),
                Carbon::now(),
                "123456"
            );
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

    /**
     * @param UpdateUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $user = $this->userRepository->update(
                $id,
                $request->get('identification_type_id'),
                $request->get('first_name'),
                $request->get('last_name'),
                $request->get('email'),
                $request->get('phone'),
                $request->get('birthday'),
                Carbon::now()
            );
            DB::commit();

            return self::success([
                'message'   => 'User updated successfully.',
                'user'  => $user
            ]);
        }catch (Exception $exception){
            DB::rollBack();
            logger($exception->getMessage());
            return self::internalServerError($exception->getMessage());
        }
    }


    public function destroy($id){
        try {
            DB::beginTransaction();

            $this->userRepository->delete($id);

            DB::commit();

            return self::success([
                'message'   => 'User destroy successfully.',
            ]);
        }catch (Exception $exception){
            DB::rollBack();
            logger($exception->getMessage());
            return self::internalServerError($exception->getMessage());
        }
    }

}
