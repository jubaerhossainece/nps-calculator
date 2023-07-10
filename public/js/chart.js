// chart generator function
function generateChart(response, id, color, y_title) {

  let chartStatus = Chart.getChart(id) // <canvas> id
  if (chartStatus != undefined) {
    chartStatus.destroy()
  }

  const ctx = document.getElementById(id).getContext('2d')

  const gradient = ctx.createLinearGradient(0, 0, 0, 400)
  gradient.addColorStop(0, color[0]);
  gradient.addColorStop(1, color[1]);
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
            font: {
              size: 18,
              lineHeight: 1.2
            },
          },
          grid: {
            display: false
          }
        },
        y: {
          title: {
            display: true,
            text: y_title,
            font: {
              size: 18,
              lineHeight: 1.2
            },
          },
          ticks: {
            precision: 0, // Enforce integer values
            beginAtZero: true // Start y-axis at zero
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

// chart for user summery
function getUserData() {
  // get date range
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
      let color = ['rgba(37, 88, 220, 1)', 'rgba(0, 180, 239, 1)'];
      generateChart(response, "user-summary-canvas", color, 'Number if users');
    },
  })
}


//feedback summery chart
function getProjectFeedback() {
   // get date range
   let dates = $('#nps-report-range').next('input').val().split(' : ');
   let startDate = dates[0];
   let endDate = dates[1];
   
  var user_id = $('#user-nps-filter').val();
  var project_id = $('#project-nps-filter').val();

  console.log(user_id+" "+project_id);

  $.ajax({
    type: 'GET',
    url: 'dashboard/project-feedback/chart',
    data: {
      user_id: user_id,
      project_id: project_id,
      startDate: startDate,
      endDate: endDate,
    },
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    success: function (response) {
      console.log(response); 
      let color = ['rgba(247, 148, 32, 1)', "rgba(251, 167, 70, 0.56)"];
      generateChart(response, "nps-summary-canvas", color, 'No of feedbacks');
    },
  })
}


// NPS Line chart

function getProjectData() {
  // get date range
  let dates = $('#project-report-range').next('input').val().split(' : ');
  var startDate = dates[0];
  var endDate = dates[1];

  $.ajax({
    type: 'GET',
    url: '/dashboard/project/chart',
    data: {
      startDate: startDate,
      endDate: endDate,
    },
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    success: function (response) {
      console.log(response);
      let color = ['rgba(221, 58, 107, 1)', 'rgba(221, 58, 107, 0.56)'];
      generateChart(response, "project-summary-canvas", color, 'No of projects');
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





