$(document).ready(function () {
 
    // clear search
    $('.search .clear-input').click(function () {
        var parent = $(this).parent();
        parent.find('input').val('').focus();
        parent.find('.result').html('');
    });
    // clear search
    $('.search .clear-result').click(function () {
        $(this).parent().find('.result').html('');
    });
    
    // chpu enable
    $('form input[name="chpu_enable"]').click(function(){
        var inp = $(this).parents('.form-group').find('input[name="chpu"]');
        if(!$(this).prop('checked')){
            inp.removeAttr('readonly');
        }else{
            inp.attr('readonly','readonly');
        }
    });


    // translite
    $.fn.translite = function (options) {
        var sett = {
            inputNameChpu: 'chpu',
            selectNameRazdel: false
        };
        var sett = $.extend({}, sett, options);

        return this.each(function () {
            var form = $(this).parents('form');
            var title = this;
            var chpuInp = form.find('input[name="' + sett.inputNameChpu + '"]');

            var translite = function (str) {
                var arr = {'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ж': 'g', 'з': 'z', 'и': 'i', 'й': 'y', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o', 'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'ы': 'i', 'э': 'e', 'А': 'A', 'Б': 'B', 'В': 'V', 'Г': 'G', 'Д': 'D', 'Е': 'E', 'Ж': 'G', 'З': 'Z', 'И': 'I', 'Й': 'Y', 'К': 'K', 'Л': 'L', 'М': 'M', 'Н': 'N', 'О': 'O', 'П': 'P', 'Р': 'R', 'С': 'S', 'Т': 'T', 'У': 'U', 'Ф': 'F', 'Ы': 'I', 'Э': 'E', 'ё': 'yo', 'х': 'h', 'ц': 'ts', 'ч': 'ch', 'ш': 'sh', 'щ': 'shch', 'ъ': '', 'ь': '', 'ю': 'yu', 'я': 'ya', 'Ё': 'YO', 'Х': 'H', 'Ц': 'TS', 'Ч': 'CH', 'Ш': 'SH', 'Щ': 'SHCH', 'Ъ': '', 'Ь': '',
                    'Ю': 'YU', 'Я': 'YA'};
                var replacer = function (a) {
                    return arr[a] || a;
                };
                str = str.replace(/[\s]/g, '-');
                str = str.replace(/[ьъЬЪ]/g, '');
                return str.replace(/[\S]/g, replacer);
            };

            var getTitle = function (e) {
                return $(e).val();
            };

            var run = function () {
                var str = '';

                str += getTitle(title);
                str = translite(str);
                chpuInp.val(str.toLowerCase());
            };

            $(title).on('keyup', function () {
                run();
            });

        });
    };


    // counter
    $.fn.counter = function (options) {
        var sett = {
            tabloClass: 'counter-tablo',
            cntAllClass: 'counter-tablo-cnt-all',
            cntBpClass: 'counter-tablo-cnt-bp',
            cntMaxLimitClass: '',
            cntMinLimitClass: '',
            maxLimit: 0,
            minLimit: 0,
            sbpPattern: ' '
        };
        var sett = $.extend({}, sett, options);

        return this.each(function () {
            $(this).after('<div class="' + sett.tabloClass + '" title="Всего/Без пробелов"></div>');
            var tablo = $(this).next('div.' + sett.tabloClass);

            $(this).keyup(function () {
                var text = $(this).val();
                var cnt = text.length;

                var pos = text.indexOf(sett.sbpPattern);
                for (var count = 0; pos !== -1; count++) {
                    pos = text.indexOf(sett.sbpPattern, pos + sett.sbpPattern.length);
                }

                var str = '<span class="' + sett.cntAllClass + '">' + cnt + '</span>';
                str += '/<span class="' + sett.cntBpClass + '">' + (cnt - count) + '</span> ';

                if (sett.maxLimit > 0) {
                    str += 'Осталось: ' + (sett.maxLimit - cnt);
                    //text = text.substring(0, sett.maxLimit);
                    $(this).val(text);
                }
                
                tablo.html(str);
            });

        });
    };

    $('textarea[name="content"]').counter({maxLimit: 30000});
    $('textarea[name="content_after"]').counter({maxLimit: 30000});
    $('textarea[name="description"]').counter({maxLimit: 160});

    // translite
    $('[data-translite="true"]').translite({inputNameChpu: 'chpu'});

    // tooltip
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    // validation form
    $('input[data-regexp]').on('keyup click change', function () {
        var vld = validationForm();
        vld.validateRegExp($(this));
    });

    // example
    $('span.sql-example').click(function () {
        var sql = $(this).data('sql');
        $(this).parents('.form-group').find('textarea').val(sql);
    });



    // custom-file chose
    $('.custom-file input[type="file"]').change(function () {
        var eto = $(this);
        var file = eto[0].files[0];
        eto.next('.custom-file-control').html(file.name);
    });

    // upload img
    $('.upload input[type="file"]').change(function () {
        ajaxUpload($(this));
    });


    ////////////
    // ajaxSetup
    $.ajaxSetup({
        type: "POST",
        dataType: "json"
    });


    // all ajax request
    $('input[data-type="ajax"]').on('keyup', function () {
        allAjax($(this));
    });
    $('a[data-type="ajax"]').on('click', function () {
        allAjax($(this));
        return false;
    });

});


// validate form
function validationForm() {
    var alert, value;
    function v() {}

    v.validateRegExp = function (e) {
        value = e.val();
        var fg = e.parents('.form-group');
        var rex = e.data('regexp');
        var res = value.search(rex);
        if (res === -1) {
            fg.addClass('has-danger');
        } else {
            fg.removeClass('has-danger');
        }
    };

    return v;
};

function allAjax(eto) {
    var fnc = loader();
    var data = eto.data();
    var respBA = response;

    respBA.url = eto.attr('href');
    respBA.data = data;
    respBA.element = eto;
    if (data.before) {
        respBA[data.before]();
    }
    if (respBA.stop) {
        return false;
    }

    fnc.loader(eto);
    $.ajax({
        url: respBA.url,
        data: respBA.data,
        success: function (result) {
            respBA.resp = result;
            fnc.setData(result);
            fnc.unloader();
            respBA[data.after ? data.after : 'Default']();
        },
        error: function (error) {
            //response.error(error);
        }
    });

    return false;
};

function ajaxUpload(eto){
    var params = eto.data();
    var data = new FormData();
    var parent = eto.parents('.upload');
    var progressBar = parent.find('progress');
    var respBA = respUpload;
    var file = eto[0].files[0];
    if (!file) {
        return false;
    }
    
    respBA.element = eto;

    parent.find('.resp').removeAttr('class').addClass('resp').html('');
    //parent.find('.custom-file-control').html(file.name);

    progressBar.removeClass('hidden-xs-up progress-danger progress-success');
    progressBar.val(0);

    data.append('file', file);
    var key;
    for (key in params) {
        data.append(key, params[key]);
    }

    $.ajax({
        type: "POST",
        url: params.url,
        data: data,
        processData: false,
        contentType: false,
        // progress
        xhr: function () {
            var xhr = $.ajaxSettings.xhr();
            xhr.upload.addEventListener('progress', function (evt) {
                if (evt.lengthComputable) { // если известно количество байт
                    // высчитываем процент загруженного
                    var percentComplete = Math.ceil(evt.loaded / evt.total * 100);
                    progressBar.val(percentComplete).text('Загружено ' + percentComplete + '%');
//                        if(percentComplete === 100){
//                            progressBar.addClass('progress-success');
//                        }
                }
            }, false);
            return xhr;
        },
        success: function (res) {
            respBA.resp = res;
            
            if (res.error === false) {
                respBA[params.after ? params.after : 'Default']();
                progressBar.addClass('progress-success');
                
            }
            if (res.error === true) {
                progressBar.addClass('progress-danger').removeClass('progress-success');
                parent.find('.resp').addClass('alert alert-danger').html(res.alert.msg);
            }

        }
    });
};

function loader() {
    var alert, tooltip, data, msg, oldText, loaderClass = 'loader';
    function r() {}

    // крутяшка
    r.loader = function (e) {
        if (!e.data('alert')) {
            tooltip = true;
            alert = e;
        } else {
            alert = $(e.data('alert'));
        }
        oldText = alert.html();
        alert.html('<span class="' + loaderClass + ' fa fa-cog fa-spin"></span>');
    };

    // не крутяшка
    r.unloader = function () {
        var loader = alert.find('.' + loaderClass).removeAttr('class');
        msg = data.alert ? data.alert.msg : '';
        if (tooltip && msg) {
            var attr = {'data-toggle': "tooltip", 'data-placement': "top", 'title': msg};
            var dt = loader.attr(attr).tooltip('show').detach();
            alert.html(oldText).after(dt);
            setTimeout(function () {
                dt.tooltip('dispose').remove();
            }, 1300);
        } else {
            if (data.error) {
                $(alert).addClass('text-danger');
            }
            $(alert).html(msg);
        }

    };

    // данные ответа
    r.setData = function (d) {
        data = d;
    };

    return r;
};

function respUpload(){};
function response() {};

// upload
response.Default = function () {
    console.log(this.resp);
};
respUpload.aTizerUpload = function(){
    this.element.parents('form').find('.tizer-label img').attr({'src': '/img/news/' + this.resp.tizer + '?' + Math.random()});
    this.element.parents('form').find('.resp').html(this.resp.alert);
};



// allAjax
response.Test = function () {
    this.data.test = 'test';
    this.element.removeClass('text-warning');
    console.log('test', this.element);
};
response.Test2 = function () {
    console.log('test2', this.resp);
};
response.Default = function () {
    console.log(this.resp);
};
response.error = function () {
    //$(this.alert).html('Ошибка запроса');
    console.log(this.resp);
};


// 
response.newsHide = function () {
    var icon = this.element.find('span');
    var hide = this.resp.hide;
    var fa = {0: 'fa fa-toggle-off text-muted', 1: 'fa fa-toggle-on text-danger'};
    icon.removeAttr('class');
    if (!this.resp.error) {
        icon.addClass(fa[hide]);
        this.element.data('hide', hide).attr('data-hide', hide);
    }
    if (this.resp.error === true) {
        console.log('error');
    }
};


// удаление
response.removeConfirm = function () {
    this.stop = false;
    if (!confirm('Удалить без возможности восстановления?')) {
        this.stop = true;
    }
};
response.remove = function () {
    if (!this.resp.error) {
        this.element.html('').parents(this.data.parent).slideUp(600);
    }
};

// move Up Down
response.moveRank = function () {
    var next = this.element.parents('li').next();
    var prev = this.element.parents('li').prev();
    var li = this.element.parents('li').detach();
    if (this.element.data('direct') === 'down') {
        next.after(li);
    } else {
        prev.before(li);
    }
};

// search page, currency
response.bSearch = function () {
    this.stop = false;
    this.data.search = this.element.val();
    this.url = this.data.url;
    if (this.data.search.length < 3) {
        this.element.next().html('');
        this.stop = true;
    }
};
// check chpu
response.bSearchChpu = function () {
    var val = this.element.val();
    if (val.length < 1) {
        this.stop = true;
    }
    this.url = this.data.url + '/' + val;
};
response.aSearch = function () {
    var result = this.element.parents('.search').find('.result');
    result.customScroll('destroy');
    var rows = this.resp.rows, link = '', cnt = 0;
    if (rows && rows.length > 0) {
        $.each(rows, function (i, row) {
            cnt = i;
            link += '<li class="list-group-item"><a href="' + row.chpu + '">' + row.title + '</a></li>';
        });
    } else {
        link = '<li class="list-group-item">Не найдено</li>';
    }
    this.element.next().html('<ul class="list-group">' + link + '</ul>');
    if (cnt > 10) {
        result.customScroll({horizontal: false});
    }
};
response.aSearchRate = function () {
    var result = this.element.parents('.search').find('.result');
    result.customScroll('destroy');
    var rows = this.resp.rows, link = '', cnt = 0;
    if (rows && rows.length > 0) {
        $.each(rows, function (i, row) {
            cnt = i;
            link += '<li class="list-group-item pointer">' + row.title + '</li>';
        });
    } else {
        link = '<li class="list-group-item">Не найдено</li>';
    }
    this.element.next().html('<ul class="list-group rate-name">' + link + '</ul>');
    if (cnt > 10) {
        result.customScroll({horizontal: false});
    }
};


// check chpu
function Bookmark() {
    if (document.all && !window.opera) {
        if (typeof window.external == "object") {
            window.external.AddFavorite(document.location, document.title);
            return false;
        } else
            return false;
    } else {
        var ua = navigator.userAgent.toLowerCase();
        var isWebkit = (ua.indexOf('webkit') != -1);
        var isMac = (ua.indexOf('mac') != -1);

        if (isWebkit || isMac) {
            alert('Нажмите "' + (isMac ? 'Command/Cmd' : 'CTRL') + ' + D" для добавления сайта в закладки');

            return false;
        } else {
            x.href = document.location;
            x.title = document.title;
            x.rel = "sidebar";
            return true;
        }
    }
};