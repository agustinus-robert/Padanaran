<?php

namespace Modules\Core\Repositories;

use Illuminate\Support\Facades\Auth;
use Modules\Account\Models\User;
use Modules\Core\Models\CompanySalaryTemplate;

trait CompanySalaryTemplateRepository
{
    /**
     * Store newly created resource.
     */
    public function storeCompanySalaryTemplate(array $data, User $user)
    {
        $grade = ['grade_id' => userGrades()];
        $template = new CompanySalaryTemplate(array_merge($data, $grade));
        if ($template->save()) {
            $user->log('membuat template gaji baru ' . $template->name . ' <strong>[ID: ' . $template->id . ']</strong>', CompanySalaryTemplate::class, $template->id);
            return $template;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanySalaryTemplate(CompanySalaryTemplate $template, array $data, User $user)
    {
        $grade = ['grade_id' => userGrades()];
        $template = $template->fill(array_merge($data, $grade));
        if ($template->save()) {
            $user->log('memperbarui template gaji ' . $template->name . ' <strong>[ID: ' . $template->id . ']</strong>', CompanySalaryTemplate::class, $template->id);
            return $template;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanySalaryTemplate(CompanySalaryTemplate $template, User $user)
    {
        if (!$template->trashed() && $template->delete()) {
            $user->log('menghapus template gaji ' . $template->name . ' <strong>[ID: ' . $template->id . ']</strong>', CompanySalaryTemplate::class, $template->id);
            return $template;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanySalaryTemplate(CompanySalaryTemplate $template, User $user)
    {
        if ($template->trashed() && $template->restore()) {
            $user->log('memulihkan template gaji ' . $template->name . ' <strong>[ID: ' . $template->id . ']</strong>', CompanySalaryTemplate::class, $template->id);
            return $template;
        }
        return false;
    }
}
