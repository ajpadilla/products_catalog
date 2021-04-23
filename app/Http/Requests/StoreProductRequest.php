<?php

namespace App\Http\Requests;

use App\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProductRequest extends FormRequest
{
    /** @var Category */
    private $category;

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
            'sku'           => 'required|unique:products,sku',
            'image_url'     => 'required|mimes:jpeg,bmp,png',
            'name'          => 'required|string',
            'description'   => 'required|string',
            'stock'         => 'required|numeric|min:1',
            'price'         => 'required|numeric|min:1',
            'category_id'   => 'required|exists:categories,id'
        ];
    }

    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = app(CategoryRepository::class);

        /** @var Category category */
        $this->category = $categoryRepository->getById($this->get('category_id'));

        if (!$this->category->isActive() || !$this->category->hasParent() || !$this->category->isParentActive()){
            $validator->after(function (Validator $validator) {
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
}
