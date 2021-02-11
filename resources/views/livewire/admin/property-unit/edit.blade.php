<div>

    @push('header-css')
        <style>
            .img-wrap {
                position: relative;
                display: inline-block;
                font-size: 0;
            }

            .img-wrap .close {
                position: absolute;
                top: 2px;
                right: 2px;
                z-index: 100;
                background-color: #FFF;
                padding: 5px 2px 2px;
                color: #000;
                font-weight: bold;
                cursor: pointer;
                opacity: .2;
                text-align: center;
                font-size: 22px;
                line-height: 10px;
                border-radius: 50%;
            }

            .img-wrap:hover .close {
                opacity: 1;
            }
        </style>

    @endpush


    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <!-- project card -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title font-16 mb-4">{{ __('unit.Update Property Unit') }}</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('unit.Property Unit Title') }} <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" wire:model.defer="title">
                                @error('title') <span class="text-danger font-weight-semibold">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastname">{{ __('unit.Rent') }} <span class="text-danger">*</span> </label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"
                                              id="basic-addon1">@setting('currency_symbol')</span>
                                    </div>
                                    <input type="number" min="0.0" class="form-control" aria-label="rent"
                                           aria-describedby="basic-addon1" wire:model.defer="rent">


                                </div>
                                @error('rent') <span class="text-danger font-weight-semibold">{{ $message }}</span>
                                @enderror

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('unit.Commission') }} <span class="text-danger">*</span></label>
                                <input wire:model.defer="commission" class="form-control" type="number">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastname">{{ __('unit.Deposit') }} </label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"
                                              id="basic-addon1">@setting('currency_symbol')</span>
                                    </div>
                                    <input type="number" min="0.0" class="form-control" aria-label="deposit"
                                           aria-describedby="basic-addon1" wire:model.defer="deposit">


                                </div>
                                @error('deposit') <span class="text-danger font-weight-semibold">{{ $message }}</span>
                                @enderror


                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ __('unit.Details') }} <span class="text-danger">*</span></label>
                                <input wire:model.defer="details" class="form-control" type="text"
                                       placeholder="e.g two bedroom,studio,villa">
                                @error('details') <span class="text-danger font-weight-semibold">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ __('unit.Description/Features ') }} <span class="text-danger">*</span></label>
                                <textarea wire:model.defer="description" class="form-control" id="example-textarea"
                                          rows="3" placeholder="Room features and description"></textarea>
                                @error('description') <span
                                    class="text-danger font-weight-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ __('unit.Upload Room Images') }}</label>
                                <div class="input-group mb-3">
                                    <input type="file" class="form-control" id="file" placeholder="Upload Room Images"
                                           multiple wire:model.lazy="photos" aria-label="Room Images"
                                           aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2">
                                            <div class="spinner-border spinner-border-sm" wire:loading
                                                 wire:target="photos" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @error('photos') <span class="text-danger tx-bold">{{ $message }}</span> @enderror
                            @error('photos.*')
                            <span class="text-danger tx-bold">{{ $message }}</span>
                            @enderror



                            @if ($photos && !$errors->has('photos.*'))
                                @foreach($photos as $photo)
                                    <div class="img-wrap mr-2 mb-2" wire:key="{{$loop->index}}">
                                        <span wire:click="removePhoto({{ $loop->index }})" class="close">&times;</span>
                                        <img src="{{ $photo->temporaryUrl() }}" alt="image" style="width:auto;"
                                             class="img-fluid avatar-lg rounded">
                                    </div>
                                @endforeach

                            @endif
                        </div>
                    </div>

                    <div class="text-right">
                        <button class="btn btn-success waves-effect waves-light" wire:click="updateRoom"
                                wire:attr="disabled">
                            <span class="spinner-border spinner-border-sm" wire:loading wire:target="updateRoom"
                                  role="status" aria-hidden="true"></span>
                            {{ __('unit.Update Property Unit') }}
                        </button>
                    </div>


                </div> <!-- end card-body-->

            </div> <!-- end card-->

        </div> <!-- end col -->

        <div class="col-xl-4 col-lg-5">


            <div class="card">
                <div class="card-body">
                    <h5 class="card-title font-16 mb-3">{{ __('unit.Room Images') }} </h5>

                    <div class="row">
                        @forelse ($oldPhotos as $item)
                            <div class="col-4">
                                <div class="img-wrap mr-2 mb-2" wire:key="old-{{$loop->index}}">
                                    <span wire:click="deleteExistingImage({{ $item->id }})" class="close">&times;</span>
                                    <img src="{{ url($item->image)}}" alt="image" style="width:auto;"
                                         class="img-fluid avatar-lg rounded">
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info" role="alert">
                                    <i class="mdi mdi-alert-circle-outline mr-2"></i>
                                    {{ __('unit.This property unit does not have any images .') }}
                                </div>
                            </div>
                        @endforelse
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
