<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarDepartamentoRequest;
use App\Http\Requests\GuardarDepartamentoRequest;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class DepartmentController extends Controller
{
    /**
     * Devuelve todos los departamentos
     */
    public function index()
    {
        try {
            $departments = Department::all();
            return response()->json($departments);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los departamentos'], 500);
        }
    }

    /**
     * Guardar un nuevo departamento
     */
    public function store(GuardarDepartamentoRequest $request)
    {
        try {
            Department::create($request->validated());

            return response()->json([
                'res' => true,
                'msg' => 'Departamento almacenado correctamente'
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => 'Error al almacenar el departamento: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        if (!$department) {
            return response()->json([
                'status' => false,
                'message' => 'Departamento no encontrado'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $department
        ], 200);
    }

    /**
     * Actualizar el nombre del departamento
     */
    public function update(ActualizarDepartamentoRequest $request, Department $department)
    {
        try {
            $department->update($request->validated());

            return response()->json([
                'status' => true,
                'mensaje' => 'Departamento actualizado correctamente'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'mensaje' => 'Error al actualizar el departamento: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        try {
            $department->delete();

            return response()->json([
                'status' => true,
                'mensaje' => 'Departamento eliminado correctamente'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'mensaje' => 'Error al eliminar el departamento: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
