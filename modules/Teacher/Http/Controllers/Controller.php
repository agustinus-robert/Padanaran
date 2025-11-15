<?php

namespace Modules\Teacher\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as AppController;

use Modules\Academic\Models\AcademicSemester;

class Controller extends AppController
{
    /**
     * Controller instance.
     */
    public function __construct()
    {
     	$this->acsems = AcademicSemester::openedByDesc()->get();

     	$this->acsem = $this->acsems->first();

     	\View::share('ACSEM', $this->acsem);
    }
}
