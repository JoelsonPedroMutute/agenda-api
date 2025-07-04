<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class QueryFilter
{

       /**
     * Instância da requisição HTTP atual.
     * Contém todos os parâmetros enviados via query string.
     */
    protected Request $request;

     /**
     * Instância do builder Eloquent que será filtrado.
     */
    protected Builder $builder;

    /**
     * Construtor que injeta a Request atual.
     *
     * Request $request A requisição HTTP contendo os filtros.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
     /**
     * Aplica todos os filtros disponíveis na subclasse correspondente.
     *
     * Para cada parâmetro presente na URL (?campo=valor), este método verifica se existe
     * um método com o mesmo nome na subclasse. Se existir, ele é executado passando o valor.
     * 
     * Exemplo:
     * - URL: /appointments?status=ativo
     * - Chama: $this->status('ativo') se o método status existir.
     * 
     * Builder $builder Instância da query Eloquent.
     * Builder A query modificada com os filtros aplicados.
     */

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

         // Percorre todos os filtros recebidos pela requisição
        foreach ($this->filters() as $name => $value) {
               // Se existir um método com o nome do filtro, executa-o com o valor correspondente
            if (method_exists($this, $name)) {
                $this->$name($value);
            }
        }
          // Retorna o builder modificado com os filtros aplicados
        return $this->builder;
    }

     /**
     * Retorna todos os filtros enviados via query string.
     * 
     * Este método pode ser sobrescrito por subclasses se quiser restringir os filtros aceitos.
     *
     *  Lista de parâmetros da query string.
     */
    protected function filters(): array
    {
        return $this->request->all();
    }
}
