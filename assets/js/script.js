$(document).ready(function () {
    $(".status").click(function () {
       /* $("#status").show(1000);
        $("#view").removeClass('col-md-12');
        $("#view").addClass('col-md-9');*/
    });
    $(".close-button").click(function () {
        $("#status").hide(1000);
        $("#view").removeClass('col-md-9');
        $("#view").addClass('col-md-12');
    });

    setTimeout(function () {
        $("#files").show();
        $(".loading").hide();
    },800);

    setTimeout(function () {
        $(".alert").hide();
    },2000);

    $('#rename').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var url = button.data('whatever')
        var name = url.split('/');
        var size = name.length;
        var last = name[size-1].split('.');
        var modal = $(this);
        //modal.find('.modal-title').text(reci 'Dosyasının ismini Değiştirme')
        modal.find('.modal-body .url').val(url);
        modal.find('.modal-body #name').val(last[0]);
    })

    $('#file_rename').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var url = button.data('whatever')
        var name = url.split('/');
        var size = name.length;
        var last = name[size-1].split('.');
        var modal = $(this);
        //modal.find('.modal-title').text(reci 'Dosyasının ismini Değiştirme')
        modal.find('.modal-body .url').val(url);
        modal.find('.modal-body #name').val(last[0]);
    })

    $('#add_file').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var url = button.data('whatever')

        var modal = $(this);
        //modal.find('.modal-title').text(reci 'Dosyasının ismini Değiştirme')
        modal.find('.modal-body .url').val(url);
    });

    $('#add_folder').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var url = button.data('whatever')

        var modal = $(this);
        //modal.find('.modal-title').text(reci 'Dosyasının ismini Değiştirme')
        modal.find('.modal-body .url').val(url);
    });

    $('#resize').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var url = button.data('whatever')

        var modal = $(this);
        //modal.find('.modal-title').text(reci 'Dosyasının ismini Değiştirme')
        modal.find('.modal-body .url').val(url);
    });

    $('#optimize').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var url = button.data('whatever')
        var name = url.split('/');
        var size = name.length;
        var last = name[size-1].split('.');
        if(last[1] == 'png'){
            $("#range").attr('min','5');
            $("#range").attr('max','10');
            $("#range").attr('value','10');
            $("#range").attr('step','1');
            $("#textInput").text(10);
        }
        var modal = $(this);
        //modal.find('.modal-title').text(reci 'Dosyasının ismini Değiştirme')
        modal.find('.modal-body .url').val(url);
        modal.find('.modal-body .file_type').val(last[1]);
    });

    $("#range").on('change',function (event) {
        document.getElementById('textInput').textContent= $(this).val();
    });



    $("#file").on('change',function () {
        var str = $( "#file" ).val();
        var name = str.split('/');
        var size = name.length;
        var last = name[size-1].split('.');
        var type = last[1];
        if(type == 'png' || type == 'gif' || type == 'svg' || type == 'jpeg' || type == 'jpg')
            $('.size').show();
        else
            $('.size').hide();
    });

    $('.size-select').on('change',function () {
        console.log('deneme');
        var select = $(this).val();
        console.log(select);
        if(select == 'different'){
            $('.different').show();
        }else{
            $('.different').hide();
        }
    });

    $('#confirm-delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });

    $('button.confirm').confirm({
        title: 'Karar Aşaması',
        content: "Silmek istediğinize emin misiniz?",
        buttons: {
            Hayır:{
                text: 'Hayır',
                action: function(){
                    $.alert('Silmekten vazgeçtiniz!!"');
                }
            },Evet:{
                text: 'Evet',
                action: function(){
                    $('.forms').submit();
                }
            }
        }
    });

    $('.checkbox').on('change',function () {
        if($('input[name="url[]"]').is(':checked')){
            $('.deletes').show();
        }else{
            $('.deletes').hide();
        }
    });

    $('.folder-link').mousedown(function(e){
        if( e.button == 2 ) {
            var url = $(this).data('href');
            //console.log(url);
            window.location.href='http://localhost/folderplugin'+url;
        }
    });

    $( ".draggable" ).draggable({
        revert: "invalid",
        cancel: "a.ui-icon",
        containment: "document",
        helper: "clone",
        cursor: "move" });
    $( ".droppable" ).droppable({
        accept: ".draggable",
        activate: function( event, ui ) {
            $('.back-folder').css('opacity','60');
        },
        deactivate: function( event, ui ) {
            $('.back-folder').css('opacity','0');
        },
        classes: {
            "ui-droppable-active": "ui-state-active",
            "ui-droppable-hover": "ui-state-hover"
        },
        drop: function( event, ui ) {
            var file = ui.draggable.data('url');
            var name = ui.draggable.data('name');
            var url = $( this ).data('url');
            console.log(file);
            console.log(name);
            console.log(url);
            window.location.href='http://localhost/folderplugin?move_to='+file+'&from='+url+'&name='+name;
        }
    });
});


