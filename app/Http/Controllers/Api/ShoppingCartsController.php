<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteShoppingCartRequest;
use App\Http\Requests\PayShoppingCartRequest;
use App\Http\Requests\StoreShoppingCartRequest;
use App\Repositories\ProductRepository;
use App\Repositories\ShoppingCartRepository;
use App\Services\ShoppingCart\ShoppingCartProductService;
use App\ShoppingCart;
use App\Traits\JsonResponse;
use Illuminate\Support\Facades\DB;
use Exception;

class ShoppingCartsController extends Controller
{

    use JsonResponse;

    /** @var ShoppingCartProductService */
    protected $shoppingCartProductService;

    /** @var ShoppingCartRepository */
    protected $shoppingCartRepository;

    /** @var ProductRepository */
    protected $productRepository;

    public function __construct(ShoppingCartProductService $shoppingCartProductService,
                                ShoppingCartRepository $shoppingCartRepository,
                                ProductRepository $productRepository
    ){
        $this->shoppingCartProductService = $shoppingCartProductService;
        $this->shoppingCartRepository = $shoppingCartRepository;
        $this->productRepository = $productRepository;
    }

    public function store(StoreShoppingCartRequest $request)
    {
        try {

            DB::beginTransaction();

            $shoppingCart = $this->shoppingCartProductService->getCart($request->getUser());

            if ($shoppingCart->hasProduct($request->getProduct())){
                $this->shoppingCartProductService->updateExistingProductQuantity($shoppingCart, $request->getProduct(), $request->getQuantity());
            }else{
                $this->shoppingCartProductService->addProduct($shoppingCart, $request->getProduct(), $request->getQuantity());
            }

            $this->shoppingCartProductService->updateTotalsInCart($shoppingCart);

            DB::commit();

            return self::success([
                'message'           => 'Product add successfully to shopping cart.',
                'shoppingCart'      => $shoppingCart,
                'total_pay'         => $shoppingCart->total_pay,
                'total_items'       => $shoppingCart->total_items,
                'total_references'  => $shoppingCart->references
            ]);
        }catch (Exception $exception){
            DB::rollBack();
            logger($exception->getMessage());
            return self::internalServerError($exception->getMessage());
        }
    }

    public function destroy(DeleteShoppingCartRequest $request, $id)
    {
        try {
            /** @var ShoppingCart $shoppingCart */
            $shoppingCart = $request->getShoppingCart();

            $this->shoppingCartProductService->detachProduct($shoppingCart, $request->getProduct());

            $this->shoppingCartProductService->updateTotalsInCart($shoppingCart);

            return self::success([
                'message'           => 'Product detach successfully to shopping cart.',
                'shoppingCart'      => $shoppingCart,
                'total_pay'         => $shoppingCart->total_pay,
                'total_items'       => $shoppingCart->total_items,
                'total_references'  => $shoppingCart->getProductItemsDescription()
            ]);
        }catch (Exception $exception){
            DB::rollBack();
            logger($exception->getMessage());
            return self::internalServerError($exception->getMessage());
        }
    }

    public function pay(PayShoppingCartRequest $request, $id)
    {
        try {
            /** @var ShoppingCart $shoppingCart */
            $shoppingCart = $request->getShoppingCart();

            if ($shoppingCart->products()->count()){
                foreach ($shoppingCart->products as $product){
                    $this->productRepository->update($product, ['stock' => ($product->stock - $product->pivot->quantity)]);
                }

                $this->shoppingCartRepository->update($shoppingCart, ['pay' => true]);

                $this->shoppingCartProductService->updateTotalsInCart($shoppingCart);

                return self::success([
                    'message'           => 'Cart pay successfully.',
                    'shoppingCart'      => $shoppingCart,
                    'total_pay'         => $shoppingCart->total_pay,
                    'total_items'       => $shoppingCart->total_items,
                    'total_references'  => $shoppingCart->getProductItemsDescription()
                ]);
            }

            return self::success([
                'message'           => 'Cart has no products.',
                'shoppingCart'      => $shoppingCart
            ]);

        }catch (Exception $exception){
            DB::rollBack();
            logger($exception->getMessage());
            return self::internalServerError($exception->getMessage());
        }
    }
}
