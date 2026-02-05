function printlaporan(url){
  // MyWindow=window.open('/dashboard/logbook/print','MyWindow','width=800,height=600'); 
  // return false;
  if(url=='transaction'){
    uid = $('#pui').val();
    url = url+'?user_id='+uid;
  }
  window.open('/print/'+url,'POPUP WINDOW TITLE HERE','width=800,height=800').print()
}

function dataexport(method){
  if(method=='copy'){
    $('.buttons-copy').click()
    Toast.fire({icon: 'success',title: 'Data berhasil dicopy'})
  }
  if(method=='csv'){
    $('.buttons-csv').click()
  }
  if(method=='excel'){
    $('.buttons-excel').click()
  }
  if(method=='pdf'){
    $('.buttons-pdf').click()
  }
  if(method=='print'){
    $('.buttons-print').click()
  }
}