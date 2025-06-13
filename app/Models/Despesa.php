<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    use HasFactory;

    protected $casts = [
        'data_vencimento' => 'date',
    ];

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'descricao',
        'valor',
        'data_vencimento',
        'categoria',
        'user_id', // <-- ESTA É A LINHA QUE ESTAVA A FALTAR!
    ];

    /**
     * Define a relação de que a Despesa pertence a um Utilizador.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    
    /**
     * Define a relação de que a Despesa tem um Pagamento.
     */
    public function pagamento()
    {
        return $this->hasOne('App\Models\Pagamento');
    }
}
