// immunization-dashboard-charts.js
// Handles fetching and rendering Immunization statistics charts for the home dashboard

document.addEventListener('DOMContentLoaded', function () {
    fetch('/api/immunization/stats')
        .then(response => response.json())
        .then(data => renderImmunizationCharts(data));
});

// Chart.js instances
// Always use window-scoped variables to avoid redeclaration and ensure destroy works
if (typeof window.immChildrenPerPurokChart === 'undefined') window.immChildrenPerPurokChart = null;
if (typeof window.immVaccStatusPieChart === 'undefined') window.immVaccStatusPieChart = null;
if (typeof window.immVaccineBarChart === 'undefined') window.immVaccineBarChart = null;

function renderImmunizationCharts(data) {
    // Records this month
    if (document.getElementById('imm-records-this-month')) {
        document.getElementById('imm-records-this-month').textContent = data.recordsThisMonth;
        document.getElementById('imm-records-this-month-desc').textContent = data.descriptions.recordsThisMonth;
    }

    // Completed Children
    if (document.getElementById('imm-completed-children')) {
        document.getElementById('imm-completed-children').textContent = data.completedChildren;
        document.getElementById('imm-completed-children-desc').textContent = data.descriptions.completedChildren;
    }

    // Pie Chart: Children per Purok
    if (document.getElementById('imm-children-per-purok-chart')) {
        const purokLabels = data.childrenPerPurok.map(item => item.purok);
        const purokCounts = data.childrenPerPurok.map(item => item.count);
        if (window.immChildrenPerPurokChart) window.immChildrenPerPurokChart.destroy();
        window.immChildrenPerPurokChart = new Chart(document.getElementById('imm-children-per-purok-chart'), {
            type: 'pie',
            data: {
                labels: purokLabels,
                datasets: [{
                    data: purokCounts,
                    backgroundColor: [
                        '#36a2eb', '#ff6384', '#ffcd56', '#4bc0c0', '#9966ff', '#c9cbcf', '#1cc88a', '#e74a3b', '#f6c23e'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { enabled: true },
                    title: { display: true, text: 'Children Records per Purok' }
                }
            }
        });
        document.getElementById('imm-children-per-purok-desc').textContent = data.descriptions.childrenPerPurok;
    }

    // Pie Chart: Vaccination Status
    if (document.getElementById('imm-vacc-status-pie-chart')) {
        const statusLabels = Object.keys(data.vaccStatusPie);
        const statusCounts = Object.values(data.vaccStatusPie);
        if (window.immVaccStatusPieChart) window.immVaccStatusPieChart.destroy();
        window.immVaccStatusPieChart = new Chart(document.getElementById('imm-vacc-status-pie-chart'), {
            type: 'pie',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusCounts,
                    backgroundColor: ['#e74a3b', '#f6c23e', '#1cc88a']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { enabled: true },
                    title: { display: true, text: 'Children by Vaccination Status' }
                }
            }
        });
        document.getElementById('imm-vacc-status-pie-desc').textContent = data.descriptions.vaccStatusPie;
    }

    // Bar Graph: Vaccines Administered
    if (document.getElementById('imm-vaccine-bar-chart')) {
        const vaccineLabels = data.vaccineCounts.map(item => item.vaccine_type);
        const vaccineCounts = data.vaccineCounts.map(item => item.count);
        if (window.immVaccineBarChart) window.immVaccineBarChart.destroy();
        window.immVaccineBarChart = new Chart(document.getElementById('imm-vaccine-bar-chart'), {
            type: 'bar',
            data: {
                labels: vaccineLabels,
                datasets: [{
                    label: 'Count',
                    data: vaccineCounts,
                    backgroundColor: '#36a2eb'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true },
                    title: { display: true, text: 'Vaccines Administered to Children' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
        document.getElementById('imm-vaccine-bar-desc').textContent = data.descriptions.vaccineCounts;
    }
}

