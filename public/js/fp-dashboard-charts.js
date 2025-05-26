// fp-dashboard-charts.js
// Handles fetching and rendering Family Planning statistics charts for the home dashboard

document.addEventListener('DOMContentLoaded', function () {
    const endpoint = '/api/user/family-planning-stats';
    fetch(endpoint)
        .then(response => response.json())
        .then(data => renderFamilyPlanningCharts(data));
});

window.fpCompletedRecordsChart = window.fpCompletedRecordsChart || null;

function renderFamilyPlanningCharts(data) {
    // Records this month
    document.getElementById('fp-records-this-month').textContent = data.recordsThisMonth;
    document.getElementById('fp-records-this-month-desc').textContent = data.descriptions.recordsThisMonth;

    // Completed Records Card
    if (data.completedRecords !== undefined) {
        document.getElementById('fp-completed-records').textContent = data.completedRecords;
        document.getElementById('fp-completed-records-desc').textContent = data.descriptions.completedRecords;
        // Assume familyPlanningCount is available globally or pass as part of API
        var totalRecords = window.familyPlanningCount || (data.familyPlanningCount || 0);
        if (!totalRecords && typeof data.recordsThisMonth !== 'undefined') {
            totalRecords = data.recordsThisMonth + (data.completedRecords ? (data.completedRecords - data.recordsThisMonth) : 0);
        }
        // fallback: try to get from DOM
        if (!totalRecords) {
            var countElem = document.querySelector('.text-success.text-uppercase ~ .h5');
            if (countElem) totalRecords = parseInt(countElem.textContent) || 0;
        }
        var notCompleted = totalRecords > data.completedRecords ? totalRecords - data.completedRecords : 0;
        if (window.fpCompletedRecordsChart) window.fpCompletedRecordsChart.destroy();
        window.fpCompletedRecordsChart = new Chart(document.getElementById('fp-completed-records-chart'), {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Not Completed'],
                datasets: [{
                    data: [data.completedRecords, notCompleted],
                    backgroundColor: ['#1cc88a', '#e74a3b']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { enabled: true },
                    title: { display: true, text: 'Completed vs Not Completed FP Records' }
                }
            }
        });
        document.getElementById('fp-completed-records-chart-desc').textContent = data.descriptions.completedRecords;
    }

    // WRA vs NWRA Pie
    if (window.fpWraVsNwraChart) window.fpWraVsNwraChart.destroy();
    window.fpWraVsNwraChart = new Chart(document.getElementById('fp-wra-vs-nwra'), {
        type: 'pie',
        data: {
            labels: ['WRA (15-49)', 'NWRA'],
            datasets: [{
                data: [data.wraVsNwraFemale.WRA, data.wraVsNwraFemale.NWRA],
                backgroundColor: ['#36a2eb', '#ff6384']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { enabled: true },
                title: { display: true, text: 'WRA vs NWRA (Females)' }
            }
        }
    });
    document.getElementById('fp-wra-vs-nwra-desc').textContent = data.descriptions.wraVsNwraFemale;

    // Modern FP by Age Group Bar
    if (window.fpModernFpAgeChart) window.fpModernFpAgeChart.destroy();
    window.fpModernFpAgeChart = new Chart(document.getElementById('fp-modern-fp-age'), {
        type: 'bar',
        data: {
            labels: Object.keys(data.modernFpByAgeGroup),
            datasets: [{
                label: 'Modern FP Users',
                data: Object.values(data.modernFpByAgeGroup),
                backgroundColor: '#4e73df'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true },
                title: { display: true, text: 'Modern FP Users by Age Group' }
            }
        }
    });
    document.getElementById('fp-modern-fp-age-desc').textContent = data.descriptions.modernFpByAgeGroup;

    // Non-Modern FP by Age Group Bar
    if (window.fpNonModernFpAgeChart) window.fpNonModernFpAgeChart.destroy();
    window.fpNonModernFpAgeChart = new Chart(document.getElementById('fp-nonmodern-fp-age'), {
        type: 'bar',
        data: {
            labels: Object.keys(data.nonModernFpByAgeGroup),
            datasets: [{
                label: 'Non-Modern FP Users',
                data: Object.values(data.nonModernFpByAgeGroup),
                backgroundColor: '#e74a3b'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true },
                title: { display: true, text: 'Non-Modern FP Users by Age Group' }
            }
        }
    });
    document.getElementById('fp-nonmodern-fp-age-desc').textContent = data.descriptions.nonModernFpByAgeGroup;

    // Records per Purok Bar
    if (window.fpRecordsPurokChart) window.fpRecordsPurokChart.destroy();
    window.fpRecordsPurokChart = new Chart(document.getElementById('fp-records-purok'), {
        type: 'bar',
        data: {
            labels: data.recordsPerPurok.map(x => x.purok),
            datasets: [{
                label: 'Records',
                data: data.recordsPerPurok.map(x => x.count),
                backgroundColor: '#1cc88a'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true },
                title: { display: true, text: 'Records per Purok' }
            }
        }
    });
    document.getElementById('fp-records-purok-desc').textContent = data.descriptions.recordsPerPurok;

    // FP Method Pie
    if (window.fpMethodPieChart) window.fpMethodPieChart.destroy();
    window.fpMethodPieChart = new Chart(document.getElementById('fp-method-pie'), {
        type: 'pie',
        data: {
            labels: data.fpMethodPie.map(x => x.fp_method),
            datasets: [{
                data: data.fpMethodPie.map(x => x.count),
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69', '#ff6384', '#36a2eb', '#fd7e14'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { enabled: true },
                title: { display: true, text: 'FP Methods Used' }
            }
        }
    });
    document.getElementById('fp-method-pie-desc').textContent = data.descriptions.fpMethodPie;

    // Intended Method Pie
    if (window.fpIntendedMethodPieChart) window.fpIntendedMethodPieChart.destroy();
    window.fpIntendedMethodPieChart = new Chart(document.getElementById('fp-intended-method-pie'), {
        type: 'pie',
        data: {
            labels: data.intendedMethodPie.map(x => x.intended_method || 'None'),
            datasets: [{
                data: data.intendedMethodPie.map(x => x.count),
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69', '#ff6384', '#36a2eb', '#fd7e14'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { enabled: true },
                title: { display: true, text: 'Intended FP Methods' }
            }
        }
    });
    document.getElementById('fp-intended-method-pie-desc').textContent = data.descriptions.intendedMethodPie;
}
