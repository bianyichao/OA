<?php
/**
 * Created by PhpStorm.
 * User: zhong
 * Date: 2017/9/14
 * Time: 2:00
 */

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">

    <title>MOA-查看和登记失物</title>
    <?php $this->load->view('view_keyword'); ?>

<link href="<?= base_url() . 'assets/images/moa.ico' ?>" rel="shortcut icon">

<link href="<?= base_url() . 'assets/css/bootstrap.min.css?v=3.4.0' ?>" rel="stylesheet">
<link href="<?= base_url() . 'assets/font-awesome/css/font-awesome.min.css' ?>" rel="stylesheet">

<link href="<?= base_url() . 'assets/css/plugins/iCheck/custom.css' ?>" rel="stylesheet">

<link href="<?= base_url() . 'assets/css/plugins/simditor/simditor.css' ?>" rel="stylesheet">

<link href="<?= base_url() . 'assets/css/plugins/chosen/chosen.css' ?>" rel="stylesheet">

<link href="<?= base_url() . 'assets/css/plugins/jasny/jasny-bootstrap.min.css' ?>" rel="stylesheet">

<link href="<?= base_url() . 'assets/css/plugins/datepicker/datepicker3.css' ?>" rel="stylesheet">

<link href="<?= base_url() . 'assets/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css' ?>" rel="stylesheet">

<!-- Data Tables -->
<link href="<?= base_url() . 'assets/css/plugins/dataTables/dataTables.bootstrap.css' ?>" rel="stylesheet">

<link href="<?= base_url() . 'assets/css/animate.css' ?>" rel="stylesheet">
<link href="<?= base_url() . 'assets/css/style.css?v=2.2.0' ?>" rel="stylesheet">

</head>

<body onload="startTime()">
<div id="wrapper">
    <?php $this->load->view('view_nav'); ?>

    <div id="page-wrapper" class="gray-bg dashbard-1">
        <?php $this->load->view('view_header'); ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2 id="time"></h2>
                <ol class="breadcrumb">
                    <li>
                        MOA
                    </li>
                    <li>
                        失物招领
                    </li>
                    <li>
                        <strong>拾获登记</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>查看和登记拾获物品</h5>
                            <div class="btn-group pull-right">
                                <button class="btn btn-primary btn-xs" id="new_record_btn" onclick="new_record()"
                                        data-toggle="modal" data-target="#myModal">新增记录
                                </button>
                                <button class="btn btn-secondary btn-xs" id="toggle_more_btn" onclick="toggle_more()">
                                    显示更多
                                </button>
                            </div>
                        </div>
                        <div class="ibox-content">

                            <table id="found_table"
                                   class="table table-striped table-bordered table-hover users-dataTable">
                                <thead>
                                <tr>
                                    <th>序号</th>       <!--  1  $d_fid-->
                                    <th>登记助理</th>   <!--  2  $d_fworkername-->
                                    <th>拾获时间</th>   <!--  3  $d_found_{date, weekday_translate, time}-->
                                    <th>物品描述</th>   <!--  4  $d_fdescription-->
                                    <th>地点</th>       <!--  5  $d_fplace-->
                                    <th>登记时间</th>   <!--  6  $d_signup_{...}-->
                                    <th>拾获人</th>     <!--  7  $d_finder-->
                                    <th>联系方式</th>   <!--  8  $d_fcontact-->
                                    <th>登记助理</th>   <!--  9  $d_oworkername-->
                                    <th>领取人</th>     <!-- 10  $d_owner-->
                                    <th>领取时间</th>   <!-- 11  $d_owned_{...}-->
                                    <th>联系方式</th>   <!-- 12  $d_ocontact-->
                                    <th>证件号</th>     <!-- 13  $d_onumber-->
                                    <th>操作</th>       <!-- 14 -->
                                </tr>
                                </thead>
                                <tbody>
                                <?php for ($i = 0; $i < count($d_fid); $i++){?>
                                    <tr class="found_content" id="found_content_<?php echo $d_fid[$i];?>">
                                        <td><?php echo $d_fid[$i];?></td>
                                        <td><?php echo $d_fworkername[$i];?></td>
                                        <td>
                                            <?php echo $d_found_date[$i] . "&nbsp;";?>
                                            <?php //echo "星期" . $d_found_weekday_translate[$i] . "&nbsp;";?>
                                            <?php echo $d_found_time[$i];?>
                                        </td>
                                        <td><?php echo $d_fdescription[$i];?></td>
                                        <td><?php echo $d_fplace[$i];?></td>
                                        <td>
                                            <?php echo $d_signup_date[$i] . "&nbsp;";?>
                                            <?php //echo "星期" . $d_signup_weekday_translate[$i] . "&nbsp;";?>
                                            <?php echo $d_signup_time[$i];?>
                                        </td>
                                        <td><?php echo $d_finder[$i];?></td>
                                        <td><?php echo $d_fcontact[$i];?></td>
                                        <td><?php echo $d_state[$i] > 0 ? $d_oworkername[$i] : "";?></td>
                                        <td><?php echo $d_owner[$i];?></td>
                                        <td>
                                            <?php
                                            if ($d_state[$i] > 0) {
                                                echo $d_owned_date[$i] . "&nbsp;";
                                                //echo "星期" . $d_owned_weekday_translate[$i] . "&nbsp;";
                                                echo $d_owned_time[$i];
                                            }else echo "<span style='color:red;'>未领取</span>";
                                            ?>
                                        </td>
                                        <td><?php echo $d_ocontact[$i];?></td>
                                        <td><?php echo $d_onumber[$i];?></td>
                                        <td>
                                            <div class="btn-group" id="found_btn_group_<?php echo $d_fid[$i];?>">
                                                <?php
                                                if ($d_state[$i] == 0)
                                                    echo "<button class='btn btn-primary btn-xs' id='found_btn_".
                                                        $d_fid[$i]."' onclick='found_by_fid(".$d_fid[$i].")' ".
                                                        "data-toggle='modal' data-target='#myModal'".
                                                        ">领取</button>";
                                                if (//$d_fwid[$i] == $_SESSION['worker_id'] ||
                                                    $d_owid[$i] == $_SESSION['worker_id'] ||
                                                    $_SESSION['level'] >= 2 &&
                                                    $d_state[$i] == 1)
                                                    echo "<button class='btn btn-warning btn-xs' id='found_btn_".
                                                        $d_fid[$i]."' onclick='update_by_fid(".$d_fid[$i].")' ".
                                                        "data-toggle='modal' data-target='#myModal'".
                                                        ">更新</button>";
                                                if ($d_fwid[$i] == $_SESSION['worker_id'] || $_SESSION['level'] >= 2)
                                                    echo "<button class='btn btn-danger btn-xs' id='delete_btn_".
                                                        $d_fid[$i]."' onclick='delete_by_fid(".$d_fid[$i].")'>删除</button>";
                                                ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->load->view('view_footer'); ?>
    </div>
</div>

<script>
    var wid_current = <?php echo $_SESSION['worker_id'];?>;
</script>

<!-- Mainly scripts -->
<script src="<?= base_url() . 'assets/js/jquery-2.1.1.min.js' ?>"></script>
<script src="<?= base_url() . 'assets/js/bootstrap.min.js?v=3.4.0' ?>"></script>
<script src="<?= base_url() . 'assets/js/plugins/metisMenu/jquery.metisMenu.js' ?>"></script>
<script src="<?= base_url() . 'assets/js/plugins/slimscroll/jquery.slimscroll.min.js' ?>"></script>
<script src="<?= base_url() . 'assets/js/found.js' . '?t=' . time() ?>"></script>

<!-- nav item active -->
<script>
    $(document).ready(function () {
        $("#active-lostandfound").addClass("active");
        $("#active-found").addClass("active");
        $("#mini").attr("href", "Found#");
    });
</script>

<!-- Custom and plugin javascript -->
<script src="<?= base_url() . 'assets/js/hplus.js?v=2.2.0' ?>"></script>
<script src="<?= base_url() . 'assets/js/plugins/pace/pace.min.js' ?>"></script>

<!-- Dynamic date -->
<script src="<?= base_url() . 'assets/js/dynamicDate.js' ?>"></script>

<!-- Jquery Validate -->
<script type="text/javascript" src="<?= base_url() . 'assets/js/plugins/validate/jquery.validate.min.js' ?>"></script>
<script type="text/javascript" src="<?= base_url() . 'assets/js/plugins/validate/messages_zh.min.js' ?>"></script>

<!-- iCheck -->
<script src="<?= base_url() . 'assets/js/plugins/iCheck/icheck.min.js' ?>"></script>

<!-- Chosen -->
<script src="<?= base_url() . 'assets/js/plugins/chosen/chosen.jquery.js' ?>"></script>

<!-- JSKnob -->
<script src="<?= base_url() . 'assets/js/plugins/jsKnob/jquery.knob.js' ?>"></script>

<!-- Input Mask-->
<script src="<?= base_url() . 'assets/js/plugins/jasny/jasny-bootstrap.min.js' ?>"></script>

<!-- Date picker -->
<script src="<?= base_url() . 'assets/js/plugins/datepicker/bootstrap-datepicker.js' ?>"></script>

<!-- Date time picker -->
<script src="<?= base_url() . 'assets/js/plugins/datetimepicker/bootstrap-datetimepicker.js' ?>"></script>
<script src="<?= base_url() . 'assets/js/plugins/datetimepicker/bootstrap-datetimepicker.zh-CN.js' ?>"></script>

<!-- Data Tables -->
<script src="<?= base_url() . 'assets/js/plugins/dataTables/jquery.dataTables.js' ?>"></script>
<script src="<?= base_url() . 'assets/js/plugins/dataTables/dataTables.bootstrap.js' ?>"></script>

<script>
    $(document).ready(function () {

        $('.users-dataTable').dataTable({
            "aaSorting": [[10, "desc"]],
            "columnDefs": [
                {
                    "targets":[1, 5, 7, 8, 11, 12],
                    "visible":false
                }
            ]
        });

        /* Calendar */
        $('#calendar_date .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
        });


    });

    /* Chosen */
    var config = {
        '.chosen-select': {},
        '.chosen-select-deselect': {
            allow_single_deselect: true
        },
        '.chosen-select-no-single': {
            disable_search_threshold: 10
        },
        '.chosen-select-no-results': {
            no_results_text: 'Oops, nothing found!'
        },
        '.chosen-select-width': {
            width: "95%"
        }
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

</script>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     style="z-index: 10;">
    <div class="modal-dialog">
        <div class="modal-content" style="margin-top: 90px;">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabelTitle"></h4>
            </div>
            <div id="modalBody" class="modal-body">
            </div>
        </div>
    </div>
</div>

</body>

</html>
