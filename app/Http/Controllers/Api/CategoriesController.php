<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Repositories\CategoryRepository;
use App\Traits\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    use JsonResponse;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * CategoriesController constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $categories = $this->categoryRepository->search([])->get();
            return self::success([
                'categories' => $categories
            ]);
        }catch (Exception $exception){
            logger($exception->getMessage());
            return self::internalServerError($exception->getMessage());
        }
    }

    /**
     * @param StoreCategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            DB::beginTransaction();
            /** @var Category $category */
            $category = $this->categoryRepository->create([
                    'name'      => $request->get('name'),
                    'status'    => $request->get('status'),
                    'parent_id' => $request->get('parent_id')
            ]);
            DB::commit();

            return self::success([
                'message'   => 'Category created successfully.',
                'category'  => $category
            ]);
        }catch (Exception $exception){
            DB::rollBack();
            logger($exception->getMessage());
            return self::internalServerError($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id)
    {
        try {
            /** @var Category $category */
            $category = $this->categoryRepository->getById($id);
            return self::success([
                'message'   => 'Localized category.',
                'category'  => $category
            ]);
        }catch (Exception $exception){
            logger($exception->getMessage());
            return self::internalServerError($exception->getMessage());
        }
    }

    /**
     * @param UpdateCategoryRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            /** @var Category $category */
            $category = $this->categoryRepository->update($request->getCategory(), [
                'name'      => $request->get('name'),
                'status'    => $request->get('status'),
                'parent_id' => $request->get('parent_id')
            ]);
            DB::commit();
            return self::success([
                'message'   => 'Category updated.',
                'category'  => $category
            ]);
        }catch (Exception $exception){
            DB::rollBack();
            logger($exception->getMessage());
            return self::internalServerError($exception->getMessage());
        }
    }
}
