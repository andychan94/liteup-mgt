/**
 * @author Narek Kerobian <narek@toolbox.am>
 * @copyright (c) 2016, ToolBox Software - www.toolbox.am
 * @version 0.1
 */

jQuery.fn.toolboxFilebrowser = function( options ) {

    // Default settings
    var defaults = {
        image_directory: '/img',
        current_directory: '',
        toolBoxFileBrowserLayout: '/bundles/toolboxfilebrowser/resources/toolbox-filebrowser-layout.html',
        thumbWidth: 400,
        thumbHeight: 200,
        selectedImages: [],
        current_file: '',
        currentSelected: 0,
        cropOptions: {}
    };

    if(!$.isEmptyObject(inst)){ close(inst); }

    var settings = $.extend( {}, defaults, options );
    settings.current_directory = settings.image_directory;
    var inst = this;

    initToolBoxFileBrowser(inst);

    // Functions
    function initToolBoxFileBrowser(inst){

        inst.each(function(){

            var current = $(this);
            var container;

            current.wrap('<div class="toolbox-filebrowser"></div>');
            container = current.parent('.toolbox-filebrowser');

            container.prepend('<button class="btn btn-success tb-init-filebrowser">Browse Files</button>');

            $('tb-init-filebrowser').bind('click');

        });

        $('.tb-init-filebrowser').on('click', function(e){

            settings.current_directory = settings.image_directory;

            $('body').css({'position': 'relative'});

            $('body').append('<div class="tb-filebrowser-container"></div>');

            e.preventDefault();
            var currentContainer, currentInput;

            currentContainer = $('body .tb-filebrowser-container');
            currentInput = $(this).siblings('.filebrowser');

            currentContainer.load(settings.toolBoxFileBrowserLayout, function(response, status){

                if(status == 'error'){

                    close(inst);
                    alert('Cant load resource!');

                } else {

                    initFileTree();

                    $('.tb-filebrowser-container').show();

                    displayContents(settings.image_directory);

                    $('.get-selection').on('click', function(){ getSelected(currentInput, inst); });

                    $('.dir-new').on('click', function(){ createDirectory(settings.current_directory); });
                    $('.dir-delete').on('click', function(){ deleteDirectory(settings.current_directory); });
                    $('.file-upload').on('click', function(){  uploadFiles(settings.current_directory); $('.dropzone').slideDown(); });

                    $('.file-crop').on('click', function(){  cropFiles(settings.selectedImages, settings.cropOptions); });

                    $('.select-all').on('click', function(){ selectAll($('.tb-preview__content__file__image input[type=checkbox]')); });
                    $('.deselect-all').on('click', function(){ deSelectAll($('.tb-preview__content__file__image input[type=checkbox]')); });
                    $('.trash-selected').on('click', function(){ deleteFile(settings.current_directory); });

                    $('.tb-close-window').on('click', function(){ close(inst); });

                    $('.tb-to-root').on('click', function(){

                        settings.current_directory = settings.image_directory;
                        displayContents(settings.image_directory);
                        initFileTree();

                    });

                }

            });

        });

    }

    function alertMessages(messageClass, message) {

        $(".tb-message").attr('class', 'tb-message alert-dismissible');
        $('.tb-message').addClass('alert').addClass(messageClass);
        $('.tb-message .tb-message-body').html(message);
        $('.tb-message').slideDown(200);
        $('.tb-message-close').on('click', function(){ $('.tb-message').slideUp(200); $('.tb-message .tb-message-body').html('') });

    }

    function uploadFiles(d){

        var dropzoneOptions = {
            paramName: 'img',
            acceptedFiles: "image/jpeg, image/png, image/gif, image/svg+xml, application/pdf, application/excel, application/vnd.ms-excel, application/x-excel, application/x-msexcel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            url: '/tbfb-upload',
            maxFilesize: 100, //in MB
            createImageThumbnails: false,
            maxfilesizeexceeded: function(file) {
                alert('Image is too heavey, plsaes upload images with waight less than 3M');
            },
            addRemoveLinks: false,
            sending: function(file, xhr, formData) {

                formData.append("dir", d);
                formData.append("thumb_width", settings.thumbWidth);
                formData.append("thumb_height", settings.thumbHeight);
                formData.append("action", 'upload');

            },
            success: function(f, r){

                if(r.success == 'true'){

                    initFileTree();
                    displayContents(d);
                    alertMessages('alert-success', r.message);

                }

                if(r.success == 'false'){ alertMessages('alert-danger', r.message); }

            }
        };

        Dropzone.autoDiscover = false;
        var tbDropzone = new Dropzone('.dropzone', dropzoneOptions);

        $('.tb-close-dropzone').on('click', function(e){

            e.preventDefault();
            $('.dropzone').slideUp();
            tbDropzone.destroy();

        });

    }

    function cropFiles(a, o) {

        if(a.length <= 0){

            alertMessages('alert-warning', 'You have to select images!');
            $('#crop-modal').modal('hide');

        } else {

            var cropDimentionType = 'pixel';
            var currentSelectedOption;
            var cropParams = {};

            // Set provided options
            if(!$.isEmptyObject(o)){

                $('.tb-crop-options').html('');

                var optGroupLabel = 'label="__________"';
                var optGroupHtml = '<optgroup><option value="custom">Custom</option></optgroup>';

                $.each(o, function(){

                    var optGroup = $(this)[0];

                    optGroupHtml += '<optgroup '+optGroupLabel+'>';

                    for(optionName in optGroup){

                        var currOption = "";

                        if(optGroup[optionName].type == 'pixel'){

                            currOption  = '<option value="'+optionName+'" data-type="'+optGroup[optionName].type+'" data-width="'+optGroup[optionName].width+'" data-height="'+optGroup[optionName].height+'">'+optGroup[optionName].title+'</option>';

                        }

                        if(optGroup[optionName].type == 'ratio'){

                            currOption = '<option value="'+optionName+'" data-type="'+optGroup[optionName].type+'" data-ratio="'+optGroup[optionName].ratio+'"  >'+optGroup[optionName].title+'</option>';

                        }

                        optGroupHtml += currOption;

                    }

                    optGroupHtml += "</optgroup>";

                });

                $('.tb-crop-options').html(optGroupHtml);

            }

            // Change Width Height Values On Select
            $('.tb-crop-options').change(function(){

                var optSelected = $( ".tb-crop-options option:selected");
                currentSelectedOption = optSelected;
                var dataValue = optSelected.val();

                var dataType = optSelected.attr('data-type');

                if(dataType !== 'undefined'){

                    if(dataValue == 'custom'){

                        // Switch Width And Height
                        $('.tb-crop-switch-wh').bind('click');
                        $('.tb-crop-switch-wh').on('click', function(){

                            var curentW = $('input[name="crop-width"]').val();
                            var curentH = $('input[name="crop-height"]').val();

                            $('input[name="crop-width"]').val(curentH);
                            $('input[name="crop-height"]').val(curentW);

                        });

                        $('input[name="crop-width"]').prop("readonly", false);
                        $('input[name="crop-height"]').prop("readonly", false);

                    } else {

                        $('.tb-crop-switch-wh').unbind('click');

                        $('input[name="crop-width"]').prop("readonly", true);
                        $('input[name="crop-height"]').prop("readonly", true);

                    }

                    if(dataType == 'pixel'){

                        cropDimentionType = 'pixel';

                        $('input[name="crop-width"]').val(optSelected.attr('data-width'));
                        $('input[name="crop-height"]').val(optSelected.attr('data-height'));

                    }

                    if(dataType == 'ratio') {

                        cropDimentionType = 'ratio';

                        var dataRatio = optSelected.attr('data-ratio').split('/');

                        var ratioW = dataRatio[0];
                        var ratioH = dataRatio[1];

                        $('input[name="crop-width"]').val(ratioW);
                        $('input[name="crop-height"]').val(ratioH);

                    }

                }

                // Re-init cropper after size change
                cropParams = initCropper($('.tb-crop-container'), cropDimentionType, a, currentSelectedOption);

            });

            // Init Cropper
            $('#crop-modal').modal();
            $('#crop-modal').on('shown.bs.modal', function (e) {

                if($.isEmptyObject(cropParams)){

                    cropParams = initCropper($('.tb-crop-container'), cropDimentionType, a, currentSelectedOption);

                }

            });

        }

    }

    function initCropper(cropContainer, cropDimentionType, cropArray, selectedObject) {

        var cropWidth = "";
        var cropHeight = "";
        var cropRatio = "";
        var $img, iniWidth, iniHeight, cropApi;
        var cropDimentionObj = {};
        var cropperOptions = {};

        // Destroy jCrop
        if(!$.isEmptyObject(cropApi)){ cropApi.destroy(); }

        cropContainer.html('');
        cropContainer.append('<img src="'+cropArray[settings.currentSelected]+'" class="tb-crop-image" />');
        var $img = cropContainer.children('img.tb-crop-image');

        $img.load(function(){

            iniWidth = $img.prop('naturalWidth');
            iniHeight = $img.prop('naturalHeight');

            if(selectedObject && cropDimentionType == 'ratio') {

                cropRatio = selectedObject.attr('data-ratio').split('/');

                if($.type(cropRatio[1]) !== 'undefined'){

                    cropperOptions = {
                        aspectRatio: cropRatio[0] / cropRatio[1],
                        setSelect: [0, 0, (cropRatio[0] * 100), (cropRatio[1] * 100)]
                    };

                } else {

                    cropperOptions = {
                        aspectRatio: cropRatio[0],
                        setSelect: [0, 0, (cropRatio[0] * 200), (cropRatio[1] * 200)]
                    };

                }

            }

            if(selectedObject && cropDimentionType == 'pixel') {

                if($.type(selectedObject) !== 'undefined'){

                    cropWidth = selectedObject.attr('data-width');
                    cropHeight = selectedObject.attr('data-height');

                    cropperOptions = {
                        aspectRatio: cropWidth / cropHeight,
                        minSize: [cropWidth, cropHeight],
                        setSelect: [0, 0, cropWidth, cropHeight]
                    };

                }

            }

            var cropFunctionObj = {
                trueSize: [iniWidth, iniHeight],
                onSelect: getCropCoords,
                onChange: getCropCoords
            };

            function getCropCoords(c) {

                cropDimentionObj = {
                    file_path: cropArray[settings.currentSelected],
                    ini_width: iniWidth,
                    ini_height: iniHeight,
                    cropWidth: c.w,
                    cropHeight: c.h,
                    resWidth: c.w,
                    resHeight: c.h,
                    x: c.x,
                    y: c.y,
                    x2: c.x2,
                    y2: c.y2,
                };

            };

            $.extend(cropperOptions, cropFunctionObj);

            $('.tb-crop-image').Jcrop(cropperOptions, function(){

                cropApi = this;

            });

            // On Crop Click
            $('.tb-crop-final').unbind('click');
            $('.tb-crop-final').bind('click');

            if(iniWidth < cropWidth || iniHeight < cropHeight){

                $('.tb-crop-final').on('click', function(){

                    alert('Image is too small, choose different size or simply close!');

                });

            } else {

                $('.tb-crop-final').on('click', function(){

                    if(cropWidth == '' || cropHeight == ''){

                        cropWidth = cropDimentionObj.cropWidth;
                        cropHeight = cropDimentionObj.cropHeight;

                    }

                    $.ajax({
                        method: 'POST',
                        url: '/tbfb-crop',
                        data: 'file_path=' + cropDimentionObj.file_path +
                            '&action=crop'+
                            '&crop_width=' + cropWidth +
                            '&crop_height=' + cropHeight +
                            '&ini_width=' + (cropDimentionObj.x2 - cropDimentionObj.x) +
                            '&ini_height=' + (cropDimentionObj.y2 - cropDimentionObj.y) +
                            '&res_width=' + cropDimentionObj.resWidth +
                            '&res_height=' + cropDimentionObj.resHeight +
                            '&scaleX=' + cropDimentionObj.x +
                            '&scaleY=' + cropDimentionObj.y,
                        success: function(success_response){

                            if(success_response.success == 'false'){

                                alertMessages('alert-danger', success_response.message);

                            } else if(success_response.success == 'true'){

                                alertMessages('alert-success', success_response.message);

                            }

                            settings.currentSelected++;

                            if((settings.currentSelected + 1) > cropArray.length){

                                displayContents(settings.current_directory);

                                settings.selectedImages = [];
                                settings.currentSelected = 0;

                                $('#crop-modal').modal('toggle');


                            } else {

                                initCropper(cropContainer, cropDimentionType, cropArray, selectedObject);

                            }

                        },
                        error: function(error_response){
                            alert('Error: ' + error_response);
                            console.log(error_response);
                        }
                    });

                });

            }

        });

    }

    function initFileTree(){

        $('.tb-tree').fileTree({
            script: '/tbfb-filetree',
            root: settings.image_directory,
            folderEvent: 'click',
            expandSpeed: 1,
            collapseSpeed: 1,
            loadMessage: 'Loading, please wait',
        }, function(file) {

        }, function(dir){

            settings.current_directory = dir;
            displayContents(dir);

        });

    }

    function createDirectory(rootDir) {

        alertMessages('alert-success', '<input type="text" style="color: #000;" name="new-dir-name" /><button class="tb-create-dir" style="color: #000;"><i class="fa fa-plus"></i> Create</button><button class="tb-message-close" style="color: #000;"><i class="fa fa-times"></i> Cancel</button>');

        $('.tb-create-dir').on('click', function(){

            var newDirName = $('input[name=new-dir-name]').val();

            if(newDirName){

                var newDirName = rootDir + '/' + newDirName;

                $.ajax({
                    method: 'POST',
                    url: '/tbfb-createdir',
                    data: 'dir=' + newDirName + '&action=create_dir',
                    success: function(success_response){

                        if(success_response.success == 'false'){

                            alertMessages('alert-danger', success_response.message);

                        } else if(success_response.success == 'true'){

                            alertMessages('alert-success', success_response.message);

                        }

                        initFileTree();

                    },
                    error: function(error_response){
                        alert('Error: ' + error_response);
                        console.log(error_response);
                    }
                });

            } else {

                alertMessages('alert-warning', 'Directory name can\'t be empty!');

            }

        });

    }

    function deleteDirectory(d) {

        if(d == settings.image_directory){

            alertMessages('alert-warning', 'You can\'t delete your image root directory!');

        } else {

            alertMessages('alert-warning', 'Warning: This action will delete <b>' + d + '</b> directory and all it\'s contents! <button class="tb-remove-dir-confirm"><i class="fa fa-check"></i> Remove</button><button class="tb-message-close"><i class="fa fa-times"></i> Cancel</button>');

            $('.tb-remove-dir-confirm').on('click', function(){

                $.ajax({
                    method: 'POST',
                    url: '/tbfb-deletetree',
                    data: 'dir=' + d,
                    success: function(success_response){

                        if(success_response.success == 'false'){

                            alertMessages('alert-danger', success_response.message);

                        } else if(success_response.success == 'true'){

                            alertMessages('alert-success', success_response.message);

                        }

                        initFileTree();

                    },
                    error: function(error_response){

                        alert('Error: ' + error_response);

                    }
                });

            });

        }

    }

    function deleteFile(d){

        if(settings.selectedImages.length === 0){

            alertMessages('alert-warning', 'Please select images to delete!');

        } else {

            alertMessages('alert-warning', 'Warning: This action will delete selected files! <button class="tb-remove-file-confirm"><i class="fa fa-check"></i> Remove</button><button class="tb-message-close"><i class="fa fa-times"></i> Cancel</button>');

            $('.tb-remove-file-confirm').on('click', function(){

                for(var i = 0; i < settings.selectedImages.length; i++ ){

                    var fileToDelete = settings.selectedImages[i];

                    $.ajax({
                        method: 'POST',
                        url: '/tbfb-deletefile',
                        data: 'file=' + fileToDelete,
                        success: function(success_response){

                            if(success_response.success == 'false'){

                                alertMessages('alert-danger', success_response.message);

                            } else if(success_response.success == 'true'){

                                alertMessages('alert-success', success_response.message);
                                settings.selectedImages = [];
                                settings.currentSelected = 0;

                                displayContents(d);

                            }

                        },
                        error: function(error_response){

                            alert('Error: ' + error_response);

                        }
                    });

                }

            });

        }

    }

    function displayContents(d) {

        $.ajax({
            method: 'POST',
            url: '/tbfb-dircont',
            data: 'dir=' + d,
            success: function(success_response){

                success_response = JSON.parse(success_response);

                if(success_response.success == 'false'){

                    alertMessages('alert-danger', success_response.message);

                } else if(success_response.success == 'true'){


                    if($.isEmptyObject(success_response.dir_content)){

                        $('.tb-preview__content').html('No Files In This Directory');

                    } else {

                        $('.tb-preview__content').html('<div class="clearfix"></div>');
                        $.each(success_response.dir_content, function(){

                            var dateNow = new Date();
                            var elem = $(this)[0];

                            var icon = elem.icon;
                            var path = elem.path;
                            var displayPath = elem.path+'?cc='+dateNow.getTime();
                            var elemType = elem.type;
                            var name = elem.name;

                            if(elemType !== 'image'){

                                $('.tb-preview__content').append('<div class="tb-preview__content__file"><div class="tb-preview__content__file__image"><input type="checkbox" data-value="'+path+'" /><img src="/bundles/toolboxfilebrowser/img/icons/'+icon+'" /></div><a href="javascript:void(0)" data-path="'+path+'">'+name.substring(0,11)+'</a></div>');

                            } else {

                                $('.tb-preview__content').append('<div class="tb-preview__content__file"><div class="tb-preview__content__file__image" style="background-image: url(\''+displayPath+'\');"><input type="checkbox" data-value="'+path+'" /></div><a href="javascript:void(0)" data-path="'+path+'">'+name.substring(0,11)+'</a></div>');

                            }

                            $('.tb-preview__content__file').on('click', 'a', function(){

                                var dataPath = $(this).attr('data-path');
                                settings.current_file = dataPath;
                                $('.tb-filepath').html('Copy path: '+settings.current_file);

                            });

                        });

                        $('.tb-preview__content').append('<div class="clearfix"></div>');

                        $.each($('input[type="checkbox"]'), function(){
                            $(this).change(function(){

                                var condition = $(this).prop('checked');
                                var thisValue = $(this).attr('data-value');

                                if(condition){

                                    if($.inArray(thisValue, settings.selectedImages) !== '-1') {

                                        settings.selectedImages.push(thisValue);

                                    }

                                } else {

                                    if($.inArray(thisValue, settings.selectedImages) !== '-1') {

                                        settings.selectedImages = $.grep(settings.selectedImages, function(value) {
                                            return value != thisValue;
                                        });

                                    }

                                }

                            });

                        });

                    }

                }

            },
            error: function(error_response){

                alert('Error: ' + error_response);

            }
        });

    }

    function getSelected(currentInput, inst) {

        var selected = settings.selectedImages;

        if(selected.length == 0){

            alertMessages('alert-warning', 'Please select images!');

        } else {

            if(currentInput.hasClass('single-select') && selected.length > 1){

                alertMessages('alert-warning', 'You must select One Image!');

            } else {

                currentInput.val('');

                if(currentInput.hasClass('single-select')){
                    currentInput.val(selected[0]);
                } else {
                    currentInput.val(JSON.stringify(selected));
                }

                close(inst);

            }

        }

    }

    function selectAll(selector){

        settings.selectedImages = [];

        $.each(selector, function(){

            $(this).prop('checked', true);

            settings.selectedImages.push($(this).attr('data-value'));

        });

    }

    function deSelectAll(selector){

        selector.prop('checked', false);
        settings.selectedImages = [];

    }

    function close(inst) {

        settings.selectedImages = [];
        settings.currentSelected = 0;
        settings.current_file = '';
        settings.current_directory = '';

        $('#crop-modal').modal('hide');
        $('.toolbox-filebrowser-layout').detach();
        $('.tb-filebrowser-container').remove();

    }

};
