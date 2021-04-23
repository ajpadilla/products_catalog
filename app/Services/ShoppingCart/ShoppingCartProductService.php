<?php


namespace App\Services\ShoppingCart;


use App\Product;
use App\Repositories\ShoppingCartRepository;
use App\ShoppingCart;
use App\User;

class ShoppingCartProductService
{
    /** @var ShoppingCartRepository */
    protected $shippingCartRepository;

    /** @var ShoppingCart */
    protected $shippingCart;

    public function __construct(ShoppingCartRepository $shippingCartRepository)
    {
        $this->shippingCartRepository = $shippingCartRepository;
    }

    public function getCart(User $user)
    {
        if (!$this->shippingCart = $this->shippingCartRepository->search(['user_id' => $user->id,'without_pay' => false])->first()){
            return $this->shippingCartRepository->create([
                'user_id'       => $user->id,
                'pay'           => false
            ]);
        }
        return $this->shippingCart;
    }

    public function updateTotalsInCart(ShoppingCart $cart)
    {
        $this->shippingCartRepository->update($cart,  [
            'total_pay'         => $cart->total(),
            'total_items'       => $cart->products()->count(),
            'references'        => $cart->getProductItemsDescription()
        ]);
    }

    public function addProduct(ShoppingCart $cart,Product $product, int $quantity)
    {
        $this->shippingCartRepository->associateProduct($cart, $product, $quantity);
    }

    public function detachProduct(ShoppingCart $cart,Product $product)
    {
        $this->shippingCartRepository->detachProduct($cart, $product);
    }


    public function updateExistingProductQuantity(ShoppingCart $cart,Product $product, int $quantity)
    {
        $this->shippingCartRepository->updateProductQuantity($cart, $product, $quantity);
    }
}
