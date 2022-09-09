<?php

namespace App\Services;

use App\Models\User;

class UserService extends ApiService
{
    protected $model = User::class;
    protected $fieldsName = '_fields';

    protected function getOrderbyableFields(): array
    {
        return ['id', 'name', 'email',];
    }

    protected function mapFilters(): array
    {
        return [
            'name' => function ($value) {
                return (static function ($q) use ($value) {
                    $q->whereNotNull('name')->where('name', $value);
                });
            },
            'is_active' => function ($value) {
                return (static function ($q) use ($value) {
                    $q->where('is_active', $value);
                });
            },
        ];
    }

    protected function fields(): array
    {
        return [
            'name', 'email', 'is_active', 'abc'

        ];
    }

    public function get_abc_value($value, $model)
    {
        return 'bdfbdf';
    }

    public function newQuery()
    {
        $query = parent::newQuery();

        $query->where('is_active', true);
        return $query;
    }

    protected function boot()
    {
        parent::boot();

        $this->on('creating', function($model) {
            $model->name = 'on creating...';
        });
    }
}
