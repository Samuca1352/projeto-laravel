<?php

namespace App\Models;

        use Illuminate\Database\Eloquent\Factories\HasFactory;
        use Illuminate\Database\Eloquent\Model;

        class Pagamento extends Model
        {
            use HasFactory;

            protected $fillable = [
                'despesa_id',
                'data_pagamento',
                'imagem', // <-- Adicionado aqui
            ];

            public function despesa() { return $this->belongsTo('App\Models\Despesa'); }
        }
        