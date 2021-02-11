<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class TenantController extends Controller
{

    public function index()
    {
        if (\request()->ajax()) {

            $tenants = User::with('tenantProfile')->whereRoleIs('tenant')->withCount('leases')->latest()->get();

            return DataTables::of($tenants)
                ->addIndexColumn()
                ->addColumn('actions', function ($tenant) {
                    return view('admin.tenant.actions', compact('tenant'))->render();
                })
                ->addColumn('identityNo', function ($tenant) {
                    return $tenant->tenantProfile->identity ?? 'No ID';
                })->addColumn('address', function ($tenant) {
                    return $tenant->tenantProfile->address ?? 'No Address';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.tenant.index');
    }


    public function create()
    {
        return view('admin.tenant.create');
    }


    public function show($id)
    {
        $tenant = User::with(['tenantProfile', 'leases'])->findOrFail($id);
        $invoices = Invoice::with('invoicable')->where('tenant_id', $id)->get();

        return view('admin.tenant.show', compact('tenant', 'invoices'));
    }


    public function edit($id)
    {
        return view('admin.tenant.edit', compact('id'));
    }


    public function destroy($id)
    {
        $tenant = User::with('tenantProfile')->findOrFail($id);

        $fileNameToDelete = $tenant->tenantProfile->identity_document;
        if (!empty($fileNameToDelete)) if (Storage::exists($fileNameToDelete)) {
            unlink(public_path($fileNameToDelete));
        }

        $tenant->delete();

        return redirect()->route('admin.tenant.index')->with('success', 'Tenant has been deleted');
    }
}
