
document.addEventListener('DOMContentLoaded', function () {
    fetch('/api/analytics.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            if (data.length === 0) {
                alert('No analytics data available.</td></tr>');
                return;
            }

            let totalViews = [];
            let totalRevenue = [];
            let months1 = [];
            let months2 = [];
            console.log(unixtodate(1751964077));
            data.forEach(item => {
                let temp1 = item.raw_view;
                totalViews.push(view1);
                months1.push(unixtodate(item.date));
            });
            var view1 = {
                type: 'line',
                label: "",
                data: [1, 2, 3, 4],
                backgroundColor: 'rgba(255, 255, 255, 0.2)',
                borderColor: 'transparent',
                borderWidth: 1
            };

            // Render Views Chart
            const viewsCtx = document.getElementById('viewsChart').getContext('2d');
            new Chart(viewsCtx, {
                data: {
                    labels: months,
                    datasets: totalViews
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                        labels: {
                            fontColor: '#ddd',
                            boxWidth: 40
                        }
                    },
                    tooltips: {
                        displayColors: false
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true,
                                fontColor: '#ddd'
                            },
                            gridLines: {
                                display: true,
                                color: "rgba(221, 221, 221, 0.08)"
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                fontColor: '#ddd'
                            },
                            gridLines: {
                                display: true,
                                color: "rgba(221, 221, 221, 0.08)"
                            },
                        }]
                    }

                }
            });

            // Render Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [{
                        label: 'Total Revenue',
                        data: totalRevenue,
                        backgroundColor: 'rgba(255, 255, 255, 0.5)',
                        borderColor: 'transparent',
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                        labels: {
                            fontColor: '#ddd',
                            boxWidth: 40
                        }
                    },
                    tooltips: {
                        displayColors: false
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true,
                                fontColor: '#ddd'
                            },
                            gridLines: {
                                display: true,
                                color: "rgba(221, 221, 221, 0.08)"
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                fontColor: '#ddd'
                            },
                            gridLines: {
                                display: true,
                                color: "rgba(221, 221, 221, 0.08)"
                            },
                        }]
                    }

                }
            });

        })
        .catch(error => {
            console.error('Error fetching analytics data:', error);
            //document.getElementById('analyticsTableBody').innerHTML = `<tr><td colspan="5" class="text-center">Failed to load analytics data.</td></tr>`;
            alert('[Server] Failed to load analytics data.')
        });
});