//Date Range picker related
$(document).ready(function () {
  $('#date-range').daterangepicker({
    opens: 'left',
    autoUpdateInput: false,
    locale: {
      format: 'YYYY-MM-DD',
    },
  })

  $('#date-range').on('apply.daterangepicker', function (ev, picker) {
    $(this).val(
      picker.startDate.format('YYYY-MM-DD') +
        ' - ' +
        picker.endDate.format('YYYY-MM-DD'),
    )
    getProjectFeedback()
  })

  $('#date-range').on('cancel.daterangepicker', function (ev, picker) {
    $(this).val('')
  })
})


$(document).ready(function () {
  $('#date-range-ano').daterangepicker({
    singleDatePicker: true,
    opens: 'left',
    autoUpdateInput: false,
    locale: {
      format: 'YYYY-MM-DD',
    },
  })

  $('#date-range-ano').on('apply.daterangepicker', function (ev, picker) {
    $(this).val(
      picker.startDate.format('YYYY-MM-DD') +
        ' - ' +
        picker.endDate.format('YYYY-MM-DD'),
    )
    getNpsData()
  })

  $('#date-range-ano').on('cancel.daterangepicker', function (ev, picker) {
    $(this).val('')
  })
})
