<?php
?>
<script>
    $(function () {
    $("#mySingleFieldTags").tagit({
        allowSpaces: true
    });


    var bar         = $('.bar');
    var percent     = $('.percent');
    var prcsvdo		= $('.pt_prcs_vdo');
    var is_uploaded = false;

    var video_drop_block = $("[data-block='video-drop-zone']");

    if (typeof(window.FileReader)){
    video_drop_block[0].ondragover = function() {
    video_drop_block.addClass('hover');
    return false;
};

    video_drop_block[0].ondragleave = function() {
    video_drop_block.removeClass('hover');
    return false;
};

    video_drop_block[0].ondrop = function(event) {
    event.preventDefault();
    video_drop_block.removeClass('hover');
    var file = event.dataTransfer.files;
    $('#upload-video').find('input').prop('files', file);
    $('#upload-video').submit();
};
}

    $('#upload-video').submit(function(event) {
    var file_size = $(".upload-video-file").prop('files')[0].size;
    if (file_size > "{{CONFIG max_upload}}") {
    swal({
    title: '{{LANG error}}',
    text:  "{{LANG file_is_too_big}} <?php echo pt_size_format($pt->config->max_upload); ?>",
    type: 'error',
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'OK',
    buttonsStyling: true,
    confirmButtonClass: 'btn btn-success',
}).then(function(){
    swal.close();
    $('.upload-video-file').val('');
},
    function() {
    swal.close();
    $('.upload-video-file').val('');
});
    return false;
}
    else{
    var filename = $('.upload-video-file').val().split('\\').pop();
    $('#title').val(filename);
    <?php if (PT_IsAdmin()) { ?>
    $('#movie_title').val(filename);
    <?php } ?>
    // $('#upload-form').removeClass('hidden');
    // $('.upload').addClass('hidden');
    if('{{TYPE}}' == 1){
    $("#movie_fields").css('display', 'block');
    $("#video_fields").css('display', 'none');
}
    // console.log('{{TYPE}}');
}
});



//	snap-shot
    var video = document.getElementById('snap_video');
    var canvas = document.querySelector('canvas');
    var context = canvas.getContext('2d');
    var w, h, ratio;

    video.addEventListener('loadedmetadata', function() {
    ratio = video.videoWidth / video.videoHeight;
    w = video.videoWidth - 100;
    h = parseInt(w / ratio, 10);
    canvas.width = w;
    canvas.height = h;
}, false);
    ///define a function
    $("#snap").click(function (e) {
    e.preventDefault();
    context.fillRect(0, 0, w, h);
    context.drawImage(video, 0, 0, w, h);
    $("#cover_auto_time").val($("#snap_video")[0].currentTime);
//		alert('{{LANG successfully_snap}}');
    $('#submit-btn').attr('disabled', false);
    Snackbar.show({text: '<i class="fa fa-check"></i> {{LANG successfully_snap}}'});
});
    /////snap shot
    //let fd = new FormData(document.getElementById('upload-video'));
    //fd.append("data", $("input[name='video']").val());
    $('#upload-video').ajaxForm({
    url: '{{LINK aj/upload-video}}?hash=' + $('.main_session').val(),
    //formData: fd,
    //contentType: false,
    //processContent: false,
    dataType:'JSON',
    beforeSend: function(data) {
    $('.progress').removeClass('hidden');
    prcsvdo.removeClass('hidden');
    var percentVal = '0%';
    bar.width(percentVal);
    percent.html(percentVal);
    console.log($(this).data());
    console.log(data);
    $("#upload-form").removeClass('hidden');
    $('#title').val(data.file_name);
},
    uploadProgress: function(event, position, total, percentComplete) {
    if(percentComplete > 50) {
    percent.addClass('white');
}
    var percentVal = percentComplete + '%';
    bar.width(percentVal);
    percent.html(percentVal);
    prcsvdo.html('{{LANG porcessing_video}}');
    // if (percentComplete == 100) {
    //    // prcsvdo.html('<svg width="30" height="10" viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg" fill="#000"><circle cx="15" cy="15" r="15"><animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite" /><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite" /></circle><circle cx="60" cy="15" r="9" fill-opacity="0.3"><animate attributeName="r" from="9" to="9" begin="0s" dur="0.8s" values="9;15;9" calcMode="linear" repeatCount="indefinite" /><animate attributeName="fill-opacity" from="0.5" to="0.5" begin="0s" dur="0.8s" values=".5;1;.5" calcMode="linear" repeatCount="indefinite" /></circle><circle cx="105" cy="15" r="15"><animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite" /><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite" /></circle></svg> {{LANG porcessing_video}}');
    // 	prcsvdo.html('{{LANG upload_video_complete}}');
    // 	// $('.progress').find('.bar').removeClass('upload-progress-bar');
    // }
},
    error: function(){
    $("#upload-form").addClass('hidden');
},
    success: function(data) {
    prcsvdo.html('{{LANG upload_video_complete}}');
    $('.progress').addClass('hidden');
    // prcsvdo.addClass('hidden');
    if (data.status == 200) {

    $('#video-location').val(data.file_path);
    Snackbar.show({text: '<i class="fa fa-check"></i> ' + data.file_name + ' {{LANG successfully_uplaoded}}'});
    $('#submit-btn').attr('disabled', false);
    $("#snap").css('display', 'inline-block');
    $('.upload-video-file').val('');
    $('.progress').removeClass('hidden');
    // if($('#title').val() != data.file_name)
    // 	$('#title').val(data.file_name);


    //snap shot
    $("#video_path").attr('src', data.file_path);
    $("#snap_video")[0].load();
    //
    <?php if (PT_IsAdmin()) { ?>
    $('#movie_title').val(data.file_name);
    <?php } ?>
}
    else if(data.status == 401){

    $("#upload-form").addClass('hidden');
    swal({
    title: '{{LANG oops}}!',
    text: "{{LANG upload_limit_reached}}!",
    type: 'info',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: '{{LANG upgrade_now}}',
    cancelButtonText: '{{LANG cancel}}',
    confirmButtonClass: 'btn btn-success margin-right',
    cancelButtonClass: 'btn',
    buttonsStyling: false
}).then(function(){
    //Go pro
    window.location.href = '{{LINK go_pro}}';
},
    function() {
    window.location.href = '{{LINK }}';
});
}
    else if(data.status == 402){

    $("#upload-form").addClass('hidden');
    swal({
    title: '{{LANG error}}',
    text: data.message,
    type: 'error',
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'OK',
    buttonsStyling: true,
    confirmButtonClass: 'btn btn-success',
}).then(function(){
    swal.close();
    $('.upload-video-file').val('');
},
    function() {
    swal.close();
    $('.upload-video-file').val('');
});
}
    else {

    $("#upload-form").addClass('hidden');
    Snackbar.show({showAction: false,backgroundColor: '#e22e40',text: '<div>'+ data.error +'</div>'});
}
}
});
    var swal_upload = `<div class="swal2-container swal2-fade swal2-shown submit_upload_video" style="overflow-y: auto;">
	<div role="dialog" aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-modal swal2-show" tabindex="-1" style="width: 500px; padding: 20px; background: rgb(255, 255, 255); display: block; min-height: 311px;">
						<div class="swal2-icon submit-upload" style="display: block;"></div>
				<h2 class="swal2-title" id="swal2-title">{{LANG please_wait}}</h2>
				<div id="swal2-content" class="swal2-content" style="display: block;">{{LANG submit_upload_video_in_progress}}</div>
			</div>
			</div>`;
    $('#upload-form form').ajaxForm({
    url: '{{LINK aj/submit-video}}?hash=' + $('.main_session').val(),
    beforeSend: function() {
    $('#submit-btn').attr('disabled', true);
    $("#snap").css('display', 'none');
    $('#submit-btn').val("{{LANG please_wait}}");
    $("#container_content").prepend(swal_upload);
},
    success: function(data) {
    if (data.status == 200) {
    window.location.href = data.link;
}
    else if(data.status == 402){
    $("#container_content .submit_upload_video:eq(0)").remove();

    swal({
    title: '{{LANG error}}',
    text: data.message,
    type: 'error',
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'OK',
    buttonsStyling: true,
    confirmButtonClass: 'btn btn-success',
}).then(function(){
    window.location.href = '{{LINK upload-video}}';
},
    function() {
    window.location.href = '{{LINK }}';
});
}
    else {
    $("#container_content .submit_upload_video:eq(0)").remove();

    $('#submit-btn').attr('disabled', false);
    $("#snap").css('display', 'inline-block');
    $('#submit-btn').val('{{LANG publish}}');
    Snackbar.show({text: '<div>'+ data.message +'</div>'});
}
},
    error: function (){
    $("#container_content .submit_upload_video:eq(0)").remove();
}
});

    $('.upload-video-file').on('change', function() {
    $('#upload-video').submit();
});
});


    function PT_OpenUploadForm() {
    $('#upload-video').find('input').trigger('click');
}

    jQuery(function($) {
    $(document).ready(function() {
        $( '.upload' ).on('click', function(e) {
            $( '.upload-video-file' ).trigger("click");
        });
    });
});
</script>