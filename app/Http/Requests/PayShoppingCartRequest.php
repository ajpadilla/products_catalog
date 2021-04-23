<?php

namespace App\Http\Requests;

use App\Repositories\ShoppingCartRepository;
use App\ShoppingCart;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PayShoppingCartRequest extends FormRequest
{
    /** @var User */
    protected $user;

    /** @var ShoppingCart */
    protected $shoppingCart;

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
            //
        ];
    }


    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        /** @var ShoppingCartRepository $shoppingCartRepository */
        $shoppingCartRepository = app(ShoppingCartRepository::class);

        if (!$this->shoppingCart = $shoppingCartRepository->getById($this->route('id'))){
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return ShoppingCart
     */
    public function getShoppingCart(): ShoppingCart
    {
        return $this->shoppingCart;
    }

}
