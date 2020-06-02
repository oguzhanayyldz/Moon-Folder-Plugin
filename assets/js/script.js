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
        $(".alert").hide();
    },400);

    $('#rename').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var url = button.data('whatever') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        //modal.find('.modal-title').text(reci 'Dosyasının ismini Değiştirme')
        modal.find('.modal-body .url').val(url);
    })

});