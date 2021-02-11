<?php

namespace App\Http\Livewire\Admin\PropertyUnit;

use App\Models\Gallery;
use App\Models\PropertyUnit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{

    use WithFileUploads;

    public $unitId;

    public $title;
    public $details;
    public $rent;
    public $deposit;
    public $commission = 0;
    public $description;
    public $photos;
    public $oldPhotos;

    protected $rules = [
        'title' => 'required',
        'details' => 'required',
        'rent' => 'required|numeric',
        'deposit' => 'nullable|numeric',
        'commission' => 'required|numeric',
        'photos.*' => 'nullable|image|mimes:gif,png,jpg,jpeg|max:3000',
    ];

    public function mount()
    {
        $unit = PropertyUnit::with('galleries')->findOrFail($this->unitId);
        $this->title = $unit->title;
        $this->details = $unit->details;
        $this->rent = $unit->rent;
        $this->deposit = $unit->deposit;
        $this->commission = $unit->commission;
        $this->description = $unit->description;
        $this->oldPhotos = $unit->galleries;
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

    public function render()
    {
        return view('livewire.admin.property-unit.edit');
    }

    public function updateRoom()
    {

        $this->validate();

        $room = PropertyUnit::with('galleries')->findOrFail($this->unitId);


        DB::beginTransaction();

        try {
            $room->update([
                'title' => $this->title,
                'details' => $this->details,
                'rent' => $this->rent,
                'deposit' => $this->deposit,
                'commission' => $this->commission,
                'description' => $this->description,
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
                'position' => 'center',
                'timer' => 3000,
                'toast' => false,
                'text' => $exception->getMessage(),
            ]);
        }

        session()->flash('success','Property unit has been updated');

        return redirect()->route('admin.property-unit.show',$room->id);

    }

    public function deleteExistingImage($id)
    {
        $gallery = Gallery::findOrFail($id);

        if (Storage::exists($gallery->image)) {
            unlink(public_path($gallery->image));
        }

        $gallery->delete();

        $this->alert('success', 'Success', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
            'text' => 'An image has been removed from the unit gallery',
            'showCancelButton' => false,
            'showConfirmButton' => false,
        ]);

        $this->oldPhotos = Gallery::where('gallerable_id', $this->unitId)
            ->where('gallerable_type', 'App\Models\PropertyUnit')
            ->get();

    }

    public function removePhoto($index)
    {
        array_splice($this->photos, $index, 1);
    }


}
