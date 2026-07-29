<?php

namespace App\Http\Requests\Produk;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nama'       => 'required|string|max:255',
            'harga_beli' => 'required|integer|min:0',
            'harga_jual' => 'required|integer|min:0',
            'stok'       => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'foto.image'       => 'File yang diupload harus gambar.',
            'foto.mimes'       => 'Ekstensi gambar harus JPG, JPEG, PNG.',
            'foto.max'         => 'Maksimal ukuran gambar 2MB.',
            'nama.required'    => 'Nama produk wajib diisi.',
            'harga_beli.required' => 'Harga beli wajib diisi.',
            'harga_beli.integer'  => 'Harga beli harus berupa angka/bilangan bulat.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.integer'  => 'Harga jual harus berupa angka/bilangan bulat.',
            'stok.required'    => 'Stok wajib diisi.',
            'stok.integer'     => 'Stok harus berupa angka.',
        ];
    }
}
