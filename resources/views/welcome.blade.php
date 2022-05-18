@extends("layouts.basic")
@section('content')

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/js/moment.js"></script>
    <link href="/css/fullCalender2.css?var=6" rel="stylesheet">
    <body>
    <div id="warp">
        <!-- 레이어 -->
        <div id="regist-layer-background"></div>
        <div id="regist-layer">
            <div>
                <h6>시간 설정 [<span id="click-date"></span>]</h6>
                <div id="layer_close"><i class="fas fa-times-circle"></i></div>
            </div>
            <div>
                <input type="button" class="btn btn-danger time-controll" value="-" id="prve" /> &nbsp;
                <input type="text" name="layer_time_cnt" id="layer_time_cnt" class="form-control2" style="width:20%;text-align:center;" value="" /> &nbsp;
                <input type="button" class="btn btn-primary time-controll" value="+" id="next" />
            </div>
        </div>
        <!-- 레이어 -->

        <header>
            <div>{{$title}}</div>
            <div><input type="button" class="btn btn-danger" value="새로고침" onclick="location.reload();"/></div>
            @if($pathInfo === '2')
                <div><input type="button" class="btn btn-success" value="정산" onclick="location.href='/statement?searchYesr={{$searchYear}}&searchMonth={{$searchMonth}}'" /></div>
            @endif
        </header>
        <div class="content">
            <div>
                <select name="searchYear" id="searchYear" class="form-control" onchange="">
                    @for($i=2021; $i <= date("Y"); $i++)
                        <option value="{{$i}}" @if($searchYear == $i) selected @endif>{{$i}}년도</option>
                    @endfor
                </select>
            </div>
            <div>
                <ul class="row list-unstyled">
                @for($i=1; $i <= 12; $i++)
                    @php
                        $ii = $i < 10 ? "0" . $i : $i;
                    @endphp
                    <li class="col-sm-2">
                        <a id="search-month-{{$ii}}" href="javascript:;" onclick="search_link('', '{{$ii}}')">{{$ii}}월</a>
                    </li>
                @endfor
                </ul>
            </div>
            <div id='calendar' style="background-color:#fff;padding:10px;"></div>
            <div>
                @if($pathInfo === '1') 정성스런 노고에 감사드립니다. @endif
            </div>
            <div>
                @foreach ($payTypesCount as $key => $val)
                    <div>{{$key}}시간 x {{$val}}회 = ({{$key*$val}}시간){{number_format($key * $val * 12000)}}</div>
                @endforeach
                <div>총 {{$paySum}}시간 = {{number_format($paySum *  12000)}}</div>
            </div>
            <div>&nbsp;</div>
        </div>
    </div>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/js/fullCalender.js"></script>
    <script>
        var __CLICK_DATE__ = "";
        var global_search_month = "<?php echo $searchMonth?>";
        $(function(){
            var calendar = "";
            var date = new Date();

            /* initialize the external events
            -----------------------------------------------------------------*/
            $('#external-events div.external-event').each(function() {

                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                // it doesn't need to have a start or end
                var eventObject = {
                    title: $.trim($(this).text()) // use the element's text as the event title
                };

                // store the Event Object in the DOM element so we can get to it later
                $(this).data('eventObject', eventObject);

                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 999,
                    revert: true,      // will cause the event to go back to its
                    revertDuration: 0  //  original position after the drag
                });

            });

            var put_event = "";

            /* initialize the calendar
            -----------------------------------------------------------------*/

            calendar =  $('#calendar').fullCalendar({
                year: "<?php echo $searchYear2?>",
                month: "<?php echo $searchMonth2?>",
                date: 1,
                header: {
                    left: 'title',
                    center: 'month',
                    right: 'prev,next today'
                },
                editable: true,
                firstDay: 0, //  1(Monday) this can be changed to 0(Sunday) for the USA system
                selectable: false,
                defaultView: 'month',

                axisFormat: 'h:mm',
                columnFormat: {
                    month: 'ddd',    // Mon
                    week: 'ddd d', // Mon 7
                    day: 'dddd M/d',  // Monday 9/7
                    agendaDay: 'dddd d'
                },
                titleFormat: {
                    month: 'MMMM yyyy', // September 2009
                    week: "MMMM yyyy", // September 2009
                    day: 'MMMM yyyy'                  // Tuesday, Sep 8, 2009
                },
                allDaySlot: false,
                selectHelper: false,
                droppable: false, // this allows things to be dropped onto the calendar !!!
                drop: function(date, allDay) { // this function is called when something is dropped

                    // retrieve the dropped element's stored Event Object
                    var originalEventObject = $(this).data('eventObject');

                    // we need to copy it, so that multiple events don't have a reference to the same object
                    var copiedEventObject = $.extend({}, originalEventObject);

                    // assign it the date that was reported
                    copiedEventObject.start = date;
                    copiedEventObject.allDay = allDay;

                    // render the event on the calendar
                    // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                    $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        $(this).remove();
                    }

                },
                dayClick: function(date, allDay, jsEvent, view) {
                    <?php if ($pathInfo == 1) {?>
                        return false;
                    <?php } ?>
                    __CLICK_DATE__ = $.fullCalendar.formatDate(date,'yyyy-MM-dd');
                    $("#layer_time_cnt").val("");
                    $("#regist-layer").show();
                    $("#regist-layer-background").show();

                    $("#click-date").html(__CLICK_DATE__);
                    var allData = {
                        "searchYear":"<?php echo $searchYear?>"
                        ,"searchMonth":"<?php echo $searchMonth?>"
                    };

                    $.ajax({
                        url: "/pay/calenderData",
                        data: allData,
                        type: 'GET',
                        success: function (data) {
                            var returnList = JSON.parse(JSON.stringify(data));
                            $("#layer_time_cnt").val(returnList['list2'][__CLICK_DATE__]['time_cnt']);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert("시스템 장애[관리자에게 문의] \n" + textStatus + " : " + errorThrown);
                            self.close();
                        }
                    });
                    /*
                    if (__CLICK_DATE__ < toDate) {
                        alert("이전 날짜는 선택 불가");
                        return false;
                    }
                    */
                },
                events : function(start, end, callback){
                    var allData = {
                        "searchYear":"<?php echo $searchYear?>"
                        ,"searchMonth":"<?php echo $searchMonth?>"
                    };

                    $.ajax({
                        url: "/pay/calenderData",
                        data: allData,
                        type: 'GET',
                        success: function (data) {
                            var returnList = JSON.parse(JSON.stringify(data));
                            var events = [];
                            if (returnList['list'].length > 0) {
                                for (i = 0; i < returnList['list'].length; i++) {
                                    events.push({
                                        "id": returnList['list'][i]['id'],
                                        "title": returnList['list'][i]['title'],
                                        "start": returnList['list'][i]['date']
                                    });
                                }
// 	   				        var events = eval(plan.jsonTxt);
                                callback(events); //여기서 오류 나더라구요
                                colorSetting();
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert("시스템 장애[관리자에게 문의] \n" + textStatus + " : " + errorThrown);
                            self.close();
                        }
                    });
                }
            });

            $(".fc-header").hide();
            $("#search-month-<?php echo $searchMonth?>").css("background-color", "#ff0000");
            $("#search-month-<?php echo $searchMonth?>").css("color", "#ffffff");
            $("#search-month-<?php echo $searchMonth?>").css("border-color", "#ff0000");
            $("#search-month-<?php echo $searchMonth?>").css("font-weight", "bold");

            $(document).on("click", "#layer_close, #regist-layer-background", function(){
                $("#regist-layer").hide();
                $("#regist-layer-background").hide();
                //$('#calendar').fullCalendar( 'refetchEvents');
                location.reload();
            });



            $(document).on("click", ".time-controll", function(){
                var type = $(this).attr("id");
                var now_layer_time_cnt = $("#layer_time_cnt").val();

                if (type == "next") {
                    now_layer_time_cnt++;
                } else {
                    if (now_layer_time_cnt == 0) {
                        alert("더 이상 차감할 수 없습니다");
                        return false;
                    }
                    now_layer_time_cnt--;
                }
                $("#layer_time_cnt").val(now_layer_time_cnt);

                $.ajax({
                    url: "/pay/timeCountSetting",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type : 'POST',
                    data : {"date":__CLICK_DATE__, "time_cnt" : now_layer_time_cnt},
                    beforeSend: function () {
                        //$("#write-form-button").css("margin-top","30px;");
                        //$("#write-form-button").html("<img src='/admin/inc/img/loading.gif' style='width:32px;' />");
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert("시스템 장애[관리자에게 문의] \n" + textStatus + " : " + errorThrown);
                        self.close();
                    }
                });
            });

            $(document).on("change", "#time_cnt", function(){
                var date = $(this).attr("date");
                var time_cnt = $(this).val();
                $.ajax({
                    url: "/pay/store",
                    type : 'POST',
                    data : {"date":date, "time_cnt" : time_cnt},
                    beforeSend: function () {
                        //$("#write-form-button").css("margin-top","30px;");
                        //$("#write-form-button").html("<img src='/admin/inc/img/loading.gif' style='width:32px;' />");
                    },
                    success: function (data) {
                        var returnData = JSON.parse(data);

                        $("#golf_no_fk option").remove();
                        $("#golf_no_fk").append("<option value=''>골프장 선택 (미선택:지역 전체)</option>");
                        $.each(returnData, function (item, value) {
                            $("#golf_no_fk").append("<option value='" + value['no'] + "'>" + value['name'] + "</option>");
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert("시스템 장애[관리자에게 문의] \n" + textStatus + " : " + errorThrown);
                        self.close();
                    }
                });
            })
        });


        function search_link(year, month){
            var search_year = year;
            var search_month = month;
            if(year == '') {
                search_year = $("#searchYear").val();
            }

            if(month == '') {
                search_month = global_search_month;
            }

            location.href='<?php echo $thisUrl?>?searchYear=' + search_year + '&searchMonth=' + search_month;
            //var searchYear =
        }

        function colorSetting(){
            $('.fc-event-inner').each(function (index, item) {
                var time_cnt = $(this).text();
                if (time_cnt == 0) {
                    $(this).css("background-color", "#999999");
                } else if (time_cnt == 2) {

                } else {
                    $(this).css("background-color", "#FF6060");
                }
            });
        }
    </script>
@endsection