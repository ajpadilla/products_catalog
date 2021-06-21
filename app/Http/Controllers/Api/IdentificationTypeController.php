<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIdentificationTypeRequest;
use App\IdentificationType;
use Exception;
use Illuminate\Http\Request;
use App\Traits\JsonResponse;
use App\Repositories\IdentificationTypeRepository;
use Illuminate\Support\Facades\DB;

class IdentificationTypeController extends Controller
{
    use JsonResponse;

    /**
     * @var IdentificationTypeRepository
     */
    protected $identificationTypeRepository;

    /**
     * UsersController constructor.
     * @param IdentificationTypeRepository $identificationTypeRepository
     */
    public function __construct(IdentificationTypeRepository $identificationTypeRepository)
    {
        $this->identificationTypeRepository = $identificationTypeRepository;
    }

    /**
     * @param StoreIdentificationTypeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $identificationType = $this->identificationTypeRepository->create([
                'name'        => $request->get('name'),
                'description' => $request->get('description')
            ]);
            DB::commit();

            return self::success([
                'message'   => 'Identification type created successfully.',
                'identification_type'  => $identificationType
            ]);
        }catch (Exception $exception){
            DB::rollBack();
            logger($exception->getMessage());
            return self::internalServerError($exception->getMessage());
        }
    }

    public function update(StoreIdentificationTypeRequest $request,$id){
        try {
            DB::beginTransaction();

            $identificationType = $this->identificationTypeRepository->getById($id);

            $identificationType = $this->identificationTypeRepository->update($identificationType,[
                'name'        => $request->get('name'),
                'description' => $request->get('description')
            ]);
            DB::commit();

            return self::success([
                'message'   => 'Identification type updated successfully.',
                'identification_type'  => $identificationType
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

            $identificationType = $this->identificationTypeRepository->getById($id);

            $this->identificationTypeRepository->delete($identificationType);

            DB::commit();

            return self::success([
                'message'   => 'Identification type destroy successfully.',
            ]);
        }catch (Exception $exception){
            DB::rollBack();
            logger($exception->getMessage());
            return self::internalServerError($exception->getMessage());
        }
    }

}
