<?php

namespace App\Http\Requests;

use App\Product;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreShoppingCartRequest extends FormRequest
{
    /** @var User */
    protected $user;

    /** @var Product */
    protected $product;

    /** @var int */
    protected $quantity;

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
            'user_id'       => 'required|exists:users,id',
            'product_id'    => 'required|exists:products,id',
            'quantity'      => 'required|numeric|min:1'
        ];
    }

    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        /** @var UserRepository $userRepository */
        $userRepository = app(UserRepository::class);

        /** @var ProductRepository $productRepository */
        $productRepository = app(ProductRepository::class);

        if (!$this->user = $userRepository->getById($this->get('user_id'))){
            $validator->after(function (Validator &$validator) {
                $validator->errors()->add('user_id', 'User not found.');
            });
            return $validator;
        }

        if (!$this->product = $productRepository->getById($this->get('product_id'))){
            $validator->after(function (Validator &$validator) {
                $validator->errors()->add('product_id', 'Product not found.');
            });
            return $validator;
        }

        if ($this->product->stock < $this->get('quantity')){
            $this->quantity = $this->product->stock;
        }else{
            $this->quantity = $this->get('quantity');
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
