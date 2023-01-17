<?php

namespace App\Repositories;

use App\Models\Employee;

class EmployeeRepository
{
    public function store($employee)
    {
        return Employee::create($employee);
    }
    public function update($employeeInput)
    {
        $employee = Employee::find($employeeInput["id"]);
        $oldImage = $employee->getImageName();
        $employee->name = $employeeInput["name"];
        $employee->job_ar = $employeeInput["job_ar"];
        $employee->job_en = $employeeInput["job_en"];
        $employee->image = isset($employeeInput["image"]) ? $employeeInput["image"] : $oldImage;
        $employee->twitter = $employeeInput["twitter"];
        $employee->facebook = $employeeInput["facebook"];
        $employee->instgram = $employeeInput["instgram"];
        $employee->save();
        return ["old_image" => $oldImage, "updated_employee" => $employee];
    }
    public function delete($id)
    {
        $employee = Employee::find($id);
        $oldImage = null;
        if ($employee) {
            $oldImage = $employee->getImageName();
            $employee->delete();
        }
        return $oldImage;
    }
    public function getPage($pageSize, $text)
    {
        return Employee::whereRaw('LOWER(`job_ar`) LIKE ? or LOWER(`job_en`) LIKE ? or 
        LOWER(`name`) LIKE ?', [
            "%" . strtolower($text) . '%',
            "%" . strtolower($text) . '%',
            "%" . strtolower($text) . '%'
        ])->paginate($pageSize);
    }
    public function getLatestEmployees($limit)
    {
        return Employee::orderBy('id', 'desc')->take($limit)->get();
    }
}
