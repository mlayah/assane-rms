<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">{{__('tenant.Register New Tenant')}}</h4>
                    <p class="text-muted font-13">
                        {{__('tenant.Tenant Create Subtitle.')}}
                    </p>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label">{{__('tenant.Full Name')}}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Tenant full name"
                                wire:model.defer="name">

                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label">{{__('tenant.Email')}}</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" wire:model.defer="email">
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>


                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label">{{__('tenant.Phone Number')}}</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" wire:model.defer="phone">
                            @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label class="col-form-label">{{__('tenant.Identity No / Passport')}}</label>
                            <input type="text" class="form-control @error('identityNo') is-invalid @enderror" wire:model.defer="identityNo">
                            @error('identityNo') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                    </div>
                    <div class="form-row">

                        <div class="form-group col-md-6">
                            <label class="col-form-label">{{__('tenant.Identification Document')}}</label>
                            <input type="file" class="form-control-file @error('identityDocument') is-invalid @enderror" wire:model.defer="identityDocument">
                            @error('identityDocument') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label">{{__('tenant.Address')}}</label>
                            <input type="text" class="form-control" wire:model.defer="address">
                            @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>


                    <h5 class="mb-3 text-uppercase bg-light p-2">
                        <i class="mdi mdi-office-building mr-1"></i>
                        {{__('tenant.Place Of Work')}}
                    </h5>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputState" class="col-form-label">{{__('tenant.Occupation Status')}}</label>
                            <select id="inputState" class="form-control" wire:model="occupationStatus">
                                <option value="">{{__('tenant.Choose')}}</option>
                                <option>Employee</option>
                                <option>Employer</option>
                                <option>Self Employed</option>
                                <option>Others</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label">{{__('tenant.Occupation Place')}}</label>
                            <input type="text" class="form-control" wire:model.defer="occupationPlace">
                        </div>
                    </div>

                    <h5 class="mb-3 text-uppercase bg-light p-2">
                        <i class="mdi mdi-office-building mr-1"></i>
                        {{__('tenant.Incase of emergency,contact:')}}
                    </h5>

                    <div class="form-row">

                        <div class="form-group col-md-6">
                            <label class="col-form-label">{{__('tenant.Name')}}</label>
                            <input type="text" class="form-control" wire:model.defer="emergencyContactPerson">


                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label">{{__('tenant.Phone Number')}}</label>
                            <input type="text" class="form-control" wire:model.defer="emergencyContactPhone">
                        </div>
                    </div>



                    <button class="btn btn-primary waves-effect waves-light" wire:click="createTenant"
                        wire:attr="disabled">
                        <span class="spinner-border spinner-border-sm" wire:loading wire:target="createTenant"
                            role="status" aria-hidden="true"></span>
                        {{__('tenant.Register Tenant')}}
                    </button>

                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
</div>
