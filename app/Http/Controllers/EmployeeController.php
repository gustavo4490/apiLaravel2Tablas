<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarEmployeeRequest;
use App\Http\Requests\GuardarDepartamentoRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
    public function store(GuardarDepartamentoRequest $request)
    {
        try {
            $employee = Employee::create($request->validated());

            return response()->json([
                'success' => true,
                'data' => $employee,
                'message' => 'Empleado almacenado correctamente'
            ], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación al almacenar el empleado: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al almacenar el empleado: ' . $e->getMessage()
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
    public function update(ActualizarEmployeeRequest $request, Employee $employee)
    {
        try {
            $employee->update($request->validated());

            return response()->json([
                'success' => true,
                'data' => $employee,
                'message' => 'Empleado actualizado correctamente'
            ], Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación al actualizar el empleado: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el empleado: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
