<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'required|max:255',
            'image' => 'required|image|mimes:jpeg,jpg,png',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'condition' => 'required',
            'price' => 'bail|required|regex:/^\d+$/',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名は必須です。',
            'description.required' => '商品の説明は必須です。',
            'description.max' => '商品の説明は255文字以内で入力してください。',
            'image.required' => '商品画像は必須です。',
            'image.image' => '画像ファイルを選択してください。',
            'image.mimes' => '画像はjpeg,jpg,png形式のみアップロードできます。',
            'categories.required' => 'カテゴリーを選択してください。',
            'categories.array' => 'カテゴリーの形式が正しくありません。',
            'categories.*.exists' => '選択されたカテゴリーが存在しません。',
            'condition.required' => '商品の状態を選択してください。',
            'price.required' => '販売価格は必須です。',
            'price.regex' => '販売価格は0円以上の整数で入力してください。',
        ];
    }
}
