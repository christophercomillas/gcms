    <script src="../assets/js/jquery-1.9.1.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/bootstrap-datepicker.min.js"></script>
    <script src="../assets/js/moment.min.js"></script>
    <script src="../assets/js/daterangepicker.js"></script>
    <script src="../assets/js/bootstrap-dialog.js"></script>
    <script src="../assets/js/jquery.inputmask.bundle.min.js"></script>
    <script src="../assets/js/jquery.dataTables.js"></script>
    <script src="../assets/js/fileinput.js"></script>
    <script src="../assets/js/slate.js"></script>
    <script src="../assets/js/lightgallery/picturefill.min.js"></script>
    <script src="../assets/js/lightgallery/lightgallery.js"></script>
    <script src="../assets/js/lightgallery/lg-fullscreen.js"></script>
    <script src="../assets/js/lightgallery/lg-thumbnail.js"></script>
    <script src="../assets/js/lightgallery/lg-zoom.js"></script>
    <script src="../assets/js/crossroads/signals.js"></script>
    <script src="../assets/js/crossroads/crossroads.min.js"></script> 
    <script type="text/javascript" src="../assets/js/logout.js"></script>
      <script>
        $('#lightgallery, #lightgallery1').lightGallery();
        $('li.about').click(function(){
        BootstrapDialog.show({
              title: 'About Us',
              cssClass: 'aboutus',
              closable: true,
              closeByBackdrop: false,
              closeByKeyboard: true,
              message: function(dialog) {
                  var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
                  var pageToLoad = dialog.getData('pageToLoad');
                  setTimeout(function(){
                  $message.load(pageToLoad);
                  },1000);
                  return $message;
              },
              data: {
                  'pageToLoad': '../dialogs/aboutus.php'
              },
            onshow: function(dialog) {
                // dialog.getButton('button-c').disable();
            },
            onshown: function(dialogRef){

            },
            buttons:[ {
              icon: 'glyphicon glyphicon-remove-sign',
                label: 'Close',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });
          return false;
        });
      </script> 