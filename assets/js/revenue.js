$(function () {
	"use strict";

	// chart 1
	var ctx = document.getElementById('chart1').getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
			datasets: [{
				label: 'Recent Revenue',
				data: [0,0,0,0,0,0,0,0,0,0,0,0],
				backgroundColor: "rgba(255, 255, 255, 0.25)",
				borderColor: "transparent",
				pointRadius: "0",
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
});