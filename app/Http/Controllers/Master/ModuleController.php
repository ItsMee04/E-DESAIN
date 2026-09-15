<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Master\ModuleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller
{
    protected ModuleService $moduleService;

    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    public function getModules()
    {
        try {
            $data = $this->moduleService->getModules();

            return response()->json([
                'status' => '200',
                'success' => true,
                'message' => 'Data module berhasil diambil',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'Gagal mengambil data module: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function storeModule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_id' => 'nullable|exists:modules,id',
            'name' => 'required|string|max:100',
            'key' => 'required|string|max:100|unique:modules,key',
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'status' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {

            $module = $this->moduleService->createModule(
                $validator->validated()
            );

            return response()->json([
                'status' => '200',
                'success' => true,
                'message' => 'Module berhasil ditambahkan',
                'data' => $module
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'Gagal menambahkan module: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateModule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:modules,id',
            'parent_id' => 'nullable|exists:modules,id',
            'name' => 'required|string|max:100',
            'key' => 'required|string|max:100|unique:modules,key,' . $request->id,
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'status' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {

            $module = $this->moduleService->updateModule(
                $request->id,
                $validator->validated()
            );

            return response()->json([
                'status' => '200',
                'success' => true,
                'message' => 'Module berhasil diperbarui',
                'data' => $module
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'Gagal memperbarui module: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteModule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:modules,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {

            $this->moduleService->deleteModule($request->id);

            return response()->json([
                'status' => '200',
                'success' => true,
                'message' => 'Module berhasil dihapus',
                'data' => []
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'Gagal menghapus module: ' . $e->getMessage()
            ], 500);
        }
    }
}
