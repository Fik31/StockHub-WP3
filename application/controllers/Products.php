<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Products extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->not_logged_in();

        $this->data['page_title'] = 'Products';

        $this->load->model('model_products');
        $this->load->model('model_brands');
        $this->load->model('model_category');
        // $this->load->model('model_stores');
        $this->load->model('model_attributes');
    }


    public function index()
    {
        if (!in_array('viewProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $this->render_template('products/index', $this->data);
    }


    public function fetchProductData()
    {
        $result = array('data' => array());

        $data = $this->model_products->getProductData();

        foreach ($data as $key => $value) {

            // $store_data = $this->model_stores->getStoresData($value['store_id']);
            $brand_data = $this->model_brands->getBrandData($value['brand_id']);
            $category_data = $this->model_category->getCategoryData($value['category_id']);
            // button
            $buttons = '';
            if (in_array('updateProduct', $this->permission)) {
                $buttons .= '<a href="' . base_url('Products/update/' . $value['id']) . '" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a>';
            }

            if (in_array('deleteProduct', $this->permission)) {
                $buttons .= ' <button type="button" class="btn btn-danger btn-sm" onclick="removeFunc(' . $value['id'] . ')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
            }

            $img = '<img src="' . base_url($value['image']) . '" alt="' . $value['name'] . '" class="img-circle" width="50" height="50" />';

            $availability = ($value['availability'] == 1) ? '<center><span class="label label-success">Active</span></center>' : '<center><span class="label label-warning">Inactive</span></center>';

            $qty_status = '';
            if ($value['qty'] <= 10) {
                $qty_status = '<span class="label label-warning">Low</span>';
            } else if ($value['qty'] <= 0) {
                $qty_status = '<span class="label label-danger">Out of Stock!</span>';
            }


            $result['data'][$key] = array(
                $img,
                // $value['sku'],
                $value['name'],
                // '$'.$value['price'],
                'Rp ' . number_format($value['price'], 0, ',', '.'),
                $value['qty'] . ' ' . $qty_status,
                $value['description'],
                // $store_data['name'],
                $brand_data['name'],
                $category_data['name'],
                // $value['category_id'],
                $availability,
                $buttons
            );
        } // /foreach

        echo json_encode($result);
    }


    public function create()
    {
        // echo 'came';
        // exit();
        if (!in_array('createProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('product_name', 'Product name', 'trim|required');
        // $this->form_validation->set_rules('sku', 'SKU', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        // $this->form_validation->set_rules('store', 'Store', 'trim|required');
        $this->form_validation->set_rules('availability', 'Availability', 'trim|required');


        if ($this->form_validation->run() == TRUE) {
            // true case
            $upload_image = $this->upload_image();

            $data = array(
                'name' => $this->input->post('product_name'),
                // 'sku' => $this->input->post('sku'),
                'price' => $this->input->post('price'),
                'qty' => $this->input->post('qty'),
                'image' => $upload_image,
                'description' => $this->input->post('description'),
                'attribute_value_id' => json_encode($this->input->post('attributes_value_id')),
                'brand_id' => $this->input->post('brands'),
                'category_id' => $this->input->post('category'),
                // 'store_id' => $this->input->post('store'),
                'availability' => $this->input->post('availability'),
            );

            $create = $this->model_products->create($data);
            if ($create == true) {
                $this->session->set_flashdata('success', 'Successfully created');
                redirect('Products/', 'refresh');
            } else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('Products/create', 'refresh');
            }
        } else {
            // false case

            // attributes 
            $attribute_data = $this->model_attributes->getActiveAttributeData();

            $attributes_final_data = array();
            foreach ($attribute_data as $k => $v) {
                $attributes_final_data[$k]['attribute_data'] = $v;

                $value = $this->model_attributes->getAttributeValueData($v['id']);

                $attributes_final_data[$k]['attribute_value'] = $value;
            }

            $this->data['attributes'] = $attributes_final_data;
            $this->data['brands'] = $this->model_brands->getActiveBrands();
            $this->data['category'] = $this->model_category->getActiveCategroy();
            // $this->data['stores'] = $this->model_stores->getActiveStore();        	

            $this->render_template('products/create', $this->data);
        }
    }


    public function upload_image()
    {
        // assets/images/product_image
        $config['upload_path'] = 'assets/images/product_image';
        $config['file_name'] =  uniqid();
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1000';

        // $config['max_width']  = '1024';s
        // $config['max_height']  = '768';

        $this->load->library('upload', $config);
        if (! $this->upload->do_upload('product_image')) {
            $error = $this->upload->display_errors();
            return $error;
        } else {
            $data = array('upload_data' => $this->upload->data());
            $type = explode('.', $_FILES['product_image']['name']);
            $type = $type[count($type) - 1];

            $path = $config['upload_path'] . '/' . $config['file_name'] . '.' . $type;
            return ($data == true) ? $path : false;
        }
    }


    public function update($product_id)
    {
        if (!in_array('updateProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        if (!$product_id) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('product_name', 'Product name', 'trim|required');
        // $this->form_validation->set_rules('sku', 'SKU', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        // $this->form_validation->set_rules('store', 'Store', 'trim|required');
        $this->form_validation->set_rules('availability', 'Availability', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            // true case

            $data = array(
                'name' => $this->input->post('product_name'),
                // 'sku' => $this->input->post('sku'),
                'price' => $this->input->post('price'),
                'qty' => $this->input->post('qty'),
                'description' => $this->input->post('description'),
                'attribute_value_id' => json_encode($this->input->post('attributes_value_id')),
                'brand_id' => $this->input->post('brands'),
                'category_id' => $this->input->post('category'),
                // 'store_id' => $this->input->post('store'),
                'availability' => $this->input->post('availability'),
            );


            if ($_FILES['product_image']['size'] > 0) {
                $upload_image = $this->upload_image();
                $upload_image = array('image' => $upload_image);

                $this->model_products->update($upload_image, $product_id);
            }

            $update = $this->model_products->update($data, $product_id);
            if ($update == true) {
                $this->session->set_flashdata('success', 'Successfully updated');
                redirect('Products/', 'refresh');
            } else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('Products/update/' . $product_id, 'refresh');
            }
        } else {
            // attributes 
            $attribute_data = $this->model_attributes->getActiveAttributeData();

            $attributes_final_data = array();
            foreach ($attribute_data as $k => $v) {
                $attributes_final_data[$k]['attribute_data'] = $v;

                $value = $this->model_attributes->getAttributeValueData($v['id']);

                $attributes_final_data[$k]['attribute_value'] = $value;
            }

            // false case
            $this->data['attributes'] = $attributes_final_data;
            $this->data['brands'] = $this->model_brands->getActiveBrands();
            $this->data['category'] = $this->model_category->getActiveCategroy();
            // $this->data['stores'] = $this->model_stores->getActiveStore();          

            $product_data = $this->model_products->getProductData($product_id);
            $this->data['product_data'] = $product_data;
            $this->render_template('products/edit', $this->data);
        }
    }


    public function remove()
    {
        if (!in_array('deleteProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $product_id = $this->input->post('product_id');

        $response = array();
        if ($product_id) {
            $delete = $this->model_products->remove($product_id);
            if ($delete == true) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed";
            } else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the product information";
            }
        } else {
            $response['success'] = false;
            $response['messages'] = "Refresh the page again!!";
        }

        echo json_encode($response);
    }
}
