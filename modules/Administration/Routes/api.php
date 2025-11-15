<?php
use Illuminate\Support\Facades\Route;
use Modules\Academic\Models\AcademicSemester;
use Modules\Administration\Models\SchoolBillCycleSemesters;
use Modules\Administration\Models\SchoolBillReference;
use App\Models\References\GradeLevel;
use Modules\Academic\Models\AcademicClassroom;
use Modules\Core\Enums\StudentEducationEnum;
use Modules\Administration\Http\Controllers\API\Facility\RoomAPIController;
use Modules\Administration\Http\Controllers\API\Facility\BuildingAPIController;
use Modules\Administration\Http\Controllers\API\Scholar\ClassroomAPIController;

Route::get('/semesters', function () {
    return AcademicSemester::whereNull('deleted_at')
        ->with('academic:id,name') 
        ->get(['id', 'acdmc_id', 'name'])
        ->map(function ($item) {
            return [
                'id'   => $item->id,
                'name' => ($item->academic?->name ?? '-') . ' - ' . $item->name,
            ];
        });
})->name('semesters');


Route::get('/grade_class', function() {
    $classroomId = request()->query('class_id'); 

    $query = GradeLevel::query();

    if($classroomId == 1){
        $query->where('grade_id', 4);
    } else {
        $query->where('grade_id', 5);
    }

    return $query->get(['id', 'name'])
        ->map(function ($item){
            return [
                'id' => $item->id,
                'name' => $item->name
            ];
        });
})->name('grade_class');


Route::get('/classrooms', function () {
    $classroomId = request()->query('class_id'); 
    
    return AcademicClassroom::where('level_id', $classroomId)
        ->get(['id', 'name'])
        ->map(function ($item){
            return [
                'id' => $item->id,
                'name' => $item->name
            ];
        });
})->name('classrooms');



Route::get('/batches', function () {
    $semesterId = request()->query('semester_id'); 
    $acdmcId = AcademicSemester::find($semesterId);

    return SchoolBillCycleSemesters::where('semester_id', $acdmcId->id)
        ->where('grade_id', userGrades())
        ->get(['id','name']) 
        ->map(function ($item) {
            return [
                'id'   => $item->id,
                'name' => $item->name,
            ];
        });
})->middleware(['web','auth'])->name('batches');


Route::get('/references', function () {
    $batchId = request()->query('batch_id'); 

    return SchoolBillReference::where('batch_id', $batchId)
        ->select('type_class')
        ->distinct()
        ->where('type_class', userGrades())
        ->get()
        ->map(function ($ref) {
            $enum = $ref->type_class;

            return [
                'type_class'       => $enum->value, 
                'type_class_label' => $enum->label(),
            ];
        })
        ->values();
})->middleware(['web','auth'])->name('references');


Route::get('/references_category', function () {
    $batchId = request()->query('batch_id'); 

    return SchoolBillReference::where('batch_id', $batchId)
        ->select('payment_category')
        ->distinct()
        ->where('type_class', userGrades())
        ->get()
        ->map(function ($ref) {
            $enum = $ref->payment_category;

            return [
                'payment_category'       => $enum->value,
                'payment_category_label' => $enum->label(),
            ];
        })
        ->values();
})->middleware(['web','auth'])->name('references_category');


// api

Route::prefix('facility')->middleware('auth:sanctum')->group(function() {

    // List semua asset
    Route::get('/room-list', [RoomAPIController::class, 'index'])->name('room.list');
    Route::get('/building-list', [BuildingAPIController::class, 'index'])->name('building.list');
});

Route::prefix('scholar')->middleware('auth:sanctum')->group(function() {
    Route::get('/classroom-list', [ClassroomAPIController::class, 'index'])->name('classroom.list');
});