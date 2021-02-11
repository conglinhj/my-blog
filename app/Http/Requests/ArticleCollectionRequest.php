<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\Types\Boolean;

class ArticleCollectionRequest extends FormRequest
{
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
            'page' => 'numeric',
            'limit' => 'numeric',
            'sort' => [
                'starts_with:id,title,is_published,created_at,updated_at,deleted_at,published_at',
            ],
            'with_deleted' => 'boolean',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->has('with_deleted')) {
            $this->merge([
                'with_deleted' => $this->convertStringToBoolean($this->get('with_deleted')),
            ]);
        }
    }

    protected function convertStringToBoolean($value): ?bool
    {
        switch ($value) {
            case 'true': return true;
            case 'false': return false;
            default: return $value;
        }
    }
}
