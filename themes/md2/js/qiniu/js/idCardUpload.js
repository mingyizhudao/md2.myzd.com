/*global Qiniu */
/*global plupload */
/*global FileProgress */
/*global hljs */


$(function () {
    var num = 0;
    var domForm = $('#idCard-form'),
            uploadFile = domForm.attr('data-url-uploadfile'),
            urlReturn = domForm.attr('data-url-return'),
            isVerified = $('article').attr('data-isVerified'),
            submitBtn = $('#submitBtn');
    //存储成功上传图片的信息
    var firstData = '';
    var secondData = '';
    var thirdData = '';
    var firstImg = false;
    var secondImg = false;
    var thirdImg = false;
    if (isVerified != 0) {
        firstImg = true;
        secondImg = true;
        thirdImg = true;
    }
    //加载已上传图片
    $.ajax({
        url: $('article').attr('data-realAuth'),
        success: function (data) {
            setImg(data);
        },
        error: function (XmlHttpRequest, textStatus, errorThrown) {
            setImg('');
            console.log(XmlHttpRequest);
            console.log(textStatus);
            console.log(errorThrown);
        },
    });
    function setImg(data) {
        var firstImgClick = 'http://static.mingyizhudao.com/147634523695755';
        var secondImgClick = 'http://static.mingyizhudao.com/147634523727390';
        var thirdImgClick = 'http://static.mingyizhudao.com/147634523577826';
        if (data != '') {
            var files = data.results.files;
            if (files && files.length > 0) {
                for (var i = 0; i < files.length; i++) {
                    if (files[i].certType == 1) {
                        firstImgClick = files[i].thumbnailUrl;
                    } else if (files[i].certType == 2) {
                        secondImgClick = files[i].thumbnailUrl;
                    } else if (files[i].certType == 3) {
                        thirdImgClick = files[i].thumbnailUrl;
                    }
                }
            }
        }
        $('#pickfiles1').find('img').attr('src', firstImgClick);
        $('#pickfiles2').find('img').attr('src', secondImgClick);
        $('#pickfiles3').find('img').attr('src', thirdImgClick);

        var uploader = Qiniu.uploader({
            runtimes: 'html5,flash,html4',
            browse_button: 'pickfiles1',
            container: 'container1',
            drop_element: 'container1',
            max_file_size: '100mb',
            flash_swf_url: 'bower_components/plupload/js/Moxie.swf',
            dragdrop: true,
            chunk_size: '4mb',
            uptoken_url: $('#uptoken_url').val(),
            domain: $('#domain').val(),
            get_new_uptoken: false,
            multi_selection: false,
            // downtoken_url: '/downtoken',
            // unique_names: true,
            // save_key: true,
            // x_vars: {
            //     'id': '1234',
            //     'time': function(up, file) {
            //         var time = (new Date()).getTime();
            //         // do something with 'time'
            //         return time;
            //     },
            // },
            auto_start: false,
            log_level: 5,
            filters: {
                prevent_duplicates: true,
                mime_types: [
                    {title: "Image files", extensions: "bmp,jpg,gif,png,jpeg"}
                ]
            },
            init: {
                'FilesAdded': function (up, files) {
                    firstImg = true;
                    $('table').show();
                    $('#success').hide();
                    plupload.each(files, function (file) {
                        //图片预览
                        showPreview(file, '#container1');

                        var progress = new FileProgress(file, 'fsUploadProgress1');
                        progress.setStatus("等待...");
                        progress.bindUploadCancel(up);
                    });
                },
                'BeforeUpload': function (up, file) {
                    var progress = new FileProgress(file, 'fsUploadProgress1');
                    var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                    if (up.runtime === 'html5' && chunk_size) {
                        progress.setChunkProgess(chunk_size);
                    }
                },
                'UploadProgress': function (up, file) {
                    var progress = new FileProgress(file, 'fsUploadProgress1');
                    var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                    progress.setProgress(file.percent + "%", file.speed, chunk_size);
                },
                'UploadComplete': function () {
                    uploader2.start();
                },
                'FileUploaded': function (up, file, info) {
                    //单个文件上传成功所做的事情 
                    // 其中 info 是文件上传成功后，服务端返回的json，形式如
                    // {
                    //    "hash": "Fh8xVqod2MQ1mocfI4S4KpRL6D98",
                    //    "key": "gogopher.jpg"
                    //  }
                    // 参考http://developer.qiniu.com/docs/v6/api/overview/up/response/simple-response.html
                    // var domain = up.getOption('domain');
                    // var res = parseJSON(info);
                    // var sourceLink = domain + res.key; 获取上传成功后的文件的Url
                    var progress = new FileProgress(file, 'fsUploadProgress1');
                    var infoJson = eval('(' + info + ')');
                    progress.setComplete(up, info);
                    var fileExtension = file.name.substring(file.name.lastIndexOf('.') + 1);
                    var imgUrl = domForm.find('#domain').val() + '/' + infoJson.key;
                    firstData = '"1":{"report_type":"","file_size":"' + encodeURIComponent(file.size) +
                            '","mime_type":"' + encodeURIComponent(file.type) +
                            '","file_name":"' + encodeURIComponent(file.name) +
                            '","file_url":"' + encodeURIComponent(file.name) +
                            '","file_ext":"' + encodeURIComponent(fileExtension) +
                            '","remote_domain":"' + encodeURIComponent(domForm.find('#domain').val()) +
                            '","remote_file_key":"' + encodeURIComponent(infoJson.key) +
                            '"},';
                },
                'Error': function (up, err, errTip) {
                    returnResult = false;
                    console.log('错误信息' + errTip);
                    $('table').show();
                    var progress = new FileProgress(err.file, 'fsUploadProgress1');
                    progress.setError();
                    progress.setStatus(errTip);
                }
                ,
                'Key': function (up, file) {
                    var fileExtension = file.name.substring(file.name.lastIndexOf('.') + 1);
                    var key = (new Date()).getTime() + '' + Math.floor(Math.random() * 100) + '.' + fileExtension;
                    // do something with key
                    return key;
                }
            }
        });
        uploader.bind('FileUploaded', function () {
            //console.log('hello man,a file is uploaded');
        });
        var Qiniu2 = new QiniuJsSDK();
        var uploader2 = Qiniu2.uploader({
            runtimes: 'html5,flash,html4',
            browse_button: 'pickfiles2',
            container: 'container2',
            drop_element: 'container2',
            max_file_size: '100mb',
            flash_swf_url: 'bower_components/plupload/js/Moxie.swf',
            dragdrop: true,
            chunk_size: '4mb',
            uptoken_url: $('#uptoken_url').val(),
            domain: $('#domain').val(),
            get_new_uptoken: false,
            multi_selection: false,
            // downtoken_url: '/downtoken',
            // unique_names: true,
            // save_key: true,
            // x_vars: {
            //     'id': '1234',
            //     'time': function(up, file) {
            //         var time = (new Date()).getTime();
            //         // do something with 'time'
            //         return time;
            //     },
            // },
            auto_start: false,
            log_level: 5,
            filters: {
                prevent_duplicates: true,
                mime_types: [
                    {title: "Image files", extensions: "bmp,jpg,gif,png,jpeg"}
                ]
            },
            init: {
                'FilesAdded': function (up, files) {
                    secondImg = true;
                    $('table').show();
                    $('#success').hide();
                    plupload.each(files, function (file) {
                        //图片预览
                        showPreview(file, '#container2');
                        var progress = new FileProgress(file, 'fsUploadProgress2');
                        progress.setStatus("等待...");
                        progress.bindUploadCancel(up);
                    });
                },
                'BeforeUpload': function (up, file) {
                    var progress = new FileProgress(file, 'fsUploadProgress2');
                    var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                    if (up.runtime === 'html5' && chunk_size) {
                        progress.setChunkProgess(chunk_size);
                    }
                },
                'UploadProgress': function (up, file) {
                    var progress = new FileProgress(file, 'fsUploadProgress2');
                    var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                    progress.setProgress(file.percent + "%", file.speed, chunk_size);
                },
                'UploadComplete': function () {
                    uploader3.start();
                },
                'FileUploaded': function (up, file, info) {
                    //单个文件上传成功所做的事情 
                    // 其中 info 是文件上传成功后，服务端返回的json，形式如
                    // {
                    //    "hash": "Fh8xVqod2MQ1mocfI4S4KpRL6D98",
                    //    "key": "gogopher.jpg"
                    //  }
                    // 参考http://developer.qiniu.com/docs/v6/api/overview/up/response/simple-response.html
                    // var domain = up.getOption('domain');
                    // var res = parseJSON(info);
                    // var sourceLink = domain + res.key; 获取上传成功后的文件的Url
                    var progress = new FileProgress(file, 'fsUploadProgress2');
                    var infoJson = eval('(' + info + ')');
                    progress.setComplete(up, info);
                    var fileExtension = file.name.substring(file.name.lastIndexOf('.') + 1);
                    var imgUrl = domForm.find('#domain').val() + '/' + infoJson.key;
                    secondData = '"2":{"report_type":"","file_size":"' + encodeURIComponent(file.size) +
                            '","mime_type":"' + encodeURIComponent(file.type) +
                            '","file_name":"' + encodeURIComponent(file.name) +
                            '","file_url":"' + encodeURIComponent(file.name) +
                            '","file_ext":"' + encodeURIComponent(fileExtension) +
                            '","remote_domain":"' + encodeURIComponent(domForm.find('#domain').val()) +
                            '","remote_file_key":"' + encodeURIComponent(infoJson.key) +
                            '"},';
                },
                'Error': function (up, err, errTip) {
                    returnResult = false;
                    console.log('错误信息' + errTip);
                    $('table').show();
                    var progress = new FileProgress(err.file, 'fsUploadProgress2');
                    progress.setError();
                    progress.setStatus(errTip);
                }
                ,
                'Key': function (up, file) {
                    var fileExtension = file.name.substring(file.name.lastIndexOf('.') + 1);
                    var key = (new Date()).getTime() + '' + Math.floor(Math.random() * 100) + '.' + fileExtension;
                    // do something with key
                    return key;
                }
            }
        });
        uploader.bind('FileUploaded', function () {
            //console.log('hello man,a file is uploaded');
        });

        var Qiniu3 = new QiniuJsSDK();
        var uploader3 = Qiniu3.uploader({
            runtimes: 'html5,flash,html4',
            browse_button: 'pickfiles3',
            container: 'container3',
            drop_element: 'container3',
            max_file_size: '100mb',
            flash_swf_url: 'bower_components/plupload/js/Moxie.swf',
            dragdrop: true,
            chunk_size: '4mb',
            uptoken_url: $('#uptoken_url').val(),
            domain: $('#domain').val(),
            get_new_uptoken: false,
            multi_selection: false,
            // downtoken_url: '/downtoken',
            // unique_names: true,
            // save_key: true,
            // x_vars: {
            //     'id': '1234',
            //     'time': function(up, file) {
            //         var time = (new Date()).getTime();
            //         // do something with 'time'
            //         return time;
            //     },
            // },
            auto_start: false,
            log_level: 5,
            filters: {
                prevent_duplicates: true,
                mime_types: [
                    {title: "Image files", extensions: "bmp,jpg,gif,png,jpeg"}
                ]
            },
            init: {
                'FilesAdded': function (up, files) {
                    thirdImg = true;
                    $('table').show();
                    $('#success').hide();
                    plupload.each(files, function (file) {
                        //图片预览
                        showPreview(file, '#container3');
                        var progress = new FileProgress(file, 'fsUploadProgress3');
                        progress.setStatus("等待...");
                        progress.bindUploadCancel(up);
                    });
                },
                'BeforeUpload': function (up, file) {
                    var progress = new FileProgress(file, 'fsUploadProgress3');
                    var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                    if (up.runtime === 'html5' && chunk_size) {
                        progress.setChunkProgess(chunk_size);
                    }
                },
                'UploadProgress': function (up, file) {
                    var progress = new FileProgress(file, 'fsUploadProgress3');
                    var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                    progress.setProgress(file.percent + "%", file.speed, chunk_size);
                },
                'UploadComplete': function () {
                    //图片信息存储
                    var allImg = firstData + secondData + thirdData;
                    allImg = allImg.substr(0, allImg.length - 1);
                    var formData = '{"auth_file":{' + allImg + '}}';
                    var encryptContext = do_encrypt(formData, pubkey);
                    var param = {param: encryptContext};
                    $.ajax({
                        url: uploadFile,
                        data: param,
                        type: 'post',
                        success: function (data) {
                            if (data.status == 'ok') {
                                location.href = urlReturn;
                            }
                        }
                    });
                },
                'FileUploaded': function (up, file, info) {
                    //单个文件上传成功所做的事情 
                    // 其中 info 是文件上传成功后，服务端返回的json，形式如
                    // {
                    //    "hash": "Fh8xVqod2MQ1mocfI4S4KpRL6D98",
                    //    "key": "gogopher.jpg"
                    //  }
                    // 参考http://developer.qiniu.com/docs/v6/api/overview/up/response/simple-response.html
                    // var domain = up.getOption('domain');
                    // var res = parseJSON(info);
                    // var sourceLink = domain + res.key; 获取上传成功后的文件的Url
                    var progress = new FileProgress(file, 'fsUploadProgress3');
                    var infoJson = eval('(' + info + ')');
                    progress.setComplete(up, info);
                    var fileExtension = file.name.substring(file.name.lastIndexOf('.') + 1);
                    var imgUrl = domForm.find('#domain').val() + '/' + infoJson.key;
                    thirdData = '"3":{"report_type":"","file_size":"' + encodeURIComponent(file.size) +
                            '","mime_type":"' + encodeURIComponent(file.type) +
                            '","file_name":"' + encodeURIComponent(file.name) +
                            '","file_url":"' + encodeURIComponent(file.name) +
                            '","file_ext":"' + encodeURIComponent(fileExtension) +
                            '","remote_domain":"' + encodeURIComponent(domForm.find('#domain').val()) +
                            '","remote_file_key":"' + encodeURIComponent(infoJson.key) +
                            '"},';
                },
                'Error': function (up, err, errTip) {
                    returnResult = false;
                    console.log('错误信息' + errTip);
                    $('table').show();
                    var progress = new FileProgress(err.file, 'fsUploadProgress3');
                    progress.setError();
                    progress.setStatus(errTip);
                }
                ,
                'Key': function (up, file) {
                    var fileExtension = file.name.substring(file.name.lastIndexOf('.') + 1);
                    var key = (new Date()).getTime() + '' + Math.floor(Math.random() * 100) + '.' + fileExtension;
                    // do something with key
                    return key;
                }
            }
        });
        uploader.bind('FileUploaded', function () {
            //console.log('hello man,a file is uploaded');
        });

        //图片预览
        function showPreview(file, id) {
            var preloader = new mOxie.Image();
            preloader.onload = function () {
                preloader.downsize(300, 300);
                $(id).find('img').attr('src', preloader.getAsDataURL());
            };
            preloader.load(file.getSource());
        }

        submitBtn.click(function () {
            submitBtn.find('button').attr('disabled', true);
            if (firstImg && secondImg && thirdImg) {
                $('#jingle_loading.initLoading').show();
                $('#jingle_loading_mask').show();
                uploader.start();
            } else {
                $('#jingle_toast').show();
                setTimeout(function () {
                    $('#jingle_toast').hide();
                }, 1500);
            }
        });
        $('#container').on(
                'dragenter',
                function (e) {
                    e.preventDefault();
                    $('#container').addClass('draging');
                    e.stopPropagation();
                }
        ).on('drop', function (e) {
            e.preventDefault();
            $('#container').removeClass('draging');
            e.stopPropagation();
        }).on('dragleave', function (e) {
            e.preventDefault();
            $('#container').removeClass('draging');
            e.stopPropagation();
        }).on('dragover', function (e) {
            e.preventDefault();
            $('#container').addClass('draging');
            e.stopPropagation();
        });
        $('#show_code').on('click', function () {
            $('#myModal-code').modal();
            $('pre code').each(function (i, e) {
                hljs.highlightBlock(e);
            });
        });
        $('body').on('click', 'table button.btn', function () {
            $(this).parents('tr').next().toggle();
        });
        $('#up_load').on('click', function () {
            uploader.start();
        });
        var getRotate = function (url) {
            if (!url) {
                return 0;
            }
            var arr = url.split('/');
            for (var i = 0, len = arr.length; i < len; i++) {
                if (arr[i] === 'rotate') {
                    return parseInt(arr[i + 1], 10);
                }
            }
            return 0;
        };
        $('#myModal-img .modal-body-footer').find('a').on('click', function () {
            var img = $('#myModal-img').find('.modal-body img');
            var key = img.data('key');
            var oldUrl = img.attr('src');
            var originHeight = parseInt(img.data('h'), 10);
            var fopArr = [];
            var rotate = getRotate(oldUrl);
            if (!$(this).hasClass('no-disable-click')) {
                $(this).addClass('disabled').siblings().removeClass('disabled');
                if ($(this).data('imagemogr') !== 'no-rotate') {
                    fopArr.push({
                        'fop': 'imageMogr2',
                        'auto-orient': true,
                        'strip': true,
                        'rotate': rotate,
                        'format': 'png'
                    });
                }
            } else {
                $(this).siblings().removeClass('disabled');
                var imageMogr = $(this).data('imagemogr');
                if (imageMogr === 'left') {
                    rotate = rotate - 90 < 0 ? rotate + 270 : rotate - 90;
                } else if (imageMogr === 'right') {
                    rotate = rotate + 90 > 360 ? rotate - 270 : rotate + 90;
                }
                fopArr.push({
                    'fop': 'imageMogr2',
                    'auto-orient': true,
                    'strip': true,
                    'rotate': rotate,
                    'format': 'png'
                });
            }

            $('#myModal-img .modal-body-footer').find('a.disabled').each(function () {

                var watermark = $(this).data('watermark');
                var imageView = $(this).data('imageview');
                var imageMogr = $(this).data('imagemogr');
                if (watermark) {
                    fopArr.push({
                        fop: 'watermark',
                        mode: 1,
                        image: 'http://www.b1.qiniudn.com/images/logo-2.png',
                        dissolve: 100,
                        gravity: watermark,
                        dx: 100,
                        dy: 100
                    });
                }

                if (imageView) {
                    var height;
                    switch (imageView) {
                        case 'large':
                            height = originHeight;
                            break;
                        case 'middle':
                            height = originHeight * 0.5;
                            break;
                        case 'small':
                            height = originHeight * 0.1;
                            break;
                        default:
                            height = originHeight;
                            break;
                    }
                    fopArr.push({
                        fop: 'imageView2',
                        mode: 3,
                        h: parseInt(height, 10),
                        q: 100,
                        format: 'png'
                    });
                }

                if (imageMogr === 'no-rotate') {
                    fopArr.push({
                        'fop': 'imageMogr2',
                        'auto-orient': true,
                        'strip': true,
                        'rotate': 0,
                        'format': 'png'
                    });
                }
            });
            var newUrl = Qiniu.pipeline(fopArr, key);
            var newImg = new Image();
            img.attr('src', 'images/loading.gif');
            newImg.onload = function () {
                img.attr('src', newUrl);
                img.parent('a').attr('href', newUrl);
            };
            newImg.src = newUrl;
            return false;
        });
    }
});
