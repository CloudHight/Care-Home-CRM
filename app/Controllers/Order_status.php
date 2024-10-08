<?php

namespace App\Controllers;

class Order_status extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_admin_or_settings_admin();
    }

    function index() {
        return $this->template->view("order_status/index");
    }

    function modal_form() {
        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Order_status_model->get_one($this->request->getPost('id'));
        return $this->template->view('order_status/modal_form', $view_data);
    }

    function save() {
        $this->validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required"
        ));

        $id = $this->request->getPost('id');

        $data = array(
            "color" => $this->request->getPost('color'),
            "title" => $this->request->getPost('title')
        );

        if (!$id) {
            //get sort value
            $max_sort_value = $this->Order_status_model->get_max_sort_value();
            $data["sort"] = $max_sort_value * 1 + 1; //increase sort value
        }

        $save_id = $this->Order_status_model->ci_save($data, $id);

        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    //update the sort value for the fields
    function update_field_sort_values() {

        $sort_values = $this->request->getPost("sort_values");
        if ($sort_values) {

            //extract the values from the comma separated string
            $sort_array = explode(",", $sort_values);


            //update the value in db
            foreach ($sort_array as $value) {
                $sort_item = explode("-", $value); //extract id and sort value

                $id = get_array_value($sort_item, 0);
                validate_numeric_value($id);

                $sort = get_array_value($sort_item, 1);
                validate_numeric_value($sort);

                $data = array("sort" => $sort);
                $this->Order_status_model->ci_save($data, $id);
            }
        }
    }

    function delete() {
        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');

        if ($this->request->getPost('undo')) {
            if ($this->Order_status_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Order_status_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        $list_data = $this->Order_status_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Order_status_model->get_details($options)->getRow();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
        $edit = modal_anchor(get_uri("order_status/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_order_status'), "data-post-id" => $data->id));

        $delete_attributes = array('title' => app_lang('delete_order_status'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("order_status/delete"), "data-action" => "delete-confirmation");
        if ($data->total_orders) {
            //the status shouldn't be deleted if there is any orders
            $delete_attributes = array('title' => app_lang('there_has_orders_with_this_status'), "class" => "delete not-clickable text-off",);
        }

        $delete = js_anchor("<i data-feather='x' class='icon-16'></i>", $delete_attributes);

        return array(
            $data->sort,
            "<div class='pt10 pb10 field-row'  data-id='$data->id'><div class='float-start move-icon'><i data-feather='menu' class='icon-16'></i> </div><span style='background-color:" . $data->color . "' class='color-tag  float-start'></span>" . $data->title . '</div>',
            $edit . $delete
        );
    }

}

/* End of file Order_status.php */
/* Location: ./app/controllers/Order_status.php */