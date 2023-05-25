// audience summery chart
function getAudienceData(type) {
  let chartStatus = Chart.getChart('audience-summery') // <canvas> id
  if (chartStatus != undefined) {
    chartStatus.destroy()
  }

  $.ajax({
    type: 'GET',
    url: 'dashboard/audience/' + type + '/chart',
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    success: function (response) {
      generateChart(response, type)
    },
  })
}

function generateChart(response, chartType) {
  const ctx = document.getElementById('audience-summery').getContext('2d')

  const gradient = ctx.createLinearGradient(0, 0, 0, 400)
  gradient.addColorStop(0, 'rgba(29, 170, 226, 0.5)')

  const audienceChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: response.label,
      datasets: [
        {
          data: response.audience,
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
        },
        y: {
          title: {
            display: true,
            text: 'Audience',
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

//project summery chart
function getProjectFeedback(type = 'month') {
  let chartStatus = Chart.getChart('project-feedback') // <canvas> id
  if (chartStatus != undefined) {
    chartStatus.destroy()
  }
  var projectId = $('#projectId').val()
  var dateRangeInput = $('#date-range').val()
  var dates = dateRangeInput.split(' - ')

  var startDate = dates[0]
  var endDate = dates[1]

  console.log(projectId + ' ' + startDate + ' ' + endDate)

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
      generateChartForProjectFeedback(response, type)
    },
  })
}

function generateChartForProjectFeedback(response, chartType) {
  const ctx = document.getElementById('project-feedback').getContext('2d')

  const gradient = ctx.createLinearGradient(0, 0, 0, 400)
  gradient.addColorStop(0, 'rgba(29, 170, 226, 0.5)')

  const audienceChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: response.label,
      datasets: [
        {
          data: response.audience,
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
        },
        y: {
          title: {
            display: true,
            text: 'NPS',
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

// recent audience datatables
function getData() {
  $('#audience-table').DataTable({
    bPaginate: false,
    bFilter: false,
    bInfo: false,
    processing: true,
    serverSide: true,
    autoWidth: true,
    destroy: true,
    // order: [4, "desc"],

    ajax: {
      url: '/dashboard/recent-audience',
    },
    columns: [
      {
        data: 'DT_RowIndex',
        name: 'DT_RowIndex',
        title: 'Serial',
        searchable: false,
        orderable: false,
      },
      { data: 'name', title: 'Name', orderable: false },
      { data: 'email', title: 'Email', orderable: false },
      { data: 'projects', title: 'Total Project' },
      { data: 'feedbacks', title: 'Total NPS Collect' },

      { data: 'status', title: 'Status', orderable: false },
      { data: 'action', title: 'Action', orderable: false },
    ],
  })
}

$(document).ready(function () {
  getAudienceData('year')
  getData()
  getProjectFeedback()
})

function changeStatus(id) {
  $.ajax({
    type: 'POST',
    url: '/audiences/' + id + '/status-change',
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    success: function (data) {
      let type = $('.nav-link.active').attr('id')

      getData()
    },
  })
}
