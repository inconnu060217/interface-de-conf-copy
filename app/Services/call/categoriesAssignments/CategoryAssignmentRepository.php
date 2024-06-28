<?php

namespace App\Services\call\categoriesAssignments;
use App\Models\AssignmentCategoryModel;

class CategoryAssignmentRepository implements ICategoryAssignmentRepository
{
    private AssignmentCategoryModel $assignmentCategoryModel;

    function __construct(AssignmentCategoryModel $assignmentCategoryModel)
    {

        $this->assignmentCategoryModel = $assignmentCategoryModel;

    }

    public function getCategoryAffectation() {

        return $this->assignmentCategoryModel::all()->toArray();

    }
}
