<div id="page-content" class="page-wrapper clearfix">

    <div class="card">
        <div class="page-title clearfix">
            <h1> <?php echo app_lang('orders'); ?></h1>
            <div class="title-button-group">
                <?php echo anchor(get_uri("store"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_order'), array("class" => "btn btn-default", "id" => "add-order-btn")); ?> 
            </div>
        </div>
        <div class="table-responsive">
            <table id="order-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {

        var idColumnClass = "w10p",
            invoiceColumnClass = "w20p";

        if (isMobile()) {
            idColumnClass = "";
            invoiceColumnClass = "";
        }

        $("#order-table").appTable({
            source: '<?php echo_uri("orders/order_list_data_of_client/" . $client_id) ?>',
            order: [[0, "desc"]],
            filterDropdown: [<?php echo $custom_field_filters; ?>],
            columns: [
                {visible: false, searchable: false},
                {title: "<?php echo app_lang("order") ?>", "class": idColumnClass + " all", "iDataSort": 0},
                {visible: false, searchable: false},
                {title: "<?php echo app_lang("invoices") ?>", "class": invoiceColumnClass + " all"},
                {visible: false, searchable: false},
                {title: "<?php echo app_lang("order_date") ?>", "iDataSort": 4, "class": "w15p"},
                {title: "<?php echo app_lang("amount") ?>", "class": "text-right w20p"},
                {title: "<?php echo app_lang("status") ?>", "class": "text-center w20p"}
<?php echo $custom_field_headers; ?>,
                {visible: false}
            ],
            summation: [{column: 6, dataType: 'currency'}]
        });
    });
</script>