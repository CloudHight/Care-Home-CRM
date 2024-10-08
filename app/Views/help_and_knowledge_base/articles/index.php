<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo app_lang('articles') . " (" . app_lang($type) . ")"; ?></h1>
            <div class="title-button-group">
                <?php
                echo anchor(get_uri("help/article_form/" . $type), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_article'), array("class" => "btn btn-default", "title" => app_lang('add_article')));
                ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="article-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        var showFeedback = false;
<?php if ($type == "knowledge_base" && $login_user->is_admin) { ?>
            showFeedback = true;
<?php } ?>

        $("#article-table").appTable({
            source: '<?php echo_uri("help/articles_list_data/" . $type) ?>',
            order: [[0, "desc"]],
            filterDropdown: [
                {name: "category_id", class: "w200", options: <?php echo $categories_dropdown; ?>}
            ],
            columns: [
                {title: "<?php echo app_lang('title') ?>", "class": "all"},
                {title: "<?php echo app_lang('category') ?>", "class": "w30p"},
                {title: "<?php echo app_lang('status') ?>", "class": "w10p"},
                {title: "<?php echo app_lang('total_views') ?>", "class": "w10p"},
                {visible: showFeedback, title: "<?php echo app_lang('feedback') ?>", "class": "w10p"},
                {title: "<?php echo app_lang('sort'); ?>"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3]
        });
    });
</script>