<?php

namespace App\Http\Requests;

use App\Category;
use App\Product;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
{
    /** @var Category */
    private $category;

    /** @var Product */
    private $product;

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
            'sku'           => 'required|exists:products,sku',
            'image_url'     => 'required|mimes:jpeg,bmp,png',
            'name'          => 'required|string',
            'description'   => 'required|string',
            'stock'         => 'required|numeric',
            'price'         => 'required|numeric',
            'category_id'   => 'required|exists:categories,id'
        ];
    }

    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        /** @var ProductRepository $productRepository */
        $productRepository = app(ProductRepository::class);

        if (!$this->product = $productRepository->getBySku($this->get('sku'))){

            $validator->after( function (Validator &$validator) {
                $validator->errors()->add('prodcut', 'Product not found.');
            });
        }

        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = app(CategoryRepository::class);

        /** @var Category category */
        $this->category = $categoryRepository->getById($this->get('category_id'));

        if (!$this->category->isActive() || !$this->category->hasParent() || !$this->category->isParentActive()){
            $validator->after(function (Validator &$validator) {
                $validator->errors()->add('category_id', 'Category not found.');
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
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }
}
