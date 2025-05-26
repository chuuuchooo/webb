@extends('layouts.dashboard')

@section('title', 'Add Child Immunization Record')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add New Child Immunization Record</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('immunization.store') }}" method="POST">
                @csrf
                
                <div class="row mb-4">
                    <div class="col-12">
                        <h5>Personal Information</h5>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="house_lot_no" class="form-label">House/Lot No.</label>
                            <input type="text" class="form-control @error('house_lot_no') is-invalid @enderror" id="house_lot_no" name="house_lot_no" value="{{ old('house_lot_no') }}" required>
                            @error('house_lot_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="purok" class="form-label">Purok</label>
                            <input type="text" class="form-control @error('purok') is-invalid @enderror" id="purok" name="purok" value="{{ old('purok') }}" required>
                            @error('purok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="barangay" class="form-label">Barangay</label>
                            <input type="text" class="form-control @error('barangay') is-invalid @enderror" id="barangay" name="barangay" value="{{ old('barangay') }}" required>
                            @error('barangay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Child's Information -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="middle_name" class="form-label">Middle Name (Optional)</label>
                            <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                            @error('middle_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="birthdate" class="form-label">Birthdate</label>
                            <input type="date" class="form-control @error('birthdate') is-invalid @enderror" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
                            @error('birthdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="birthplace" class="form-label">Birthplace</label>
                            <input type="text" class="form-control @error('birthplace') is-invalid @enderror" id="birthplace" name="birthplace" value="{{ old('birthplace') }}" required>
                            @error('birthplace')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="sex" class="form-label">Sex</label>
                            <select class="form-select @error('sex') is-invalid @enderror" id="sex" name="sex" required>
                                <option value="">Select Sex</option>
                                <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('sex')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="mothers_name" class="form-label">Mother's Name</label>
                            <input type="text" class="form-control @error('mothers_name') is-invalid @enderror" id="mothers_name" name="mothers_name" value="{{ old('mothers_name') }}" required>
                            @error('mothers_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="fathers_name" class="form-label">Father's Name</label>
                            <input type="text" class="form-control @error('fathers_name') is-invalid @enderror" id="fathers_name" name="fathers_name" value="{{ old('fathers_name') }}" required>
                            @error('fathers_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="birth_weight" class="form-label">Birth Weight (kg)</label>
                            <input type="number" step="0.01" class="form-control @error('birth_weight') is-invalid @enderror" id="birth_weight" name="birth_weight" value="{{ old('birth_weight') }}" required>
                            @error('birth_weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="birth_height" class="form-label">Birth Height (cm)</label>
                            <input type="number" step="0.01" class="form-control @error('birth_height') is-invalid @enderror" id="birth_height" name="birth_height" value="{{ old('birth_height') }}" required>
                            @error('birth_height')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3 mt-4">
                    <button type="submit" class="btn btn-primary">Save Record</button>
                    <a href="{{ route('immunization.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Automatically update the age display when birthdate changes
document.addEventListener('DOMContentLoaded', function() {
    const birthdateInput = document.getElementById('birthdate');
    
    function updateAgeDisplay() {
        const birthdate = new Date(birthdateInput.value);
        if (isNaN(birthdate.getTime())) return;
        
        const today = new Date();
        let years = today.getFullYear() - birthdate.getFullYear();
        let months = today.getMonth() - birthdate.getMonth();
        let days = today.getDate() - birthdate.getDate();
        
        if (days < 0) {
            months--;
            const lastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
            days += lastMonth.getDate();
        }
        
        if (months < 0) {
            years--;
            months += 12;
        }
        
        // Display age calculation result
        // This is just for display purposes - actual calculations happen in the model
    }
    
    if (birthdateInput) {
        birthdateInput.addEventListener('change', updateAgeDisplay);
        updateAgeDisplay(); // Run on page load
    }
});
</script>
@endsection