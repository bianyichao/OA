/**
 * Created by alcanderian on 27/11/2016.
 */

var settings, ori_len_weekday, ori_len_weekend;

var timepoint_weekday = [], timepoint_weekend = [];

var init = function () {
    put_year();
    get_term_list();
    get_settings();
};

var get_settings = function () {
    $.ajax({
        type: 'post',
        url: 'Settings/getAllConfig',
        data: {},
        success: function (msg) {
            ret = JSON.parse(msg);
            if (ret['state'] === true) {
                settings = ret['msg'];
                $("#salary_setting").val(settings['salary_per_hour']);
                timepoint_weekday = settings['weekday_breakpoint'].split(",");
                timepoint_weekend = settings['weekend_breakpoint'].split(",");
                put_duty_array_by_id("timepoint_weekday", timepoint_weekday);
                put_duty_array_by_id("timepoint_weekend", timepoint_weekend);
                ori_len_weekday = timepoint_weekday.length;
                ori_len_weekend = timepoint_weekend.length;
            }
        },
        error: function () {
            alert("提取配置失败，无法连接服务器");
        }
    });
};

var put_duty_array_by_id = function (id, arr) {
    var obj = $("#" + id);
    $("." + id + "_block").remove();
    warn[id] = 0;
    for (var i = 0; i < arr.length; ++i) {
        var arg = '\'' + id + '\', ' + id + ", ";
        obj.append('<div class="' + id + '_block">' +
                        '<input id="' + id + '_' + i + '" type="text" onchange="test_time(this.value, \'' +id + '\', ' + i + ');" ' +
                                'style="margin-bottom: 3px" class="time from-control col-lg-6">' +
                        '<button id="del_' + id + i + '" data-toggle="modal" ' +
                                'class="form-control btn btn-danger col-lg-3" ' +
                                'style="margin-bottom: 3px" onclick="arr_del(' + arg + i + ');">删除</button>' +
                        '<div class="alert alert-danger col-lg-3"' +
                            ' id="warn_' + id + '_' + i + '"' +
                            ' style="margin-bottom: 3px;margin-left: 5px;padding: 6px 12px;display: none">' +
                            '时间格式不正确' +
                        '</div>' +
                    '</div>');
        $("#" + id + "_" + i).timepicker({"timeFormat": "H:i"}).val(arr[i]);
        test_time($("#" + id + "_" + i).val(), id, i);
    }
};

var arr_del = function (id, arr, index) {
    arr.splice(index, 1);
    put_duty_array_by_id(id, arr);
};

var warn = {};
warn['timepoint_weekday'] = 0;
warn['timepoint_weekend'] = 0;

var test_time = function (time, id, i) {
    var reg = /^(\d{2}):(\d{2})$/;
    var obj = $("#warn_" + id + "_" + i);
    if(reg.test(time)){
        if(!obj.is(':hidden')) {
            warn[id]--;
        }
        obj.hide();
    } else {
        if(obj.is(':hidden')) {
            warn[id]++;
        }
        obj.show();
    }
    if(warn[id]) {
        $("#submit_" + id).attr("disabled", true);
    } else {
        $("#submit_" + id).attr("disabled", false);
    }
};

var submit_tp = function (id, arr) {
    sort_tp(id, arr);
    var tmp = [];
    console.log(ori_len_weekday, timepoint_weekday.length, ori_len_weekend, timepoint_weekend.length);
    if(ori_len_weekday !== timepoint_weekday.length ||　ori_len_weekend !== timepoint_weekend.length) {
        if(confirm("值班时间表的换班点数改变了，这会清空空余时间总表和现有值班表，确定要提交吗? 1/3")) {
            if(confirm("值班时间表的换班点数改变了，这会清空空余时间总表和现有值班表，确定要提交吗? 2/3")) {
                if(confirm("值班时间表的换班点数改变了，这会清空空余时间总表和现有值班表，确定要提交吗? 3/3")) {
                    $.ajax({
                        type: 'post',
                        url: 'Settings/clearDutyAndNschedule',
                        success: function (msg) {
                            alert("总共删除了" + JSON.parse(msg)['msg'] + "条记录");
                        },
                        error: function () {
                            alert("删除失败，无法连接服务器");
                        }
                    });
                } else {
                    return;
                }
            } else {
                return;
            }
        } else {
            return;
        }
    }
    tmp['timepoint_weekday'] = 'weekday_breakpoint';
    tmp['timepoint_weekend'] = 'weekend_breakpoint';
    $.ajax({
        type: 'post',
        url: 'Settings/setConfig',
        data: {
            name: tmp[id],
            value: arr.join(",")
        },
        success: function (msg) {
            ret = JSON.parse(msg);
            alert(ret['msg']);
        },
        error: function() {
            alert("无法连接服务器");
        }
    });
};

var sort_tp = function (id, arr) {
    for (var i = 0; i < arr.length; ++i) {
        arr[i] = $("#" + id + "_" + i).val();
    }
    arr.sort();
    for (var i = 0; i < arr.length - 1; ++i) {
        if(arr[i] === arr[i + 1]) {
            arr.splice(i, 1);
            --i;
        }
    }
    put_duty_array_by_id(id, arr);
};

var add_tp = function (id, arr) {
    for (var i = 0; i < arr.length; ++i) {
        arr[i] = $("#" + id + "_" + i).val();
    }
    arr.splice(arr.length, 0, "");
    put_duty_array_by_id(id, arr);
};

var put_year = function () {
    var year = (new Date).getFullYear();
    for (var i = year - 3; i < year + 3; ++i) {
        $("#year_a").append("<option value='" + i + "'>" + i + "</option>");
        $("#year_b").append("<option value='" + i + "'>" + i + "</option>");
    }
};

var new_term = function () {
    var year_a = $("#year_a option:selected").attr("value");
    var year_b = $("#year_b option:selected").attr("value");

    var year = year_a + "-" + year_b;

    var term = $("#term option:selected").attr("value");

    var start = $("#start_dtp").val() + " 00:00:00";
    var end = $("#end_dtp").val() + " 23:59:59";

    $.ajax({
        type: 'post',
        url: 'Settings/newTerm',
        data: {
            schoolyear: year,
            schoolterm: term,
            termbeginstamp: start,
            termendstamp: end
        },
        success: function (msg) {
            ret = JSON.parse(msg);
            alert(ret['msg']);
        },
        error: function() {
            alert("新建学期失败，无法连接服务器")
        }
    });
};

var get_term_list = function () {
    $.ajax({
        type: 'post',
        url: 'Settings/getTermList',
        success: function (msg) {
            ret = JSON.parse(msg);
            if (ret['state'] === true) {
                var list_tmp = "";
                for (var i = 0; i < ret['term_list'].length; i++) {
                    list_tmp +=
                        "<tr>" +
                            "<td>" +
                                i +
                            "</td>" +
                            "<td>" +
                                ret['term_list'][i]['schoolyear'] +
                            "</td>" +
                            "<td>" +
                                ret['term_list'][i]['schoolterm'] +
                            "</td>" +
                            "<td>" +
                                ret['term_list'][i]['termbeginstamp'] +
                            "</td>" +
                            "<td>" +
                                ret['term_list'][i]['termendstamp'] +
                            "</td>" +
                            "<td>" +
                                "<button type='button' class='btn btn-xs btn-outline btn-danger manager' value='" + ret['term_list'][i]['termid'] + "' onclick='delete_term(this.value)' style='margin-left: 10px;'>" +
                                "<i class='fa fa-close'></i><span> 移除</span></button>" +
                            "</td>" +
                        "</tr>"
                }
                $("#term-list").html(list_tmp);

                var now_term = "学期设置 - 当前学期：" + ret['now_term']['schoolyear'] + "学年度" + ret['now_term']['schoolterm'];
                $("#now-term").html(now_term);
            } else {
                $("#now-term").html("学期设置");
            }

            $(".users-dataTable").dataTable({"aaSorting": [[ 0, "desc" ]], "paging": false});
        },
        error: function() {
            alert("获取历史学期失败，无法连接服务器");
            $("#now-term").html("学期设置");
        }
    });
};

var delete_term = function (tid) {
    console.log(tid);
    if(tid == undefined) {
        alert("删除学期失败");
        return;
    }
    $.ajax({
        type: 'post',
        url: 'Settings/deleteTerm',
        data: {
            termid: tid
        },
        success: function (msg) {
            ret = JSON.parse(msg);
            alert(ret['msg']);
            if (ret['state'] === true) {
                location.reload();
            }
        },
        error: function() {
            alert("删除学期失败，无法连接服务器")
        }
    });
};



var salary_submit = function () {
    now_salary = parseFloat($("#salary_setting").val());
    console.log(now_salary);
    if(!isNaN(now_salary) && now_salary > 0) {
        $("#salary_warning").hide();
        $.ajax({
            type: 'post',
            url: 'Settings/setConfig',
            data: {
                name: 'salary_per_hour',
                value: now_salary
            },
            success: function (msg) {
                ret = JSON.parse(msg);
                alert(ret['msg']);
            },
            error: function() {
                alert("无法连接服务器");
            }
        });
    } else {
        $("#salary_warning").show();
    }
};

$(document).ready(init());