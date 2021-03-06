var loadImageFile = (function () {   
    if (window.FileReader) {   
    var oPreviewImg = null, oFReader = new window.FileReader(),   
    rFilter = /^(?:image\/cis\-cod|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;   
    oFReader.onload = function (oFREvent) {   
    if (!oPreviewImg) {   
    var newPreview = document.getElementById("imagePreview");
    oPreviewImg = new Image();   
    oPreviewImg.style.width = (newPreview.offsetWidth).toString() + "px";   
    oPreviewImg.style.height = (newPreview.offsetHeight).toString() + "px";   
    newPreview.appendChild(oPreviewImg);   
    }   
    oPreviewImg.src = oFREvent.target.result;   
    };   
    
      
    return function () {   
    var aFiles = document.getElementById("imageInput").files;   
    if (aFiles.length === 0) { return; }
    if (!rFilter.test(aFiles[0].type)) { 
		$(".wrong").show();return;
	 }
	 else{$(".wrong").hide();}
    oFReader.readAsDataURL(aFiles[0]);   
    }   
      
    }   
    if (navigator.appName === "Microsoft Internet Explorer") {   
    return function () {   
    alert(document.getElementById("imageInput").value);   
    document.getElementById("imagePreview").filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = document.getElementById("imageInput").value;       
      
    }   
    }   
    })();   
