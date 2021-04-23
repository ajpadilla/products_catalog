<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UpdateProductRequest;
use App\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Services\Files\FilesService;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    use JsonResponse;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var FilesService
     */
    protected $fileService;

    /**
     * ProductsController constructor.
     * @param ProductRepository $productRepository
     * @param FilesService $fileService
     */
    public function __construct(ProductRepository $productRepository, FilesService $fileService)
    {
        $this->productRepository = $productRepository;
        $this->fileService = $fileService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $products = $this->productRepository->search(['active_categories' => true])->get();
            return self::success([
                'products' => $products
            ]);
        }catch (Exception $exception){
            logger($exception->getMessage());
            return self::internalServerError($exception->getMessage());
        }
    }

    /**
     * @param StoreProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $image_url = $this->fileService->makeProductImage(
                $request->file('image_url'),
                $request->get('sku'),
                "img/products/"
            );

            DB::beginTransaction();
            /** @var Product $product */
            $product = $this->productRepository->create([
                'sku'           => $request->get('sku'),
                'image_url'     => $image_url,
                'name'          => $request->get('name'),
                'description'   => $request->get('description'),
                'stock'         => $request->get('stock'),
                'price'         => $request->get('price'),
                'category_id'   => $request->getCategory()->id
            ]);
            DB::commit();

            return self::success([
                'message'   => 'Product created successfully.',
                'product'  => $product
            ]);
        }catch (Exception $exception){
            DB::rollBack();
            logger($exception->getMessage());
            return self::internalServerError($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        try {
            /** @var Product $product */
            $product = $this->productRepository->getById($id);
            return self::success([
                'message'   => 'Localized product.',
                'product'  => $product
            ]);
        }catch (Exception $exception){
            logger($exception->getMessage());
            return self::internalServerError($exception->getMessage());
        }
    }

    /**
     * @param UpdateProductRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $image_url = $this->fileService->makeProductImage(
                $request->file('image_url'),
                $request->get('sku'),
                "img/products/"
            );

            DB::beginTransaction();
            /** @var Product $product */
            $product = $this->productRepository->update($request->getProduct(),[
                'sku'           => $request->get('sku'),
                'image_url'     => $image_url,
                'name'          => $request->get('name'),
                'description'   => $request->get('description'),
                'stock'         => $request->get('stock'),
                'price'         => $request->get('price'),
                'category_id'   => $request->getCategory()->id
            ]);
            DB::commit();

            return self::success([
                'message'   => 'Product updated successfully.',
                'product'  => $request->getProduct()
            ]);
        }catch (Exception $exception){
            DB::rollBack();
            logger($exception->getMessage());
            return self::internalServerError($exception->getMessage());
        }
    }
}
