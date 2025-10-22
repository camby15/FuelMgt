// Debug function to check if Chart.js is loaded
function checkChartDependencies() {
    console.log('Checking dependencies...');
    console.log('Chart.js available:', typeof Chart !== 'undefined');
    console.log('jQuery available:', typeof $ !== 'undefined');
    
    // Check if canvas element exists
    const canvas = document.getElementById('inventoryChart');
    console.log('Canvas element:', canvas);
    if (canvas) {
        console.log('Canvas dimensions:', canvas.width, 'x', canvas.height);
    }
}

// Initialize chart with debug information
function initChartWithDebug(period = 'month') {
    console.log('Initializing chart with period:', period);
    
    const ctx = document.getElementById('inventoryChart');
    if (!ctx) {
        console.error('Canvas element not found!');
        return null;
    }
    
    // Make sure we have a 2d context
    const context = ctx.getContext('2d');
    if (!context) {
        console.error('Could not get 2D context!');
        return null;
    }
    
    console.log('Chart context created successfully');
    
    // Rest of your chart initialization code...
    // [Previous chart initialization code goes here]
    
    return new Chart(context, {
        // Your chart configuration
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Sample Data',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Run when document is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded');
    checkChartDependencies();
    
    // Try to initialize a simple chart
    try {
        const chart = initChartWithDebug();
        if (chart) {
            console.log('Chart initialized successfully!', chart);
        } else {
            console.error('Failed to initialize chart');
        }
    } catch (error) {
        console.error('Error initializing chart:', error);
    }
});
