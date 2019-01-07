$(document).ready(function(){
    $('#avail-gc').click(function(){
        var stid = $(this).attr('stid');
        BootstrapDialog.show({
            title: '',
            message: $('<div></div>').load('../dialogs/view-avail-gc.php?stid='+stid),
            cssClass: 'modal-allocated-gc',
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            onshow: function(dialog) {
                // dialog.getButton('button-c').disable();
            },
            onshown: function(dialogRef){
                    $('#allocated-gc').dataTable({
                        "pagingType": "full_numbers",
                        "ordering": false,
                        "processing": true,
                        "iDisplayLength": 5
                    });

                    $("#allocated-gc_length").css("display", "none");
            },
            buttons:[ {
                icon: 'glyphicon glyphicon-remove-sign',
                label: 'Close',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });
    });

    $('#sold-gc').click(function(){
        var stid = $(this).attr('stid');
        BootstrapDialog.show({
            title: '',
            message: $('<div></div>').load('../dialogs/view-sold-gc.php?stid='+stid),
            cssClass: 'modal-allocated-gc',
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            onshow: function(dialog) {
                // dialog.getButton('button-c').disable();
            },
            onshown: function(dialogRef){
                    $('#allocated-gc').dataTable({
                        "pagingType": "full_numbers",
                        "ordering": false,
                        "processing": true,
                        "iDisplayLength": 5
                    });

                    $("#allocated-gc_length").css("display", "none");
            },
            buttons:[ {
                icon: 'glyphicon glyphicon-remove-sign',
                label: 'Close',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });
    });

    $('#alloc-gc').click(function(){
        var stid = $(this).attr('stid');
        BootstrapDialog.show({
            title: 'Allocated GC',
            message: $('<div></div>').load('../dialogs/view-allocated-gc.php?id='+stid),
            cssClass: 'modal-allocated-gc',
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            onshow: function(dialog) {
                // dialog.getButton('button-c').disable();
            },
            onshown: function(dialogRef){
                    $('#allocated-gc').dataTable({
                        "pagingType": "full_numbers",
                        "ordering": false,
                        "processing": true,
                        "iDisplayLength": 5
                    });

                    $("#allocated-gc_length").css("display", "none");
            },
            buttons:[ {
                icon: 'glyphicon glyphicon-remove-sign',
                label: 'Close',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });
    });

    $('#rel-gc').click(function(){
        var stid = $(this).attr('stid');
        BootstrapDialog.show({
            title: 'Released GC',
            message: $('<div></div>').load('../dialogs/view-released-gc.php?stid='+stid),
            cssClass: 'modal-allocated-gc',
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            onshow: function(dialog) {
                // dialog.getButton('button-c').disable();
            },
            onshown: function(dialogRef){
                    $('#allocated-gc').dataTable({
                        "pagingType": "full_numbers",
                        "ordering": false,
                        "processing": true,
                        "iDisplayLength": 5
                    });

                    $("#allocated-gc_length").css("display", "none");
            },
            buttons:[ {
                icon: 'glyphicon glyphicon-remove-sign',
                label: 'Close',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });

    });

});