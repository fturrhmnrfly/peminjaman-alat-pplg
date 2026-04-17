<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $role = $this->user()?->role;

        return [
            'nama' => ['required', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique(User::class, 'username')->ignore($this->user()->id),
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'hapus_foto_profil' => ['nullable', 'boolean'],
            'nomor_telepon' => [$role === 'peminjam' ? 'required' : 'nullable', 'string', 'max:30'],
            'nis' => [
                $role === 'peminjam' ? 'required' : 'nullable',
                'string',
                'max:30',
                Rule::unique(User::class, 'nis')->ignore($this->user()->id),
            ],
            'kelas' => [$role === 'peminjam' ? 'required' : 'nullable', 'string', 'max:50'],
            'alamat' => [$role === 'peminjam' ? 'required' : 'nullable', 'string', 'max:1000'],
            'nip' => [
                in_array($role, ['admin', 'petugas'], true) ? 'required' : 'nullable',
                'string',
                'max:30',
                Rule::unique(User::class, 'nip')->ignore($this->user()->id),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('name') && ! $this->filled('nama')) {
            $this->merge([
                'nama' => $this->input('name'),
            ]);
        }

        $this->merge([
            'hapus_foto_profil' => $this->boolean('hapus_foto_profil'),
            'nis' => $this->filled('nis') ? trim((string) $this->input('nis')) : null,
            'nip' => $this->filled('nip') ? trim((string) $this->input('nip')) : null,
            'kelas' => $this->filled('kelas') ? trim((string) $this->input('kelas')) : null,
            'nomor_telepon' => $this->filled('nomor_telepon') ? trim((string) $this->input('nomor_telepon')) : null,
            'alamat' => $this->filled('alamat') ? trim((string) $this->input('alamat')) : null,
        ]);
    }
}
