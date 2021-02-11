<?php

namespace App\Http\Livewire\Admin\Tenant;

use App\Mail\RegistrationMail;
use App\Models\User;
use App\Notifications\SendWelcomeEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{

    use WithFileUploads;

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


    protected $rules = [
        'name' => 'required',
        'email' => 'email|required|unique:users,email',
        'phone' => 'required',
        'address' => 'required',
        'identityNo' => 'required',
        'identityDocument' => 'nullable|file|max:5000',
    ];

    public function render()
    {
        return view('livewire.admin.tenant.create');
    }

    public function createTenant()
    {
        $this->validate();

        // store document if its available in file
        $identityDocumentToStore = null;
        //Generate random password

        $tenantPassword = Str::random(8);


        DB::beginTransaction();

        try {
            //create main table
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($tenantPassword),

            ]);

            $user->attachRole('tenant');
            //upload identity proof
            if (!empty($this->identityDocument)) {
                $path = Storage::putFile('public/documents', $this->identityDocument);
                $identityDocumentToStore = Storage::url($path);
            }
            //create tenant profile
            $user->tenantProfile()->create([
                'identity' => $this->identityNo,
                'identity_document' => $identityDocumentToStore,
                'phone' => $this->phone,
                'address' => $this->address,
                'occupation_status' => $this->occupationStatus,
                'occupation_place' => $this->occupationPlace,
                'emergency_contact_person' => $this->emergencyContactPerson,
                'emergency_contact_number' => $this->emergencyContactPhone,
            ]);

            $details = [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $tenantPassword,
               ];

            DB::commit();

        } catch (\Exception $exception) {

            Log::error($exception);
            DB::rollBack();
            return $this->alert('error', 'Problem Registering Tenant', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => false,
                'text' => $exception->getMessage(),
                'showCancelButton' => true,
                'showConfirmButton' => false,
            ]);

        }

        if (isset($details)){
            Mail::to($user->email)->send(new RegistrationMail($details));
        }

        session()->flash('success','New tenant with name '.$user->name .'has been created');

        return redirect()->route('admin.tenant.index');

        //TODO Send welcome email


    }
}
