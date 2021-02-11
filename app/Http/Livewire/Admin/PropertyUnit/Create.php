<?php

namespace App\Http\Livewire\Admin\PropertyUnit;

use App\Models\Property;
use App\Models\PropertyUnit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use function Symfony\Component\Translation\t;

class Create extends Component
{

    use WithFileUploads;

    public $title;
    public $details;
    public $rent;
    public $deposit;
    public $commission=0;
    public $description;
    public $photos;
    public $propertyId;
    public $landlordId;
    public $managerId;
    public $rooms = [];

    //trick to clean up file input after saving data
    public $iteration = 0;

    protected $rules = [
        'title' => 'required',
        'propertyId' => 'required',
        'details' => 'required',
        'rent' => 'required|numeric',
        'deposit' => 'nullable|numeric',
        'commission' => 'required|numeric',
        'photos.*' => 'nullable|image|mimes:gif,png,jpg,jpeg|max:3000',
    ];

    protected $messages=[
        'propertyId.required'=>'Select the parent property'
    ];

    protected $listeners = [
        'refreshComponent' => '$refresh'
    ];


    public function render()
    {

        $properties = Property::orderBy('title')->pluck('id', 'title');
        return view('livewire.admin.property-unit.create', ['properties' => $properties]);
    }

    public function updatedPropertyId()
    {
        $this->landlordId=Property::find($this->propertyId)->landlord_id ?? null;
        $this->getPropertyRooms();
    }

    public function updatedPhotos()
    {
        $this->validate(
            ['photos.*' => 'image|mimes:jpg,png,jpeg,gif|max:5000'],
            ['photos.*.image' => 'Ensure all files uploaded are supported images',
                'photos.*.max' => 'Each image cannot exceed size of 5MB',

            ]
        );
    }

    public function removePhoto($index)
    {
        array_splice($this->photos, $index, 1);
    }


    public function createRoom()
    {
        $this->validate();


        DB::beginTransaction();

        try {
            $room = PropertyUnit::create([
                'title' => $this->title,
                'details' => $this->details,
                'rent' => $this->rent,
                'deposit' => $this->deposit,
                'commission' => $this->commission,
                'description' => $this->description,
                'property_id' => $this->propertyId,
                'manager_id' => $this->managerId,
                'landlord_id' => $this->landlordId,
            ]);

            if (isset($this->photos)) {
                foreach ($this->photos as $photo) {

                    $path = Storage::putFile('public/gallery', $photo);
                    $imageUrl = Storage::url($path);
                    $room->galleries()->create(
                        ['image' => $imageUrl]
                    );


                }
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return $this->alert('error', 'Error', [
                'position' =>  'center',
                'timer' =>  3000,
                'toast' =>  false,
                'text' =>  $exception->getMessage(),
            ]);
        }

        //refresh added rooms
        $this->getPropertyRooms();

        $this->resetFields();
        $this->iteration++;

       return  $this->alert('success', 'Success', [
            'position' =>  'top-end',
            'timer' =>  3000,
            'toast' =>  true,
            'text' =>  'Property Unit has been created',
            'showCancelButton' =>  false,
            'showConfirmButton' =>  false,
        ]);

    }

    protected function getPropertyRooms()
    {
        $this->rooms = PropertyUnit::where('property_id', $this->propertyId)->get();
    }

    protected function resetFields()
    {
        $this->reset(['title', 'commission', 'details', 'description', 'rent', 'deposit']);
        $this->photos = [];
    }
}
