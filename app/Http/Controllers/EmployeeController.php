<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\User;
use App\Repositories\EmployeeRepository;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    private $employeeRepository;
    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }
    //Dashboard endpoints
    public function store(StoreEmployeeRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $image = $request->file("image")->store("");
        $request->merge(["image" => $image]);
        $employee = $this->employeeRepository->store($request->input());
        return $employee;
    }
    public function update(UpdateEmployeeRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $image = "";
        if ($request->file("image")) {
            $image = $request->file("image")->store("");
            $request->merge(["image" => $image]);
        }
        $updateResult = $this->employeeRepository->update($request->input());
        if ($request->file("image")) {
            Storage::delete($updateResult["old_image"]);
        }
        return $updateResult["updated_employee"];
    }
    public function delete($id)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $oldImage = $this->employeeRepository->delete($id);
        if ($oldImage) {
            Storage::delete($oldImage);
        }
    }
    //Web and front end points
    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->employeeRepository->getPage(request()->page_size, $text);
    } 
    //Web end points
    public function getLatestEmployees($limit)
    {
        return $this->employeeRepository->getLatestEmployees($limit);
    }
}
