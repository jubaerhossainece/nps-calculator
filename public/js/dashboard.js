// chart for user summery 
function getUserData(type) {
  let chartStatus = Chart.getChart('user-summery-canvas') // <canvas> id
  if (chartStatus != undefined) {
    chartStatus.destroy()
  }

  $.ajax({
    type: 'GET',
    url: 'dashboard/user/' + type + '/chart',
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    success: function (response) {
      generateUserChart(response, type)
    },
  })
}

function generateUserChart(response, chartType) {
  const ctx = document.getElementById('user-summary-canvas').getContext('2d')

  const gradient = ctx.createLinearGradient(0, 0, 0, 400)
  gradient.addColorStop(0, 'rgba(29, 170, 226, 0.5)')

  const userChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: response.label,
      datasets: [
        {
          data: response.user,
          backgroundColor: gradient,
          pointColor: '#fff',
          borderWidth: 1,
          tension: 0.3,
          fill: 'origin',
        },
      ],
    },
    options: {
      responsive: true,
      scales: {
        x: {
          title: {
            display: true,
            text: chartType,
          },
          grid: {
            display: false
          }
        },
        y: {
          title: {
            display: false,
            text: 'user',
          },
          suggestedMin: 0,
        },
      },
      plugins: {
        legend: {
          display: false,
        },
      },
    },
  })
}

//feedback summery chart
function getProjectFeedback(type = 'month') {
  let chartStatus = Chart.getChart('nps-summary-canvas') // <canvas> id
  if (chartStatus != undefined) {
    chartStatus.destroy()
  }
  var projectId = $('#projectId').val()
  var dateRangeInput = $('#date-range').val()
  var dates = dateRangeInput.split(' - ')

  var startDate = dates[0]
  var endDate = dates[1]

  $.ajax({
    type: 'GET',
    url: 'dashboard/project-feedback/chart',
    data: {
      project_id: projectId,
      from_date: startDate,
      to_date: endDate,
    },
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    success: function (response) {
      generateNpsCollectChart(response, type)
    },
  })
}

function generateNpsCollectChart(response, chartType) {
  var myChart = document.getElementById('nps-summary-canvas').getContext('2d');
        var label = response.label;
        var data =response.data;
        var score =response.score;
        var myNPSChart = new Chart(myChart, {
            type: 'pie',
            data: {
                labels: label,
                datasets: [{
                    label: '% of Votes',
                    data: data,
                    backgroundColor: [
                        '#b91d47',
                        '#00aba9',
                        '#2b5797',
                        '#e8c3b9',
                    ],

                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'top'
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            var meta = dataset._meta[Object.keys(dataset._meta)[0]];
                            var total = meta.total;
                            var currentValue = dataset.data[tooltipItem.index];
                            var percentage = parseFloat((currentValue / total * 100).toFixed(1));
                            return currentValue + ' (' + percentage + '%)';
                        },
                        title: function(tooltipItem, data) {
                            return data.labels[tooltipItem[0].index];
                        }
                    }
                },
                plugins: {
                  title: {
                      display: true,
                      text: 'NPS Score : '+score,
                  }
              }
            }
        });
}


// NPS Line chart
function getProjectData(type = 'date') {
  let chartStatus = Chart.getChart('project-summary-canvas') // <canvas> id
  if (chartStatus != undefined) {
    chartStatus.destroy()
  }

  var projectId = $('#projectIdNps').val()
  var dateRangeInput = $('#date-range-ano').val()
  var dates = dateRangeInput.split(' - ')

  var startDate = dates[0]
  var endDate = dates[1]

  $.ajax({
    type: 'GET',
    url: '/dashboard/nps-score/chart',
    data: {
      project_id: projectId,
      from_date: startDate,
      to_date: endDate,
    },
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    success: function (response) {
      generateProjectChart(response, type)
    },
  })
}

function generateProjectChart(response, chartType) {
  const ctx = document.getElementById('project-summary-canvas').getContext('2d')

  const gradient = ctx.createLinearGradient(0, 0, 0, 400)
  gradient.addColorStop(0, 'rgba(29, 170, 226, 0.5)')
  const npsChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: response.label,
      datasets: [
        {
          data: response.data,
          backgroundColor: gradient,
          pointColor: '#fff',
          borderWidth: 1,
          tension: 0.3,
          fill: 'origin',
        },
      ],
    },
    options: {
      responsive: true,
      scales: {
        x: {
          title: {
            display: true,
            text: "Date",
          },
        },
        y: {
          title: {
            display: true,
            text: 'NPS Score',
          },
          suggestedMin: 0,
        },
      },
      plugins: {
        legend: {
          display: false,
        },
      },
    },
  })
}


$(document).ready(function () {
  getUserData('year')
  // getData()
  getProjectFeedback()
  getProjectData()
})

function changeStatus(id) {
  $.ajax({
    type: 'POST',
    url: '/users/' + id + '/status-change',
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    success: function (data) {
      let type = $('.nav-link.active').attr('id')

      getData()
    },
  })
}
