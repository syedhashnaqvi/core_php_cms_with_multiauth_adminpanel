$("#upload_btn").on("click",function(){
    var upload_url = $(this).data("upload_url");
    var csrf = $(this).data("upload_csrf");
    var form_data = new FormData();
    var image_number = 1;
    var error = '';
    for(var count = 0; count < _('mediafiles').files.length; count++)  
    {
        if(!['image/jpeg', 'image/png', 'video/mp4'].includes(_('mediafiles').files[count].type))
        {
            error += '<div class="alert alert-danger"><b>'+image_number+'</b> Selected File must be .jpg or .png Only.</div>';
        }
        else
        {
            form_data.append("mediafiles[]", _('mediafiles').files[count]);
            form_data.append("csrf", csrf);
        }
        image_number++;
    }
    if(error != '')
    {
        _('uploaded_image').innerHTML = error;
        _('mediafiles').value = '';
    }
    else
    {
        _('progress_bar').style.display = 'block';
        var ajax_request = new XMLHttpRequest();
        ajax_request.open("POST", upload_url);
        ajax_request.upload.addEventListener('progress', function(event){
            var percent_completed = Math.round((event.loaded / event.total) * 100);
            _('progress_bar_process').style.width = percent_completed + '%';
            _('progress_bar_process').innerHTML = percent_completed + '% completed';
        });
        ajax_request.addEventListener('load', function(event){
            _('uploaded_image').innerHTML = '<div class="text-success d-block text-center">Files Uploaded Successfully</div>';
            _('mediafiles').value = '';
        });
        ajax_request.send(form_data);
    }    
});

function _(element)
{
    return document.getElementById(element);
}


function copyToClipboard(textToCopy) {
    // navigator clipboard api needs a secure context (https)
    if (navigator.clipboard && window.isSecureContext) {
        // navigator clipboard api method'
        return navigator.clipboard.writeText(textToCopy);
    } else {
        // text area method
        let textArea = document.createElement("textarea");
        textArea.value = textToCopy;
        // make the textarea out of viewport
        textArea.style.position = "fixed";
        textArea.style.left = "-999999px";
        textArea.style.top = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
       alert(document.execCommand("copy"));
        textArea.remove();
    }
}