@extends('layouts.dashboard')

@section('title', 'Add Family Planning Record')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add Family Planning Record</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('family-planning.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="house_lot_no" class="form-label">House/Lot No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('house_lot_no') is-invalid @enderror" id="house_lot_no" name="house_lot_no" value="{{ old('house_lot_no') }}" required>
                            @error('house_lot_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="purok" class="form-label">Purok <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('purok') is-invalid @enderror" id="purok" name="purok" value="{{ old('purok') }}" required>
                            @error('purok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="barangay" class="form-label">Barangay <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('barangay') is-invalid @enderror" id="barangay" name="barangay" value="{{ old('barangay') }}" required>
                            @error('barangay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                            @error('middle_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sex">Sex</label>
                            <select class="form-control" id="sex" name="sex" required>
                                <option value="">Select Sex</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="birthdate" class="form-label">Birthdate <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('birthdate') is-invalid @enderror" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
                            @error('birthdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required>
                            @error('contact_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="intended_method" class="form-label">Intended Method</label>
                    <select class="form-select @error('intended_method') is-invalid @enderror" id="intended_method" name="intended_method">
                        <option value="">Select Method</option>
                        <option value="Modern FP method" {{ old('intended_method') == 'Modern FP method' ? 'selected' : '' }}>Modern FP method</option>
                        <option value="Condom" {{ old('intended_method') == 'Condom' ? 'selected' : '' }}>Condom</option>
                        <option value="IUD" {{ old('intended_method') == 'IUD' ? 'selected' : '' }}>IUD (intrauterine device)</option>
                        <option value="Pills" {{ old('intended_method') == 'Pills' ? 'selected' : '' }}>Pills</option>
                        <option value="Injectable" {{ old('intended_method') == 'Injectable' ? 'selected' : '' }}>Injectable</option>
                        <option value="Vasectomy" {{ old('intended_method') == 'Vasectomy' ? 'selected' : '' }}>Vasectomy</option>
                        <option value="BTL" {{ old('intended_method') == 'BTL' ? 'selected' : '' }}>BTL (bilateral tubal ligation)</option>
                        <option value="Implant" {{ old('intended_method') == 'Implant' ? 'selected' : '' }}>Implant</option>
                        <option value="CMM/Billings" {{ old('intended_method') == 'CMM/Billings' ? 'selected' : '' }}>CMM/Billings (cervical mucus method/Billings ovulation method)</option>
                        <option value="BBT" {{ old('intended_method') == 'BBT' ? 'selected' : '' }}>BBT (basal body temperature)</option>
                        <option value="Sympto-thermal" {{ old('intended_method') == 'Sympto-thermal' ? 'selected' : '' }}>Sympto-thermal</option>
                        <option value="SDM" {{ old('intended_method') == 'SDM' ? 'selected' : '' }}>SDM (standard days method)</option>
                        <option value="LAM" {{ old('intended_method') == 'LAM' ? 'selected' : '' }}>LAM (lactational amenorrhea method)</option>
                    </select>
                    @error('intended_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="date_served" class="form-label">Date Served</label>
                    <input type="date" class="form-control @error('date_served') is-invalid @enderror" id="date_served" name="date_served" value="{{ old('date_served') }}">
                    @error('date_served')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="fp_method" class="form-label">FP Method</label>
                    <select class="form-select @error('fp_method') is-invalid @enderror" id="fp_method" name="fp_method">
                        <option value="">Select Method</option>
                        <option value="Modern FP method" {{ old('fp_method') == 'Modern FP method' ? 'selected' : '' }}>Modern FP method</option>
                        <option value="Condom" {{ old('fp_method') == 'Condom' ? 'selected' : '' }}>Condom</option>
                        <option value="IUD" {{ old('fp_method') == 'IUD' ? 'selected' : '' }}>IUD (intrauterine device)</option>
                        <option value="Pills" {{ old('fp_method') == 'Pills' ? 'selected' : '' }}>Pills</option>
                        <option value="Injectable" {{ old('fp_method') == 'Injectable' ? 'selected' : '' }}>Injectable</option>
                        <option value="Vasectomy" {{ old('fp_method') == 'Vasectomy' ? 'selected' : '' }}>Vasectomy</option>
                        <option value="BTL" {{ old('fp_method') == 'BTL' ? 'selected' : '' }}>BTL (bilateral tubal ligation)</option>
                        <option value="Implant" {{ old('fp_method') == 'Implant' ? 'selected' : '' }}>Implant</option>
                        <option value="CMM/Billings" {{ old('fp_method') == 'CMM/Billings' ? 'selected' : '' }}>CMM/Billings (cervical mucus method/Billings ovulation method)</option>
                        <option value="BBT" {{ old('fp_method') == 'BBT' ? 'selected' : '' }}>BBT (basal body temperature)</option>
                        <option value="Sympto-thermal" {{ old('intended_method') == 'Sympto-thermal' ? 'selected' : '' }}>Sympto-thermal</option>
                        <option value="SDM" {{ old('fp_method') == 'SDM' ? 'selected' : '' }}>SDM (standard days method)</option>
                        <option value="LAM" {{ old('fp_method') == 'LAM' ? 'selected' : '' }}>LAM (lactational amenorrhea method)</option>
                    </select>
                    @error('fp_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="provider_category" class="form-label">Provider Category</label>
                    <select class="form-select @error('provider_category') is-invalid @enderror" id="provider_category" name="provider_category">
                        <option value="">Select Category</option>
                        <option value="BHS" {{ old('provider_category') == 'BHS' ? 'selected' : '' }}>BHS (barangay health station)</option>
                        <option value="Pharmacy" {{ old('provider_category') == 'Pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                        <option value="" {{ old('provider_category') == '' ? 'selected' : '' }}>(Blanks)</option>
                    </select>
                    @error('provider_category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="provider_name" class="form-label">Provider Name</label>
                    <input type="text" class="form-control @error('provider_name') is-invalid @enderror" id="provider_name" name="provider_name" value="{{ old('provider_name') }}">
                    @error('provider_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="mode_of_service_delivery" class="form-label">Mode of Service Delivery</label>
                    <select class="form-select @error('mode_of_service_delivery') is-invalid @enderror" id="mode_of_service_delivery" name="mode_of_service_delivery">
                        <option value="">Select Mode</option>
                        <option value="Regular facility-based" {{ old('mode_of_service_delivery') == 'Regular facility-based' ? 'selected' : '' }}>Regular facility-based</option>
                    </select>
                    @error('mode_of_service_delivery')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="remarks" class="form-label">Remarks</label>
                    <select class="form-select @error('remarks') is-invalid @enderror" id="remarks" name="remarks">
                        <option value="">Select Remarks</option>
                        <option value="Served" {{ old('remarks') == 'Served' ? 'selected' : '' }}>Served</option>
                        <option value="Counselled" {{ old('remarks') == 'Counselled' ? 'selected' : '' }}>Counselled</option>
                        <option value="Counselled – decided not to use" {{ old('remarks') == 'Counselled – decided not to use' ? 'selected' : '' }}>Counselled – decided not to use</option>
                        <option value="Pregnant" {{ old('remarks') == 'Pregnant' ? 'selected' : '' }}>Pregnant</option>
                        <option value="Transferred" {{ old('remarks') == 'Transferred' ? 'selected' : '' }}>Transferred</option>
                        <option value="Not found" {{ old('remarks') == 'Not found' ? 'selected' : '' }}>Not found</option>
                        <option value="LAM" {{ old('remarks') == 'LAM' ? 'selected' : '' }}>LAM (lactational amenorrhea method)</option>
                        <option value="No update" {{ old('remarks') == 'No update' ? 'selected' : '' }}>No update</option>
                        <option value="Single" {{ old('remarks') == 'Single' ? 'selected' : '' }}>Single</option>
                        <option value="Widow" {{ old('remarks') == 'Widow' ? 'selected' : '' }}>Widow</option>
                        <option value="OFW partner" {{ old('remarks') == 'OFW partner' ? 'selected' : '' }}>OFW partner (overseas Filipino worker partner)</option>
                        <option value="TAHBSO" {{ old('remarks') == 'TAHBSO' ? 'selected' : '' }}>TAHBSO (total abdominal hysterectomy bilateral salpingo-oophorectomy)</option>
                        <option value="Bil. oophorectomy" {{ old('remarks') == 'Bil. oophorectomy' ? 'selected' : '' }}>Bil. oophorectomy (bilateral oophorectomy)</option>
                        <option value="Achieving" {{ old('remarks') == 'Achieving' ? 'selected' : '' }}>Achieving (trying to conceive)</option>
                        <option value="Achieving – pregnant" {{ old('remarks') == 'Achieving – pregnant' ? 'selected' : '' }}>Achieving – pregnant</option>
                        <option value="Menopause" {{ old('remarks') == 'Menopause' ? 'selected' : '' }}>Menopause</option>
                        <option value="Deceased" {{ old('remarks') == 'Deceased' ? 'selected' : '' }}>Deceased</option>
                        <option value="FP current user" {{ old('remarks') == 'FP current user' ? 'selected' : '' }}>FP current user (family planning current user)</option>
                    </select>
                    @error('remarks')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="date_counselled_pregnant" class="form-label">Date Counselled/Pregnant – Expected Date of Delivery</label>
                    <input type="date" class="form-control @error('date_counselled_pregnant') is-invalid @enderror" id="date_counselled_pregnant" name="date_counselled_pregnant" value="{{ old('date_counselled_pregnant') }}">
                    @error('date_counselled_pregnant')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="other_notes" class="form-label">Other Notes</label>
                    <textarea class="form-control @error('other_notes') is-invalid @enderror" id="other_notes" name="other_notes" rows="3">{{ old('other_notes') }}</textarea>
                    @error('other_notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="date_encoded" class="form-label">Date Encoded</label>
                    <input type="date" class="form-control @error('date_encoded') is-invalid @enderror" id="date_encoded" name="date_encoded" value="{{ old('date_encoded', date('Y-m-d')) }}">
                    @error('date_encoded')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Save Record</button>
                    <a href="{{ route('family-planning.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection