<?php

namespace Modules\Admin\Repositories;

use App\Models\Position;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

trait CompanyPositionRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'kd', 'name', 'description', 'level', 'dept_id', 'is_visible'
    ];

    /**
     * Store newly created resource.
     */
    public function storeCompanyPosition(array $data)
    {
        $position = new Position(Arr::only($data, $this->keys));
        if ($position->save()) {
            if ($data['parents']) {
                $position->parents()->sync($data['parents']);
            }
            if (isset($data['children'])) {
                $position->children()->sync($data['children']);
            }
            Auth::user()->log('membuat jabatan baru dengan nama ' . $position->name . ' <strong>[ID: ' . $position->id . ']</strong>', Position::class, $position->id);
            return $position;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanyPosition(Position $position, array $data)
    {
        $position = $position->fill(Arr::only($data, $this->keys));
        if ($position->save()) {
            $position->parents()->sync($data['parents'] ?? []);
            $position->children()->sync($data['children'] ?? []);

            Auth::user()->log('memperbarui jabatan ' . $position->name . ' <strong>[ID: ' . $position->id . ']</strong>', Position::class, $position->id);
            return $position;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanyPosition(Position $position)
    {
        if (!$position->trashed() && $position->delete()) {
            Auth::user()->log('menghapus jabatan ' . $position->name . ' <strong>[ID: ' . $position->id . ']</strong>', Position::class, $position->id);
            return $position;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanyPosition(Position $position)
    {
        if ($position->trashed() && $position->restore()) {
            Auth::user()->log('memulihkan jabatan ' . $position->name . ' <strong>[ID: ' . $position->id . ']</strong>', Position::class, $position->id);
            return $position;
        }
        return false;
    }
}
