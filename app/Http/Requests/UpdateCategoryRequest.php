<?php

namespace App\Http\Requests;

use App\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCategoryRequest extends FormRequest
{
    /** @var Category  $category*/
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
            'name' => 'required|unique:categories,name',
            'status' => 'string|in:active,inactive',
            'parent_id' => 'exists:categories,id'
        ];
    }

    protected function getValidatorInstance()
    {
        logger($this->route('category'));
        $validator = parent::getValidatorInstance();

        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = app(CategoryRepository::class);

        if (!$this->category = $categoryRepository->getById($this->route('category'))){

            $validator->after( function (Validator $validator) {
                $validator->errors()->add('category', 'Category not found.');
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
