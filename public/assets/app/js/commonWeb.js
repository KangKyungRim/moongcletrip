/** 전역변수 용 오브젝트 */
var Global = {};

$(function() {
    _.templateSettings = {
		interpolate : /\<\@\=(.+?)\@\>/gim,
		evaluate    : /\<\@(.+?)\@\>/gim,
		escape      : /\<\@\-(.+?)\@\>/gim
	};
});

/** 유니크 ID 플러그인 */
	$.fn.extend({
		uniqueId: ( function() {
			var uuid = 0;
			return function() {

				return this.each(function() {
					if (!this.id) {
						this.id = 'uid-' + ( ++ uuid );
					}
				});
			};
		} )()
	});
	
	/** form -> json */
	$.fn.serializeObject = function() {
		var obj;
		
		try {
			var tagName = this.tagName();
			if (tagName && tagName.toUpperCase() === 'FORM') {
				
				var arr = this.serializeArray();
				if (arr) {
					
					obj = {};
					$.each(arr, function() {
						obj[this.name] = this.value;
					});
				}
			}
		} catch(e) {
			console.error(e);
		}
		
		return obj;
	};
	
	/** 태그 명 가져오기 플러그인 */
	$.fn.tagName = function() { return this.prop('tagName') };
	/** 아이디 가져오기 플로그인 */
	$.fn.id = function() { return this.attr('id') };
	/** 보임 여부 */
	$.fn.isShow = function() { return this.is(':visible') };
	/** disabled 플러그인 */
	$.fn.disabled = function(isDisabled) {if (isDisabled === undefined) return this.prop('disabled', true).addClass('disabled'); else return this.prop('disabled', isDisabled).addClass(isDisabled ? 'disabled' : '').removeClass(isDisabled ? '' : 'disabled'); };
	$.fn.enabled = function(isEnabled) { if (isEnabled === undefined) return this.prop('disabled', false).removeClass('disabled'); else return this.prop('disabled', !isEnabled).addClass(isEnabled ? '' : 'disabled').removeClass(isEnabled ? 'disabled' : ''); };
	/** checked 플러그인 */
	$.fn.checked = function(isChecked) { return this.prop('checked', isChecked === undefined ? true : isChecked); };
	/** readonly 플러그인 */
	$.fn.readonly = function(isReadonly) {if (isReadonly === undefined) return this.prop('readonly', true).addClass('readonly'); else return this.prop('readonly', isReadonly).addClass(isReadonly ? 'readonly' : '').removeClass(isReadonly ? '' : 'readonly');};
	/** jquery 인스턴스 존재여부 확인 */
	$.fn.exists = function() { return this.length > 0 };
	/** jquery 자식을 가지고 있는 지 여부 존재여부 확인 */
	$.fn.hasChild = function() { return this.children().length > 0 };
	$.fn.notChild = function() { return this.children().length <= 0 };
	/** jquery 클래스 추가/삭제 */
	$.fn.addClassEx = function(className) { return (!this.hasClass(className)) ? this.addClass(className) : this };
	$.fn.removeClassEx = function(className) { return (this.hasClass(className))  ? this.removeClass(className) : this };
	/** jquery 객체 offset 값 조회 */
	$.fn.offsetTop    = function() { var offset = this.offset(); return offset && offset.top };
	$.fn.offsetLeft   = function() { var offset = this.offset(); return offset && offset.left };
	$.fn.offsetBottom = function() { var top = this.offsetTop();   return isNaN(top)? undefined : top + this.outerHeight() };
	$.fn.offsetRight  = function() { var left = this.offsetLeft(); return isNaN(left)? undefined : left + this.outerWidth() };
	$.fn.posTop    = function() { var pos = this.position(); return pos && pos.top };
	$.fn.posLeft   = function() { var pos = this.position(); return pos && pos.left };
	$.fn.posBottom = function() { var top = this.posTop();   return isNaN(top)? undefined : top + this.outerHeight() };
	$.fn.posRight  = function() { var left = this.posLeft(); return isNaN(left)? undefined : left + this.outerWidth() };
	/** jquey val() 이 null 이면 빈값 또는 기본값 */
	$.fn.valEx = function() { return Comm.getOr(this.val(), Comm.getOrEmpty(arguments[0])) };
	/** jquery data() 를 문자열로 반환 */
	$.fn.dataString = function(name) { var data = this.data(name); return data == null ? data : String(data); };
	/** 텍스트 입력창 엔터 키 이벤트 바인딩 */
	$.fn.onEnterKey = function(callback) {
	    return this.each(function(index, target) {
	          $(target).off('keypress.enterkey').on('keypress.enterkey', function(e) {
					if (e.keyCode === 13) {
						if (typeof callback === 'function') {
							callback();
						}
					}
				});    });
	};

	/** Object에 valuer값이
	 * 0, false, '', null, undefined 와 같으면
	 * 해당 Key, Value 삭제 */
	_.mixin({
		compactObject : function(o) {
			_.each(o, function(v, k) {
				if(!v) {
					delete o[k];
				}
			});
			return o;
		}
	});

function getParameter(param) {    
	var requestParam = '';

	var url = unescape(location.href);

	var exsistsParam = _.contains(url, '=');

	if (exsistsParam) {
		var paramArr = (url.substring(url.indexOf('?') + 1, url.length)).split('&');

		for(var i=0; i < paramArr.length; i++) {
			var temp = paramArr[i].split('=');

			if (temp[0].toUpperCase() == param.toUpperCase()) {
				requestParam = paramArr[i].split('=')[1];
				break;
			}
		}
	}

	return requestParam;
}

/** 숫자 외에는 삭제한다 */
function onlyNum(str) {
	if (typeof str !== 'string') return str;

	return str.replace(/\D/g, '');
}

/** 공통 포메터 오브젝트 */
var Fmt = {
    /** yyyymmdd -> yyyy-mm-dd */
    dateMo : function(str) {
        if (_.isEmpty(str)) return '';
        if (str.length != 8) return str;

        return str.substring(0, 4)
        + '-'
        + str.substring(4, 6)
        + '-'
        + str.substring(6, 8);
    },

    /** yyyymmdd -> yyyy.mm.dd or format */
    date : function(str, format) {
        if (_.isEmpty(str)) return '';
        if (str.length != 8) {

            str = onlyNum(str);
            if (str.length != 8) {
                return str;
            }
        }

        if (format === undefined) {
            return str.substring(0, 4)
                    + '.'
                    + str.substring(4, 6)
                    + '.'
                    + str.substring(6, 8);
        } else {
            return moment(str, 'YYYYMMDD').format(format);
        }
    },

    /** yyyymmdd
     *  yyyymmdd
     * -> yyyy.mm.dd ~ yyyy.mm.dd */
    date2 : function(str, str2) {
        if (_.isEmpty(str)) str = '';
        if (_.isEmpty(str2)) str2 = '';

        var rtn = '';
        str = onlyNum(str);
        if (str.length != 8) {
            rtn = str;
        } else {
            rtn = str.substring(0, 4)
                + '.'
                + str.substring(4, 6)
                + '.'
                + str.substring(6, 8);
        }

        if (! _.isEmpty(str)) {
            rtn = rtn + ' ~ ';
        }

        str2 = onlyNum(str2);
        if (str2.length != 8) {
            rtn = rtn + str2;
        } else {
            rtn = rtn
            + str2.substring(0, 4)
            + '.'
            + str2.substring(4, 6)
            + '.'
            + str2.substring(6, 8);
        }

        return rtn;

    },

    /**
     * yyyymmddhhmi -> yyyy.mm.dd hh:mi
     * yyyymmddhhmiss -> yyyy.mm.dd hh:mi:ss
     */
    dateTime : function(input, toLocal = true) {
        if (!input) return '';

        // 1) 순수 숫자 yyyymmddhhmi[ss]
        const s = String(input).trim();
        if (/^\d{12,14}$/.test(s)) {
            const y = s.slice(0,4), M = s.slice(4,6), d = s.slice(6,8);
            const H = s.slice(8,10), m = s.slice(10,12);
            const hasSec = s.length === 14;
            const sec = hasSec ? ':' + s.slice(12,14) : '';
            return `${y}.${M}.${d} ${H}:${m}${sec}`;
        }

        // 2) ISO 계열 처리 (마이크로초 → 밀리초로 정규화)
        let iso = s
            .replace(' ', 'T')                         // ' ' → 'T'
            .replace(/(\.\d{3})\d+(Z|[+\-]\d{2}:\d{2})$/, '$1$2'); // .000000Z → .000Z

        let d = new Date(iso);
        if (isNaN(d)) {
            // 타임존 표기가 없을 때 최소한 파싱 시도
            if (/^\d{4}-\d{2}-\d{2}T?\d{2}:\d{2}:\d{2}$/.test(iso)) {
            d = new Date(iso.replace(' ', 'T') + 'Z');
            }
            if (isNaN(d)) return input; // 그래도 안 되면 원문 반환
        }

        const pad = (n) => String(n).padStart(2, '0');

        let y, M, dd, H, mm, ss;
        if (toLocal) {
            y  = d.getFullYear();
            M  = pad(d.getMonth() + 1);
            dd = pad(d.getDate());
            H  = pad(d.getHours());
            mm = pad(d.getMinutes());
            ss = pad(d.getSeconds());
        } else {
            y  = d.getUTCFullYear();
            M  = pad(d.getUTCMonth() + 1);
            dd = pad(d.getUTCDate());
            H  = pad(d.getUTCHours());
            mm = pad(d.getUTCMinutes());
            ss = pad(d.getUTCSeconds());
        }

        return `${y}.${M}.${dd} ${H}:${mm}:${ss}`;

    },
};

/** 웹 date 오브젝트 */
var Dates = {
		_defaultFormat : 'YYYY.MM.DD',

		/**
		 * 현재 시간을 format 에 맞게 변환하여 반환한다
		 * @param format 기본값 YYYY.MM.DD
		 * @returns 현재시간 문자열
		 */
		now : function(format) {
			var _format = (_.isEmpty(format)) ? Dates._defaultFormat : format;

			return moment().format(_format);
		},
		
		addSecondByInput : function(dt, nSeconds, outputFormat) {
			var _format = (_.isEmpty(outputFormat)) ? Dates._defaultFormat : outputFormat;
			return moment(dt.replace(/\./g, '-')).add(nSeconds, 'seconds').format(_format);
		},
		
		addMinutes : function(nMin, outputFormat) {
			var _format = (_.isEmpty(outputFormat)) ? Dates._defaultFormat : outputFormat;

			return moment().add(nMin, 'minutes').format(_format);
		}, 

		addDay : function(nDays, outputFormat) {
			var _format = (_.isEmpty(outputFormat)) ? Dates._defaultFormat : outputFormat;

			return moment().add(nDays, 'days').format(_format);
		},

		addDayByInput : function(dt, nDays, outputFormat) {
			var _format = (_.isEmpty(outputFormat)) ? Dates._defaultFormat : outputFormat;

			return moment(dt.replace(/\./g, '-')).add(nDays, 'days').format(_format);
		} ,
		
		addMonthByInput : function(dt, nMonths, outputFormat) {
			var _format = (_.isEmpty(outputFormat)) ? Dates._defaultFormat : outputFormat;

			return moment(dt.replace(/\./g, '-')).add(nMonths, 'months').format(_format);
		} ,

		addYear : function(nYears, outputFormat) {
			var _format = (_.isEmpty(outputFormat)) ? Dates._defaultFormat : outputFormat;

			return moment().add(nYears, 'years').format(_format);
		},

		addYearByInput : function(dt, nYears, outputFormat) {
			var _format = (_.isEmpty(outputFormat)) ? Dates._defaultFormat : outputFormat;

			return moment(dt.replace(/\./g, '-')).add(nYears, 'years').format(_format);
		},

		isAfterNow : function(dayString, inputFormat) {
			var _format = (_.isEmpty(inputFormat)) ? Dates._defaultFormat : inputFormat;

			return moment(dayString, inputFormat).isAfter(moment(moment().format(_format), _format));
		},

		isAfter : function(date1, date2, inputFormat) {
			var _format = (_.isEmpty(inputFormat)) ? Dates._defaultFormat : inputFormat;

			return moment(date1, _format).isAfter( moment(date2, _format) );
		},

		isBeforeNow : function(dayString, inputFormat) {
			var _format = (_.isEmpty(inputFormat)) ? Dates._defaultFormat : inputFormat;

			return moment(dayString, inputFormat).isBefore(moment(moment().format(_format), _format));
		},

		isBefore : function(date1, date2, inputFormat) {
			var _format = (_.isEmpty(inputFormat)) ? Dates._defaultFormat : inputFormat;

			return moment(date1, _format).isBefore( moment(date2, _format) );
		},

		isEqualOrBefore : function(date1, date2, inputFormat) {
			var _format = (_.isEmpty(inputFormat)) ? Dates._defaultFormat : inputFormat;

			return moment(date1, _format).isSameOrBefore( moment(date2, _format) );
		},
		
		isEqualOrAfter : function(date1, date2, inputFormat) {
			var _format = (_.isEmpty(inputFormat)) ? Dates._defaultFormat : inputFormat;

			return moment(date1, _format).isSameOrAfter( moment(date2, _format) );
		},

		/** date2와 date1 의 일자를 계산하여 반환 */
		betweenDays : function(date1, date2, inputFormat) {
			var _format = (_.isEmpty(inputFormat)) ? Dates._defaultFormat : inputFormat;

			return moment(date2, _format).diff(moment(date1, _format), 'days');
		},

		/** date2와 date1 의 달(month)을 계산하여 반환 */
		betweenMonths : function(date1, date2, inputFormat) {
			var _format = (_.isEmpty(inputFormat)) ? Dates._defaultFormat : inputFormat;

			return moment(date2, _format).diff(moment(date1, _format), 'month');
		},

		/** date2와 date1 의 달(month)을 계산하여 반환 */
		betweenYears : function(date1, date2, inputFormat) {
			var _format = (_.isEmpty(inputFormat)) ? Dates._defaultFormat : inputFormat;

			return moment(date2, _format).diff(moment(date1, _format), 'year');
		},
		
		/** date2와 date1 의 초(seconds)을 계산하여 반환 */
		betweenSeconds : function(date1, date2, inputFormat) {
			var _format = (_.isEmpty(inputFormat)) ? Dates._defaultFormat : inputFormat;

			return moment(date2, _format).diff(moment(date1, _format), 'seconds');
		},

		/** 날짜 형태 맞는지 **/
		isValid : function(date) {

			return moment(date.replace(/\./g, '-')).isValid();
		},
		
		isTimeVaild : function(time) {
			
			return moment('20190101 '+ time, 'YYYYMMDD HHmm').isValid();
		},
		
		isDateTimeValid : function(dateTime) {
			return moment(dateTime, 'YYYYMMDDHHmm').isValid();
		}
};

var Popup = {

    init: function(selector) {
        var $popup = $(selector);

        if (!$popup.data('inited')) {

            $popup.children('div').off('click').on('click', function(e) {
                e.stopPropagation();
            });

            $popup.find('.popup-close-action').off('click').on('click', function() {
                Popup.close($popup);
            });

            $popup.data('inited', true);
        }
    },

    /**
     * events {
     *    param : 파라미터
     *    ready : function(openAction) - 팝업 오픈 전에 호출되며 등록 시 openAction 을 수행해줘야만 팝업이 오픈된다
     *    beforeOpen: function(param) 팝업 오픈 전 호출
     *    startOpen: function(param) 팝업 오픈 시작(fadeIn) 후 호출
     *    afterOpen: function(param) 팝업 오픈 후 호출
     *    beforeClose: function(result) 팝업 클로즈 전 호출
     *    afterClose: function(result) 팝업 클로즈 후 호출
     * }
     */
    bindEvent: function(selector, events) {
        var $pop = $(selector);

        if (typeof events === 'object') {

            $pop.data('param', events.param);

            delete events.param;

            var orgEvents = $pop.data('events');

            $pop.data('events', $.extend(orgEvents, events));
        }

        return $pop;
    },

    open: function(selector, events) {
        var $popup = Popup.bindEvent(selector, events);
        Popup.init($popup);


        $(':focus').addClass('lastFocus');

        $popup.removeData('value');
        var param = $popup.data('param');

        var zIndex = _.max(
            $('.popup:visible').map(function() {
                var zIdx = $(this).css('z-index');
                return isNaN(zIdx) ? 0 : Number(zIdx);
            }));

        zIndex++;

        if (zIndex < 10000) zIndex = 10000;

        var evt = $popup.data('events');

        var openAction = function() {
            if (_.isObject(evt) && _.isFunction(evt.beforeOpen)) {
                evt.beforeOpen.call($popup.get(0), param);
            }

            $popup
                .attr("tabindex", "0")
                .css('z-index', String(zIndex))
                .fadeIn(function() {
                    this.focus();
                    if (_.isObject(evt) && _.isFunction(evt.afterOpen)) {
                        evt.afterOpen.call($popup.get(0), param);
                    }
                });

            if (_.isObject(evt) && _.isFunction(evt.startOpen)) {
                evt.startOpen.call($popup.get(0), param);
            }
        };

        if (_.isObject(evt) && _.isFunction(evt.ready)) {
            evt.ready.call($popup.get(0), openAction);
        } else {
            openAction();
        }

    },


    close: function(selector, value) {
        var $popup = $(selector);


        var evt = $popup.data('events');
        if (_.isObject(evt) && _.isFunction(evt.beforeClose)) {
            evt.beforeClose.call($popup.get(0), value);
        }

        if (!$popup.is(':hidden')) {

            $popup.fadeOut(function() {

                $('.lastFocus').removeClass('lastFocus').focus();

                if (_.isObject(evt) && _.isFunction(evt.afterClose)) {
                    evt.afterClose.call($popup.get(0), value);
                }
            });

        } else {
            if (_.isObject(evt) && _.isFunction(evt.afterClose)) {
                evt.afterClose.call($popup.get(0), value);
            }
        }
    }
};

/**
 * 웹 공통 alert 메시지
 */
var Msg  = {

		warn : function(message, callback) {
			Msg._show({
				message: message
			},callback);
		},

		info: function(message, callback) {
			Msg._show({
				message: message
			}, callback);
		},

		confirm: function(message, callback) {
			
			Msg._show({
				message: message,
				msgType: 'confirm'
			},callback);
			
		},
		
		_show: function(htmlOpts, callback) {
			
			var $msg = $(Msg.getHtml(htmlOpts)).appendTo('#mobileWrap');
			Msg.bindEvent($msg, callback);
		},


		/**
		* @param opts
		* {
		*   title: '제목'
		*   message: '내용'
		*   okButtonText: '확인'
		*   cancelButtonText: '취소'
		*   msgType: 'alert' or 'confirm'
		* }
		*/
		getHtml: function(opts) {
			opts = $.extend({
				title: '알림',
				message: '',
				okButtonText: '확인',
				cancelButtonText: '취소',
				msgType: 'alert'
			}, opts);

            var html = '';
			html += '<div class="layerpop__wrap show type-alert">';
			html += '	<div class="layerpop__container">';
			html += '		<div class="layerpop__contents">';
			html += '			<div class="tit__wrap">';
			html += '				<p class="title"><@= title @></p>';
			html += '				<p class="desc"><@= message @></p>';
			html += '			</div>';
			html += '		</div>';
			html += '		<div class="layerpop__footer">';
			html += '			<div class="btn__wrap">';
			html += '			  <@ if (msgType === "confirm") { @>';
			html += '                <button class="btn-full__secondary btn-negative"><@= cancelButtonText @></button>';
			html += '			  <@ } @>';
			html += '				<button class="btn-full__primary btn-mint"><@= okButtonText @></button>';
			html += '			</div>';
			html += '		</div>';
			html += '	</div>';
			html += '	<div class="dim" aria-hidden="true"></div>';
			html += '</div>';

			return _.template(html)(opts);
		},
		
		bindEvent: function($alertBox, callback) {
			var closeAction = function() {
				$alertBox.fadeOut(function() {

					$alertBox.remove();
				});
			};
			
			$alertBox.find('.btn-mint').on('click', function() {
				closeAction();
				
				if (typeof callback === 'function') {
					callback(true);
				}
				
			});
			
			$alertBox.find('.btn-negative').on('click', function() {
				closeAction();
				
				if (typeof callback === 'function') {
					callback(false);
				}
				
			});
						
		}
};


/**
 * 페이징
 * new Page({
	  pageRows    : 10,
	  currPage    : 28,
	  totalRows   : 290
	}).html(5)
 */
function Page(pageJson, callbackId, fnCallback) {
	var self = this;

	pageJson = $.extend({
		pageRows  : 0,
		currPage  : 1,
		totalRows : 0
	}, pageJson);

	self.pageRows    = Number(pageJson.pageRows);
	self.currPage    = Number(pageJson.currPage);
	self.totalRows   = Number(pageJson.totalRows);

	var totalPageCount = Math.floor( self.totalRows / self.pageRows );
	if ((self.totalRows % self.pageRows) > 0) totalPageCount += 1;
	self.totalPageCount = totalPageCount;

	var callback;

	if ( _.isEmpty(callbackId) && typeof callback !== 'function' ) {
		callback = function() { return 'void(0)'; }
	} else {
		Pages.registerCallback(callbackId, fnCallback);
		callback = function(active, page) {
			return active !== 'active'? 'void(0)' : "Pages.invokeCallback('" + callbackId + "', " + page + ")";
		}
	}


	self.html = function(pageNaviCnt, opts) {

		if (self.totalRows <= 0) return '';

		pageNaviCnt  = typeof pageNaviCnt === 'number'? pageNaviCnt : 10;

		var firstPage = Math.floor( self.currPage / pageNaviCnt );
		if ((self.currPage % pageNaviCnt) === 0) firstPage -= 1;
		firstPage = firstPage * pageNaviCnt + 1;
		var endPage = firstPage + pageNaviCnt - 1;
		if (endPage > self.totalPageCount) endPage = self.totalPageCount;
		var lastPage = self.totalPageCount;

		var prevPage = firstPage - pageNaviCnt;
		if (prevPage < 1) prevPage = 1;

		var nextPage = endPage + 1;
		if (nextPage > lastPage) nextPage = lastPage;

		//console.log('self', self);
		//console.log('totalPageCount', self.totalPageCount);
		//console.log('firstPage', firstPage);
		//console.log('endPage', endPage);

		var activePrevArrow = firstPage > 1 ? 'active' : '';
		var activeNextArrow = endPage < self.totalPageCount ? 'active' : '';

		var html = '';

		if(self.currPage < self.totalPageCount) {
			//html += '<a href="javascript:'+callback(self.currPage +1)+'" class="btn_more mt20 color1" role="link">더보기</a>'
			html += '<li class="liPageBox more"><button onclick="'+callback(self.currPage +1)+'" class="btn btn-white w100p">더보기</button></li>';
		}
		
		return html;
	};
}



var Pages = {

		callbacks : {},


		registerCallback : function(pageId, callback) {

			Pages.callbacks[pageId] = callback;

			return Pages;
		},

		invokeCallback : function(pageId, page) {
			var callback = Pages.callbacks[pageId];
			if (typeof callback === 'function') {
				callback(page);
			} else {
				console.warn("Not registered page callback('" + pageId + "')");
			}
		},


		getRowNum : function(pageObj, index) {
			return pageObj.totalRows - ((pageObj.currPage-1) * pageObj.pageRows) - index;
		}
};