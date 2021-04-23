<?php

namespace App\Http\Requests;

use App\Product;
use App\Repositories\ProductRepository;
use App\Repositories\ShoppingCartRepository;
use App\ShoppingCart;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DeleteShoppingCartRequest extends FormRequest
{
    /** @var ShoppingCart */
    protected $shoppingCart;

    /** @var Product */
    protected $product;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required|exists:product_shopping_cart,product_id'
        ];
    }

    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        /** @var ProductRepository $productRepository */
        $productRepository = app(ProductRepository::class);

        /** @var ShoppingCartRepository $shoppingCartRepository */
        $shoppingCartRepository = app(ShoppingCartRepository::class);

        if (!$this->product = $productRepository->getById($this->get('product_id'))){
            $validator->after(function (Validator &$validator) {
                $validator->errors()->add('product_id', 'Product not found.');
            });
        }

        if (!$this->shoppingCart = $shoppingCartRepository->getById($this->route('shopping_cart'))){
            $validator->after(function (Validator &$validator) {
                $validator->errors()->add('shopping_cart_id', 'Shopping Cart not found.');
            });
        }

        return $validator;
    }

    protected function failedValidation(Validator $validator)
    {
        logger('Validation errors on'. get_class($this));
        logger($validator->errors()->toJson());

        $jsonResponse = response()->json(['errors' => $validator->errors()], 422);

        throw new HttpResponseException($jsonResponse);
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return ShoppingCart
     */
    public function getShoppingCart(): ShoppingCart
    {
        return $this->shoppingCart;
    }

}
