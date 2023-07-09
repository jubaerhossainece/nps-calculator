// chart for user summery

function getUserData() {
  let chartStatus = Chart.getChart('user-summary-canvas') // <canvas> id
  if (chartStatus != undefined) {
    chartStatus.destroy()
  }

  // change chart according to date range
  let dates = $('#user-report-range').next('input').val().split(' : ');
  var startDate = dates[0];
  var endDate = dates[1];
  
  $.ajax({
    type: 'GET',
    url: 'dashboard/user/chart',
    data: {
      startDate: startDate,
      endDate: endDate,
    },
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    success: function (response) {
      console.log(response);
      generateUserChart(response)
    },
  })
}

function generateUserChart(response) {
  const ctx = document.getElementById('user-summary-canvas').getContext('2d')

  const gradient = ctx.createLinearGradient(0, 0, 0, 400)
  gradient.addColorStop(0, 'rgba(37, 88, 220, 1)');
  gradient.addColorStop(1, 'rgba(0, 180, 239, 1)');
  let chartType = response.data.type;
  
  const userChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: response.data.label,
      datasets: [
        {
          data: response.data.data,
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
function getProjectFeedback() {
  let chartStatus = Chart.getChart('nps-summary-canvas') // <canvas> id
  if (chartStatus != undefined) {
    chartStatus.destroy()
  }
  var projectId = $('#projectId').val();
  var dateRangeInput = $('#nps-report-range').val();
  var dates = dateRangeInput.split(' - ');

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
      generateNpsCollectChart(response)
    },
  })
}

function generateNpsCollectChart(response) {
  var ctx = document.getElementById('nps-summary-canvas').getContext('2d');
  
  const gradient = ctx.createLinearGradient(0, 0, 0, 400)
  gradient.addColorStop(0, 'rgba(29, 170, 226, 0.5)')
  let chartType = response.type;

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


// NPS Line chart
function getProjectData() {
  let chartStatus = Chart.getChart('project-summary-canvas') // <canvas> id
  if (chartStatus != undefined) {
    chartStatus.destroy()
  }

  var projectId = $('#projectIdNps').val()
  var dateRangeInput = $('#project-report-range').val()
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
      generateProjectChart(response)
    },
  })
}

function generateProjectChart(response) {
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

