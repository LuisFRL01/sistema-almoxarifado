<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimento extends Model
{
    public static $rules = [
        'material_id' => 'bail|required|numeric',
        'quantidade' => 'required|integer|min:1',
        'deposito_id' => 'bail|required|numeric',
        'descricao' => 'required|min:5|max:255',
        'operacao' => 'numeric',
    ];

    public static $messages = [
        'material_id.required' => 'A escolha de um material é obrigatória',
        'material_id.numeric' => 'Escolha um material valido.',
        'quantidade.required' => 'A quantidade é obrigatória.',
        'quantidade.integer' => 'A quantidade é deve ser um número.',
        'quantidade.min' => 'A quantidade deve ter pelo menos uma unidade.',
        'deposito_id.required' => 'Escolha de um depósito é obrigatório',
        'deposito_id.numeric' => 'Escolha um depósito valido.',
        'operacao.numeric' => 'Escolha uma operação valido.',
        'descricao.required' => 'A descrição é obrigatória',
        'descricao.min' => 'A descrição deve ter no mínimo 5 caracteres.',
        'descricao.max' => 'A descrição deve ter no máximo 255 caracteres.',
    ];

    public function itemMovimentos()
    {
        $this->hasMany('App\itemMovimento');
    }
}
