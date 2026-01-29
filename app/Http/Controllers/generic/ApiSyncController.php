<?php

namespace App\Http\Controllers\generic;

use App\Http\Controllers\Controller;
use App\Models\ApiSyncConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiSyncController extends Controller
{
    public function index()
    {
        $configs = ApiSyncConfig::all();
        return view('admin.api-sync.index', compact('configs'));
    }

    public function create()
    {
        return view('admin.api-sync.config');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:api_sync_configs',
            'url' => 'required|url',
            'method' => 'required',
            'auth_type' => 'required',
        ]);

        $config = ApiSyncConfig::create($request->all());
        return redirect()->route('admin.api-sync.index')->with('success', 'Configuración creada');
    }

    public function edit(ApiSyncConfig $apiSync)
    {
        return view('admin.api-sync.config', ['config' => $apiSync]);
    }

    public function update(Request $request, ApiSyncConfig $apiSync)
    {
        $config = $apiSync;
        $config->update($request->all());
        return redirect()->route('admin.api-sync.index')->with('success', 'Configuración actualizada');
    }

    public function execute(ApiSyncConfig $apiSync)
    {
        return view('admin.api-sync.execute', ['config' => $apiSync]);
    }

    public function test(Request $request, ApiSyncConfig $apiSync)
    {
        // Simple Logic for connecting
        try {
            $response = Http::get($apiSync->url);
            return response()->json(['status' => 'success', 'data' => $response->json()]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
