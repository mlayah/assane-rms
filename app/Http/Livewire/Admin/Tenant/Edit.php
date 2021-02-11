<?php

namespace App\Http\Livewire\Admin\Tenant;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{

    use WithFileUploads;
    public $tenantId;

    public $name;
    public $email;
    public $phone;
    public $address;
    public $identityNo;
    public $identityDocument;
    public $occupationStatus;
    public $occupationPlace;
    public $emergencyContactPerson;
    public $emergencyContactPhone;
    public $currentIdentityDocument;


    public function mount()
    {


        $tenant = User::with('tenantProfile')->findOrFail($this->tenantId);
        $this->name = $tenant->name;
        $this->email = $tenant->email;
        $this->phone = $tenant->tenantProfile->phone;
        $this->address = $tenant->tenantProfile->address;
        $this->identityNo = $tenant->tenantProfile->identity;
        $this->occupationStatus = $tenant->tenantProfile->occupation_status;
        $this->occupationPlace = $tenant->tenantProfile->occupation_place;
        $this->emergencyContactPerson = $tenant->tenantProfile->emergency_contact_person;
        $this->emergencyContactPhone = $tenant->tenantProfile->emergency_contact_number;
        $this->currentIdentityDocument = $tenant->tenantProfile->identity_document;
    }

    public function render()
    {
        return view('livewire.admin.tenant.edit');
    }

    public function updateTenant()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'email|required|unique:users,email,'.$this->tenantId,
            'phone' => 'required',
            'address' => 'required',
            'identityNo' => 'required',
            'identityDocument' => 'nullable|file|max:5000',
        ]);

        $tenant=User::with('tenantProfile')->findOrFail($this->tenantId);

        try {

            $tenant->update([
                'name'=>$this->name,
                'email'=>$this->email,
            ]);

            $tenant->tenantProfile()->update([
                'identity' => $this->identityNo,
                //'identity_document' => $identityDocumentToStore,
                'phone' => $this->phone,
                'address' => $this->address,
                'occupation_status' => $this->occupationStatus,
                'occupation_place' => $this->occupationPlace,
                'emergency_contact_person' => $this->emergencyContactPerson,
                'emergency_contact_number' => $this->emergencyContactPhone,
            ]);

            //update identity document
            if (!empty($this->identityDocument)) {
                $path = Storage::putFile('public/documents', $this->identityDocument);
                $filenameToStore = Storage::url($path);

                //get value of existing proof
                $fileNameToDelete = $this->currentIdentityDocument;
                //update current one
                $tenant->tenantProfile()->update(['identity_document' => $filenameToStore]);
                //Delete if previous file exists
                if (!empty($fileNameToDelete) and Storage::exists($fileNameToDelete)) {

                    unlink(public_path($fileNameToDelete));
                }
            }
            DB::commit();

        }
        catch (\Exception $ex){
            Log::error($ex);
            DB::rollBack();
            return $this->alert('error', 'Problem updating Tenant', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => false,
                'text' => $ex->getMessage(),
                'showCancelButton' => true,
                'showConfirmButton' => false,
            ]);

        }

        session()->flash('success','Tenant details have been updated');
        return redirect()->route('admin.tenant.show',$tenant->id);

    }
}
