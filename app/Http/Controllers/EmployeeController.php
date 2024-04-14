<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarEmployeeRequest;
use App\Http\Requests\GuardarEmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;


class EmployeeController extends Controller
{
    /**
     * obtner todos los empleados y a que departamento pertenecen
     */
    public function index()
    {
        $employees = Employee::select('employees.id', 'employees.name', 'employees.email', 'employees.phone', 'departments.name as department')
            ->join('departments', 'departments.id', '=', 'employees.department_id')
            ->orderBy('employees.id', 'asc')
            ->paginate(10);

        return response()->json($employees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GuardarEmployeeRequest $request)
    {
        try {
            Employee::create($request->validated());

            return response()->json([
                'res' => true,
                'msg' => 'Empleado almacenado correctamente'
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => 'Error al almacenar el empleado: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        if (!$employee) {
            return response()->json([
                'status' => false,
                'message' => 'Empleado no encontrado'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $employee
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $rules = [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|max:80',
            'phone' => 'required|string|min:10|max:10',
            'department_id' => 'required|numeric'
        ];

        $validator = Validator::make($request->input(), $rules);
        // Verificar si la validación falla
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        // Actualizar  el empleado
        $employee->update($request->input());


        // Devolver respuesta de éxito
        return response()->json([
            'res' => true,
            'msg' => 'Empleado actualizado correctamente'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        try {
            $employee->delete();

            return response()->json([
                'success' => true,
                'message' => 'Empleado eliminado correctamente'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el empleado: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Obtiene el total de empleados por departamento.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function EmployeesByDepartment()
    {
        try {
            $employees = Employee::select(DB::raw('count(employees.id) as count'), 'departments.name')
                ->rightjoin('departments', 'departments.id', '=', 'employees.department_id')
                ->groupBy('departments.name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $employees
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los empleados por departamento: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Obtiene todos los empleados con el nombre de su departamento.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        try {
            // Selecciona los campos necesarios de los empleados, incluyendo el nombre de su departamento
            $employees = Employee::select('employees.id', 'employees.name', 'employees.email', 'employees.phone', 'departments.name as department')
                ->join('departments', 'departments.id', '=', 'employees.department_id')
                ->orderBy('employees.id', 'asc')
                ->get();

            // Devuelve una respuesta JSON con los empleados recuperados
            return response()->json([
                'success' => true,
                'data' => $employees
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            // Maneja cualquier excepción y devuelve una respuesta de error
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener todos los empleados: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
