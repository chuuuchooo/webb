@extends('layouts.dashboard')

@section('title', 'Edit Vaccination Record')

@section('content')
<div class="container-fluid responsive-container">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Vaccination Record</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('vaccination.update', $vaccination->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Child Name</label>
                    <input type="text" class="form-control" value="{{ $child->full_name }}" disabled>
                </div>
                
                <div class="mb-3">
                    <label for="vaccine_type" class="form-label">Vaccine Type</label>
                    <select class="form-select @error('vaccine_type') is-invalid @enderror" id="vaccine_type" name="vaccine_type" required>
                        <option value="">Select Vaccine</option>
                        <option value="BCG" {{ old('vaccine_type', $vaccination->vaccine_type) == 'BCG' ? 'selected' : '' }}>BCG</option>
                        <option value="Hepatitis B" {{ old('vaccine_type', $vaccination->vaccine_type) == 'Hepatitis B' ? 'selected' : '' }}>Hepatitis B</option>
                        <option value="Pentavalent Vaccine" {{ old('vaccine_type', $vaccination->vaccine_type) == 'Pentavalent Vaccine' ? 'selected' : '' }}>Pentavalent Vaccine</option>
                        <option value="Oral Polio Vaccine" {{ old('vaccine_type', $vaccination->vaccine_type) == 'Oral Polio Vaccine' ? 'selected' : '' }}>Oral Polio Vaccine</option>
                        <option value="Inactivated Polio Vaccine" {{ old('vaccine_type', $vaccination->vaccine_type) == 'Inactivated Polio Vaccine' ? 'selected' : '' }}>Inactivated Polio Vaccine</option>
                        <option value="Pneumococcal Conjugate Vaccine" {{ old('vaccine_type', $vaccination->vaccine_type) == 'Pneumococcal Conjugate Vaccine' ? 'selected' : '' }}>Pneumococcal Conjugate Vaccine</option>
                        <option value="Measles,Mumps,&Rubella" {{ old('vaccine_type', $vaccination->vaccine_type) == 'Measles,Mumps,&Rubella' ? 'selected' : '' }}>Measles, Mumps, & Rubella</option>
                    </select>
                    @error('vaccine_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="dose_number" class="form-label">Dose Number</label>
                    <input type="number" class="form-control @error('dose_number') is-invalid @enderror" id="dose_number" name="dose_number" value="{{ old('dose_number', $vaccination->dose_number) }}" min="1" max="3" required>
                    @error('dose_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="date_vaccinated" class="form-label">Date Vaccinated</label>
                    <input type="date" class="form-control @error('date_vaccinated') is-invalid @enderror" id="date_vaccinated" name="date_vaccinated" value="{{ old('date_vaccinated', $vaccination->date_vaccinated ? $vaccination->date_vaccinated->format('Y-m-d') : '') }}">
                    @error('date_vaccinated')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="Not Completed" {{ old('status', $vaccination->status) == 'Not Completed' ? 'selected' : '' }}>Not Completed</option>
                        <option value="Completed" {{ old('status', $vaccination->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Scheduled" {{ old('status', $vaccination->status) == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="next_schedule" class="form-label">Next Schedule Date</label>
                    <input type="date" class="form-control @error('next_schedule') is-invalid @enderror" id="next_schedule" name="next_schedule" value="{{ old('next_schedule', $vaccination->next_schedule ? $vaccination->next_schedule->format('Y-m-d') : '') }}">
                    @error('next_schedule')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" rows="3">{{ old('remarks', $vaccination->remarks) }}</textarea>
                    @error('remarks')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Update Record</button>
                    <a href="/immunization/{{ $child->id }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 