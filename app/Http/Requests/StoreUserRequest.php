<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Atur otorisasi di sini. Untuk sekarang, izinkan semua pengguna.
        // Anda bisa menambahkan logika seperti: return auth()->user()->isAdmin();
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,petugas,pengguna',
        ];
    }
}