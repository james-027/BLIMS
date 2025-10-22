// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Arial', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
//Chart.defaults.global.defaultFontColor = '#858796';
Chart.defaults.global.defaultFontColor = '#6b6d7b';
Chart.defaults.global.defaultFontSize = 15;
Chart.defaults.global.defaultFontWeight = 'bolder';


$(document).ready(function () {
  var base_url = $('#base_url').val();
  var yearFrom = $('#dashboard-yearFrom').val();
  var yearTo = $('#dashboard-yearTo').val();
  var expThColor = $('#expThColor').val();
  var expFontColor = $('#expFontColor').val();
  $.ajax({
    url: base_url + 'admin/performanceDashboard',
    data: {yearFrom:yearFrom, yearTo:yearTo},
    type: "POST",
    dataType:"json",
    success: function (data) {
      for(i = 0; i < data.length; i++) {
        if(data[i].totalities !== undefined){
          var total_emp_count = data[i].total_emp_count;
          var total_probi_count = data[i].total_probi_count;
          var total_reg_count = data[i].total_reg_count;
          var total_endo_count = data[i].total_endo_count;
          var probi_perc = data[i].probi_perc;
          var reg_perc = data[i].reg_perc;
          var endo_perc = data[i].endo_perc;

        }
        if(data[i].probi_stats !== undefined){
          var probi_stats = data[i].probi_stats;
          var probi_pending_perc = data[i].probi_pending_perc;
          var probi_posted_perc = data[i].probi_posted_perc;
          var probi_in_progress_perc = data[i].probi_in_progress_perc;
          var probi_for_reco_perc = data[i].probi_for_reco_perc;
          var total_probi_pending_count = data[i].total_probi_pending_count;
          var total_probi_posted_count = data[i].total_probi_posted_count;
          var total_probi_in_progress_count = data[i].total_probi_in_progress_count;
          var total_probi_for_reco_count = data[i].total_probi_for_reco_count;
        }

        if(data[i].eval_stats !== undefined){
          var posted_eval_perc = data[i].posted_eval_perc;
          var total_posted_eval_count = data[i].total_posted_eval_count;
          var total_pending_eval_count = data[i].total_pending_eval_count;
          var pending_eval_perc = data[i].pending_eval_perc;
        }

        if(data[i].table_bc !== undefined){
          var table_bc_probi = data[i].table_bc_probi;
        }
        
        if(data[i].table_for_reco !== undefined){
          var table_for_reco_details = data[i].table_for_reco_details;
        }
        
        if(data[i].table_for_reco_end_of_probi !== undefined){
          var table_for_reco_end_of_probi_details = data[i].table_for_reco_end_of_probi_details;
        }
      }

      endo_perc_disp = ' ('+ endo_perc+ '%)';
      if(endo_perc == 0) { endo_perc_disp = '';}
      probi_perc_disp = ' ('+ probi_perc+ '%)';
      if(probi_perc == 0) { probi_perc_disp = '';}
      reg_perc_disp = ' ('+ reg_perc+ '%)';
      if(reg_perc == 0) { reg_perc_disp = '';}

      $('#total_emp_count').html(number_format(total_emp_count, 0, '.', ','));
      $('#total_probi_count').html(number_format(total_probi_count, 0, '.', ','));
      $('#total_reg_count').html(number_format(total_reg_count, 0, '.', ','));
      $('#total_endo_count').html(number_format(total_endo_count, 0, '.', ','));
      
      $('#probi_perc_disp').html(probi_perc_disp);
      $('#reg_perc_disp').html(reg_perc_disp);
      $('#endo_perc_disp').html(endo_perc_disp);
      

      Circles.create({
          id:'circles-probi-pending',
          radius:45,
          value:probi_pending_perc,
          maxValue:100,
          width:7,
          text: total_probi_pending_count,
          colors:['#f1f1f1', '#FF9E27'],
          duration:400,
          wrpClass:'circles-wrp',
          textClass:'circles-text',
          styleWrapper:true,
          styleText:true
      });

      Circles.create({
          id:'circles-probi-posted',
          radius:45,
          value:probi_posted_perc,
          maxValue:100,
          width:7,
          text: total_probi_posted_count,
          colors:['#f1f1f1', '#2BB930'],
          duration:400,
          wrpClass:'circles-wrp',
          textClass:'circles-text',
          styleWrapper:true,
          styleText:true
      });

      Circles.create({
          id:'circles-probi-in-progress',
          radius:45,
          value:probi_in_progress_perc,
          maxValue:100,
          width:7,
          text: total_probi_in_progress_count,
          colors:['#f1f1f1', '#4dc3ff'],
          duration:400,
          wrpClass:'circles-wrp',
          textClass:'circles-text',
          styleWrapper:true,
          styleText:true
      });
      
      Circles.create({
          id:'circles-probi-for-reco',
          radius:45,
          value:probi_for_reco_perc,
          maxValue:100,
          width:7,
          text: total_probi_for_reco_count,
          colors:['#f1f1f1', '#b31aff'],
          duration:400,
          wrpClass:'circles-wrp',
          textClass:'circles-text',
          styleWrapper:true,
          styleText:true
      });

      Circles.create({
        id:           'eval-progress-complete',
        radius:       70,
        value:        posted_eval_perc,
        maxValue:     100,
        width:        7,
        text:         function(value){return value + '%';},
        colors:       ['#36a3f7', '#fff'],
        duration:     400,
        wrpClass:     'circles-wrp',
        textClass:    'circles-text',
        styleWrapper: true,
        styleText:    true
      })
      
      Circles.create({
        id:           'pending-eval-count',
        radius:       30,
        value:        pending_eval_perc,
        maxValue:     100,
        width:        3,
        text:         total_pending_eval_count,
        colors:       ['#36a3f7', '#fff'],
        duration:     400,
        wrpClass:     'circles-wrp',
        textClass:    'circles-text',
        styleWrapper: true,
        styleText:    true
      })
      Circles.create({
        id:           'posted-eval-count',
        radius:       30,
        value:        posted_eval_perc,
        maxValue:     100,
        width:        3,
        text:         total_posted_eval_count,
        colors:       ['#36a3f7', '#fff'],
        duration:     400,
        wrpClass:     'circles-wrp',
        textClass:    'circles-text',
        styleWrapper: true,
        styleText:    true
      })

      var today = new Date ();
      var date = (today.getMonth ()+1)+'/'+today.getDate ()+'/'+today.getFullYear ()+' '+today.getHours () +':'+ today.getMinutes()+':'+today.getSeconds();

      $("#tbl-dashboard-probi-bc-details > tbody").empty();
      $("#tbl-dashboard-probi-bc-details > tbody").append(table_bc_probi);
      tblProbi = $('#tbl-dashboard-probi-bc-details').DataTable({
        "pagingType": "full",
        "language": {
            "emptyTable":     "No data available",
            "lengthMenu":     "Show _MENU_ entries",
            "info":           "Displaying _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty":      "Displaying 0 to 0 of 0 entries",
            'search': '<i class="fa fa-search" aria-hidden="true"></i>',
            "paginate": {
                "first":      '<i class="fas fa-fast-backward"></i>',
                "last":       '<i class="fas fa-fast-forward"></i>',
                "next":       '<i class="fas fa-step-forward"></i>',
                "previous":   '<i class="fas fa-step-backward"></i>'
            },
        },
        "bInfo" : true,
        "order": [],
        "responsive": true,
        //scrollX:        true,
        //fixedColumns: {
            //leftColumns: 1
        //},
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        buttons: [
            {
                extend: 'excel',
                messageTop: 'Run Date : '+date,
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    //$('row:first c', sheet).attr( 's', '42' );
                    //$('row c[r*="3"]', sheet).attr('s', expDtColor);
                },
                autoFilter: true
            }
        ]
      });
      
      
      $("#tbl-dashboard-probi-for-reco-details > tbody").empty();
      $("#tbl-dashboard-probi-for-reco-details > tbody").append(table_for_reco_details);
      tblProbiForReco = $('#tbl-dashboard-probi-for-reco-details').DataTable({
        "pagingType": "full",
        "language": {
            "emptyTable":     "No data available",
            "lengthMenu":     "Show _MENU_ entries",
            "info":           "Displaying _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty":      "Displaying 0 to 0 of 0 entries",
            'search': '<i class="fa fa-search" aria-hidden="true"></i>',
            "paginate": {
                "first":      '<i class="fas fa-fast-backward"></i>',
                "last":       '<i class="fas fa-fast-forward"></i>',
                "next":       '<i class="fas fa-step-forward"></i>',
                "previous":   '<i class="fas fa-step-backward"></i>'
            },
        },
        "bInfo" : true,
        "order": [],
        "responsive": true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        buttons: [
            {
                extend: 'excel',
                messageTop: 'Run Date : '+date,
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    //$('row:first c', sheet).attr( 's', '42' );
                    //$('row c[r*="3"]', sheet).attr('s', expDtColor);
                },
                autoFilter: true
            }
        ]
      });
      
      
      $("#tbl-dashboard-probi-for-reco-end-of-probi-details > tbody").empty();
      $("#tbl-dashboard-probi-for-reco-end-of-probi-details > tbody").append(table_for_reco_end_of_probi_details);

      tblProbiForRecoEndo = $('#tbl-dashboard-probi-for-reco-end-of-probi-details').DataTable({
        "pagingType": "full",
        "language": {
            "emptyTable":     "No data available",
            "lengthMenu":     "Show _MENU_ entries",
            "info":           "Displaying _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty":      "Displaying 0 to 0 of 0 entries",
            'search': '<i class="fa fa-search" aria-hidden="true"></i>',
            "paginate": {
                "first":      '<i class="fas fa-fast-backward"></i>',
                "last":       '<i class="fas fa-fast-forward"></i>',
                "next":       '<i class="fas fa-step-forward"></i>',
                "previous":   '<i class="fas fa-step-backward"></i>'
            },
        },
        "bInfo" : true,
        "order": [],
        "responsive": true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        buttons: [
            {
                extend: 'excel',
                messageTop: 'Run Date : '+date,
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    //$('row:first c', sheet).attr( 's', '42' );
                    //$('row c[r*="3"]', sheet).attr('s', expDtColor);
                },
                autoFilter: true
            }
        ]
      });
      
    },
    error: function (data) {
      console.log(data);
    }
  });
});
      
