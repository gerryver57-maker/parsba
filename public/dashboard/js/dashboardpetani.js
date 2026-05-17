document.addEventListener('livewire:init', () => {
    // Simpan referensi chart secara global
    window.growthChart = null;

    const initChart = () => {
        const canvas = document.getElementById('growthChart');
        
        // Hancurkan chart lama jika ada
        if (window.growthChart) {
            window.growthChart.destroy();
            window.growthChart = null;
        }

        if (canvas) {
            const ctx = canvas.getContext('2d');
            window.growthChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['1 Okt', '8 Okt', '15 Okt', '22 Okt', '29 Okt', '5 Nov', '12 Nov'],
                    datasets: [{
                        label: 'Tinggi Tanaman (cm)',
                        data: [5, 12, 35, 62, 78, 85, 88],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.3,
                        fill: true
                    }, {
                        label: 'Standar Ideal',
                        data: [5, 15, 40, 65, 80, 90, 95],
                        borderColor: '#6c757d',
                        borderDash: [5, 5],
                        backgroundColor: 'transparent',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Tinggi (cm)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Tanggal Pengukuran'
                            }
                        }
                    }
                }
            });
        }
    };

    // Inisialisasi pertama
    initChart();
    
    // Handle Livewire component update
    Livewire.on('chart-updated', () => {
        initChart();
    });
});