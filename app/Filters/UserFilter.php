<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Classe responsável por aplicar filtros à consulta de usuários,
 * como inclusão de deletados e carregamento de relacionamentos.
 */
class UserFilter
{
    protected Request $request;

    /**
     * Inicializa o filtro com a request atual.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Aplica os filtros sobre a query base do model User.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function apply(Builder $query): Builder
    {
        $query = $this->applyTrashed($query);
        $query = $this->applyRelations($query);

        return $query;
    }

    /**
     * Aplica o filtro de soft deletes com base no parâmetro `trashed`.
     * - `trashed=only`: apenas deletados
     * - `trashed=with`: todos (ativos + deletados)
     */
    protected function applyTrashed(Builder $query): Builder
    {
        $trashed = $this->request->query('trashed');

        if ($trashed === 'only') {
            return $query->onlyTrashed();
        }

        if ($trashed === 'with') {
            return $query->withTrashed();
        }

        return $query; // padrão: apenas usuários ativos
    }

    /**
     * Aplica o carregamento condicional de relacionamentos.
     * - `with_relations=true` (default)
     * - `with_relations=false`
     */
    protected function applyRelations(Builder $query): Builder
    {
        $withRelations = $this->request->boolean('with_relations', true);

        if ($withRelations) {
            $query->with('appointments.reminders');
        }

        return $query;
    }
}
