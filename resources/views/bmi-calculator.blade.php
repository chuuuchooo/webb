@extends('layouts.dashboard')

@section('title', 'BMI Calculator')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-8 col-lg-10 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Calculate Your Body Mass Index</h6>
                </div>
                <div class="card-body">
                    <form id="bmiForm">
                        <div class="mb-3">
                            <label for="heightUnit" class="form-label">Height Unit</label>
                            <select class="form-select" id="heightUnit">
                                <option value="cm" selected>Centimeters (cm)</option>
                                <option value="m">Meters (m)</option>
                                <option value="ft">Feet and Inches</option>
                            </select>
                        </div>
                        
                        <div class="mb-3" id="heightCmContainer">
                            <label for="heightCm" class="form-label">Height (cm)</label>
                            <input type="number" class="form-control" id="heightCm" placeholder="Enter height in cm" min="50" max="250">
                        </div>
                        
                        <div class="mb-3 d-none" id="heightMContainer">
                            <label for="heightM" class="form-label">Height (m)</label>
                            <input type="number" class="form-control" id="heightM" placeholder="Enter height in meters" min="0.5" max="2.5" step="0.01">
                        </div>
                        
                        <div class="row d-none" id="heightFtContainer">
                            <div class="col-md-6 mb-3">
                                <label for="heightFt" class="form-label">Feet</label>
                                <input type="number" class="form-control" id="heightFt" placeholder="Feet" min="1" max="8">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="heightIn" class="form-label">Inches</label>
                                <input type="number" class="form-control" id="heightIn" placeholder="Inches" min="0" max="11">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="weightUnit" class="form-label">Weight Unit</label>
                            <select class="form-select" id="weightUnit">
                                <option value="kg" selected>Kilograms (kg)</option>
                                <option value="lbs">Pounds (lbs)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3" id="weightKgContainer">
                            <label for="weightKg" class="form-label">Weight (kg)</label>
                            <input type="number" class="form-control" id="weightKg" placeholder="Enter weight in kg" min="20" max="300">
                        </div>
                        
                        <div class="mb-3 d-none" id="weightLbsContainer">
                            <label for="weightLbs" class="form-label">Weight (lbs)</label>
                            <input type="number" class="form-control" id="weightLbs" placeholder="Enter weight in pounds" min="40" max="700">
                        </div>
                        
                        <div class="mb-3">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" class="form-control" id="age" placeholder="Enter age" min="2" max="120">
                        </div>
                        
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        
                        <button type="button" class="btn btn-primary w-100" id="calculateBmi">Calculate BMI</button>
                    </form>
                    
                    <div class="mt-4 d-none" id="bmiResult">
                        <h4 class="text-center mb-3">Your BMI Results</h4>
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>BMI Value</h5>
                                        <h2 id="bmiValue">--</h2>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Category</h5>
                                        <h4 id="bmiCategory">--</h4>
                                    </div>
                                </div>
                                <div class="progress mt-3">
                                    <div id="bmiProgressBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <small>Underweight</small>
                                    <small>Normal</small>
                                    <small>Overweight</small>
                                    <small>Obese</small>
                                </div>
                                <div class="mt-3">
                                    <p id="bmiInterpretation">Enter your details and click "Calculate BMI" to see your results.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    setupBmiCalculator();
});

function setupBmiCalculator() {
    // Set up height unit toggle
    document.getElementById('heightUnit').addEventListener('change', function() {
        const unit = this.value;
        
        // Hide all height input containers
        document.getElementById('heightCmContainer').classList.add('d-none');
        document.getElementById('heightMContainer').classList.add('d-none');
        document.getElementById('heightFtContainer').classList.add('d-none');
        
        // Show the selected height input container
        if (unit === 'cm') {
            document.getElementById('heightCmContainer').classList.remove('d-none');
        } else if (unit === 'm') {
            document.getElementById('heightMContainer').classList.remove('d-none');
        } else if (unit === 'ft') {
            document.getElementById('heightFtContainer').classList.remove('d-none');
        }
    });
    
    // Set up weight unit toggle
    document.getElementById('weightUnit').addEventListener('change', function() {
        const unit = this.value;
        
        // Hide all weight input containers
        document.getElementById('weightKgContainer').classList.add('d-none');
        document.getElementById('weightLbsContainer').classList.add('d-none');
        
        // Show the selected weight input container
        if (unit === 'kg') {
            document.getElementById('weightKgContainer').classList.remove('d-none');
        } else if (unit === 'lbs') {
            document.getElementById('weightLbsContainer').classList.remove('d-none');
        }
    });
    
    // Set up calculate button
    document.getElementById('calculateBmi').addEventListener('click', calculateBmi);
}

function calculateBmi() {
    // Get height in meters
    let heightInMeters = 0;
    const heightUnit = document.getElementById('heightUnit').value;
    
    if (heightUnit === 'cm') {
        const heightCm = parseFloat(document.getElementById('heightCm').value);
        if (isNaN(heightCm) || heightCm < 50 || heightCm > 250) {
            alert('Please enter a valid height in cm (50-250 cm).');
            return;
        }
        heightInMeters = heightCm / 100;
    } else if (heightUnit === 'm') {
        heightInMeters = parseFloat(document.getElementById('heightM').value);
        if (isNaN(heightInMeters) || heightInMeters < 0.5 || heightInMeters > 2.5) {
            alert('Please enter a valid height in meters (0.5-2.5 m).');
            return;
        }
    } else if (heightUnit === 'ft') {
        const feet = parseInt(document.getElementById('heightFt').value);
        const inches = parseInt(document.getElementById('heightIn').value) || 0;
        if (isNaN(feet) || feet < 1 || feet > 8) {
            alert('Please enter a valid height in feet (1-8 ft).');
            return;
        }
        if (isNaN(inches) || inches < 0 || inches > 11) {
            alert('Please enter a valid height in inches (0-11 in).');
            return;
        }
        heightInMeters = ((feet * 12) + inches) * 0.0254;
    }
    
    // Get weight in kg
    let weightInKg = 0;
    const weightUnit = document.getElementById('weightUnit').value;
    
    if (weightUnit === 'kg') {
        weightInKg = parseFloat(document.getElementById('weightKg').value);
        if (isNaN(weightInKg) || weightInKg < 20 || weightInKg > 300) {
            alert('Please enter a valid weight in kg (20-300 kg).');
            return;
        }
    } else if (weightUnit === 'lbs') {
        const weightLbs = parseFloat(document.getElementById('weightLbs').value);
        if (isNaN(weightLbs) || weightLbs < 40 || weightLbs > 700) {
            alert('Please enter a valid weight in lbs (40-700 lbs).');
            return;
        }
        weightInKg = weightLbs * 0.453592;
    }
    
    // Check age
    const age = parseInt(document.getElementById('age').value);
    if (isNaN(age) || age < 2 || age > 120) {
        alert('Please enter a valid age (2-120 years).');
        return;
    }
    
    // Calculate BMI
    const bmi = weightInKg / (heightInMeters * heightInMeters);
    
    // Determine BMI category
    let category = '';
    let progressBarWidth = 0;
    let progressBarColor = '';
    let interpretation = '';
    
    if (bmi < 18.5) {
        category = 'Underweight';
        progressBarWidth = (bmi / 40) * 100;
        progressBarColor = '#36b9cc'; // Info blue
        interpretation = 'Your BMI suggests you are underweight. Consider consulting with a healthcare professional about achieving a healthy weight.';
    } else if (bmi < 25) {
        category = 'Normal weight';
        progressBarWidth = (bmi / 40) * 100;
        progressBarColor = '#1cc88a'; // Success green
        interpretation = 'Your BMI is in the normal weight range. Maintain a balanced diet and regular physical activity.';
    } else if (bmi < 30) {
        category = 'Overweight';
        progressBarWidth = (bmi / 40) * 100;
        progressBarColor = '#f6c23e'; // Warning yellow
        interpretation = 'Your BMI suggests you are overweight. Consider a balanced diet and regular physical activity.';
    } else {
        category = 'Obese';
        progressBarWidth = (bmi / 40) * 100;
        if (progressBarWidth > 100) progressBarWidth = 100;
        progressBarColor = '#e74a3b'; // Danger red
        interpretation = 'Your BMI suggests obesity. It is recommended to consult with a healthcare professional for guidance on weight management.';
    }
    
    // Age-specific note
    if (age < 18) {
        interpretation += ' Note: BMI calculations for children and teenagers may have different interpretations. Please consult with a healthcare professional for a complete assessment.';
    }
    
    // Gender-specific note
    const gender = document.getElementById('gender').value;
    if (gender === 'female') {
        interpretation += ' For women, body composition can vary. Consider other factors like waist circumference for a more complete picture.';
    } else {
        interpretation += ' For men, muscle mass might affect BMI readings. Waist circumference can be another useful measurement.';
    }
    
    // Display the result
    document.getElementById('bmiValue').textContent = bmi.toFixed(1);
    document.getElementById('bmiCategory').textContent = category;
    document.getElementById('bmiInterpretation').textContent = interpretation;
    
    // Update progress bar
    const progressBar = document.getElementById('bmiProgressBar');
    progressBar.style.width = progressBarWidth + '%';
    progressBar.style.backgroundColor = progressBarColor;
    progressBar.setAttribute('aria-valuenow', progressBarWidth);
    
    // Show the result container
    document.getElementById('bmiResult').classList.remove('d-none');
}
</script>
@endsection 