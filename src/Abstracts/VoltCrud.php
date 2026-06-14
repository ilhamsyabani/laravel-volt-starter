<?php

namespace Ilhamsyabani\VoltStarter\Abstracts;

use Illuminate\Database\Eloquent\Model;
use Ilhamsyabani\VoltStarter\Traits\Toast;
use Ilhamsyabani\VoltStarter\Traits\Searchable;
use Ilhamsyabani\VoltStarter\Traits\Sortable;

/**
 * Base Volt Component for CRUD operations.
 *
 * Extend this class for simple CRUD pages:
 *
 *   class PostsIndex extends VoltCrudIndex
 *   {
 *       protected string $model = Post::class;
 *       protected array $searchFields = ['title', 'content'];
 *   }
 */
abstract class VoltCrudIndex
{
    use Toast;
    use Searchable;
    use Sortable;

    /**
     * The Eloquent model class.
     */
    protected string $model;

    /**
     * Fields to search in.
     */
    protected array $searchFields = ['name'];

    /**
     * Default sort field.
     */
    protected string $sortField = 'created_at';

    /**
     * Default sort direction.
     */
    protected string $sortDirection = 'desc';

    /**
     * Items per page.
     */
    protected int $perPage = 10;

    /**
     * Get paginated items.
     */
    public function items(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->model::query();

        // Apply search
        $query = $this->applySearchWith($this->searchFields, $query);

        // Apply sort
        $query = $this->applySort($query);

        return $query->paginate($this->perPage);
    }

    /**
     * Delete an item.
     */
    public function delete(Model $item): void
    {
        $item->delete();
        $this->success('Item deleted successfully.');
    }

    /**
     * Bulk delete items.
     */
    public function bulkDelete(array $ids): void
    {
        $count = $this->model::whereIn('id', $ids)->delete();
        $this->success("{$count} items deleted successfully.");
    }
}

/**
 * Base Volt Component for Create operations.
 */
abstract class VoltCrudCreate
{
    use Toast;

    /**
     * The Eloquent model class.
     */
    protected string $model;

    /**
     * Validation rules.
     */
    protected array $rules = [];

    /**
     * Store a new item.
     */
    public function store(): void
    {
        $this->validate($this->rules);

        $this->model::create($this->all());

        $this->success('Item created successfully.');
        $this->redirectRoute($this->getRedirectRoute(), navigate: true);
    }

    /**
     * Get redirect route name.
     */
    protected function getRedirectRoute(): string
    {
        return strtolower(class_basename($this->model)) . '.index';
    }
}

/**
 * Base Volt Component for Edit operations.
 */
abstract class VoltCrudEdit
{
    use Toast;

    /**
     * The Eloquent model class.
     */
    protected string $model;

    /**
     * Validation rules.
     */
    protected array $rules = [];

    /**
     * Update an item.
     */
    public function update(Model $item): void
    {
        $this->validate($this->rules);

        $item->update($this->all());

        $this->success('Item updated successfully.');
        $this->redirectRoute($this->getRedirectRoute(), navigate: true);
    }

    /**
     * Get redirect route name.
     */
    protected function getRedirectRoute(): string
    {
        return strtolower(class_basename($this->model)) . '.index';
    }
}

/**
 * Base Volt Component for Show operations.
 */
abstract class VoltCrudShow
{
    /**
     * The Eloquent model class.
     */
    protected string $model;
}
