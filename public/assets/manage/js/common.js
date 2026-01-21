/** 공통 함수 오브젝트 */
var Comm = {
    
    regexSpWord: /[`~!@#$%^&*|\\\'\";:\/?]/gi,
		
    url  : function(aUrl) {
        return aUrl;
    },

    /** 페이지 이동 */
    goTo : function(url) {
        location.href = url;
    },

    left : function(str, length) {
        return (typeof str !== 'string') ? str : str.left(length);
    },
    right : function(str, length) {
        return (typeof str !== 'string') ? str : str.right(length);
    },
    
    isOpenLoading : function() {

        var $loading = $('#loading');

        if ($loading.length <= 0) {
            
            var html = '';
            html += '<div id="loading" class="loading">';
            html += '    <div class="mask"></div>';
            html += '    <div class="loading-box">';
            html += '        <div class="loading-car ld ld-rush-ltr x2"></div>';
            html += '    </div>';
            html += '</div>';

            $(html).appendTo('body');

            return false;
        } else {
            return $loading.is(':visible');
        }
    },

    openLoadingCnt : 0,

    openLoading : function() {
        Comm.openLoadingCnt++;
        
        console.log('openLoading() - ' + Comm.openLoadingCnt);

        if (Comm.openLoadingCnt === 1 && !Comm.isOpenLoading()) {
            console.log('openLoading() -> open');
            $('#loading').stop().fadeIn(100);
        }
    },

    closeLoading : function() {
        Comm.openLoadingCnt--;
        if (Comm.openLoadingCnt < 0) Comm.openLoadingCnt = 0;

        console.log('closeLoading() - ' + Comm.openLoadingCnt);

        if (Comm.openLoadingCnt === 0 && Comm.isOpenLoading()) {
            console.log('closeLoading() -> close');
            $('#loading').stop().fadeOut(100);
        }
    } ,
};
/** 웹 API 오브젝트 */
var Api = {

    /**
     * 웹 JSON API 를 호출한다
     * @param opts <pre>
     * {
     *    url      : string
     *    data     : body json
     *    loading  : true/false
     *    success  : function(resultJson)
     *    failure  : function(resultJson)
     *    error    : function()
     *    complete : function()
     * }
     * </pre>
     */
    call : function(opts) {

        if (Api.call.callId === undefined) {
            Api.call.callId = 0;
        }

        var callId = ++Api.call.callId;
        var logTag = "[API-" + callId + "] ";
        console.log(logTag + "==[ Start ]=============");

        opts = $.extend({
            loading : true
        }, opts);

        if (opts.loading) Comm.openLoading();

        console.log(logTag + "Request url - " + opts.url);

        var ajaxParams = {
            type : 'POST',
            url  : opts.url,
            contentType : 'application/json;charset=utf-8',
            dataType    : 'json'
        };

        if (typeof opts.data === 'object') {
            var data = JSON.stringify(opts.data);
            console.log(logTag + "Request params - " + data);
            ajaxParams.data = data;
        }

        ajaxParams.success = function(resultJson, textStatus, xhr) {
            if (window.logonTimer) logonTimer.resetTime();
            
            var header = resultJson.header;
            if (header.success) {
                console.log(logTag + "Response : success - ", resultJson);

                if (_.isFunction(opts.success)) {
                    try {
                        opts.success(resultJson);
                    } catch(e) {
                        console.error(e);
                        Msg.warn('결과를 처리 중 오류가 발생하였습니다');
                    }
                }

            } else {
                console.log(logTag + "Response : failure - ", resultJson);

                if (!_.isFunction(opts.failure)) {

                    Api.callFailureAction(resultJson, textStatus, xhr);
                } else {
                    opts.failure(resultJson);
                }
            }

            if (_.isFunction(opts.complete)) {
                opts.complete();
            }

            if (opts.loading) Comm.closeLoading();
        };

        ajaxParams.error = function(xhr, textStatus, errorThrown) {
            console.log(logTag + "Response : Error - ", xhr, textStatus, errorThrown);
            if (opts.loading) Comm.closeLoading();

            try { 
                var json = JSON.parse(xhr.responseText);
                
                if (!_.isFunction(opts.error)) {

                    Msg.warn(json.header.message, function() {
                        if (json.header.forwardUrl) {
                            location.href = json.header.forwardUrl;
                        }
                    });
                    
                } else {
                    opts.error(json);
                }
            } catch(e) {

                var json = {
                        header: {
                            result: '0',
                            message: '시스템 에러가 발생하였습니다'
                        }
                };
                
                if (!_.isFunction(opts.error)) {

                    Msg.warn(json.header.message);
                } else {
                    opts.error(json);
                }
                
            }
            
            
            
            if (_.isFunction(opts.complete)) {
                opts.complete();
            }
        };

        ajaxParams.complete = function(xhr) {
            console.log(logTag + "Transaction-id : " + xhr.getResponseHeader('Transaction-id'));
            console.log(logTag + "Execution-time  : " + xhr.getResponseHeader('Execution-time') + 'ms');
            console.log(logTag + "===============[ End ]==");
        };


        $.ajax(ajaxParams);
    },
    
    callFailureAction : function(resultJson, textStatus, xhr) {
        
        Msg.warn(resultJson.header.message, function() {
            if (resultJson.header.forwardUrl) {
                location.href = resultJson.header.forwardUrl;
            }
        });
    },
		
};

/** 템플릿 함수 오브젝트 */
var Tmpl = {
		
		getTmpl : function(sel) {
			return _.template($(sel).html());
		},
		
		createHtml : function(tmplSelector, data) {
			var _tmpl = Tmpl.getTmpl(tmplSelector);
			
			return _tmpl(data);
		},

		insert : function(container, tmplSelector, data) {

			var _tmpl = Tmpl.getTmpl(tmplSelector);
			var $container = $(container).empty();

			return $(_tmpl(data)).appendTo($container);
		},

		append : function(container, tmplSelector, data) {

			var _tmpl = Tmpl.getTmpl(tmplSelector);

			return $(_tmpl(data)).appendTo(container);
		},
		
		replace : function(target, tmplSelector, data) {

			var _tmpl = Tmpl.getTmpl(tmplSelector);
			
			return $(target).replaceWith(_tmpl(data));
		}
};
