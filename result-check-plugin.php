<?php
/**
 * Plugin Name: Result Check Plugin
 * Description: Plugin to add result check form with CAPTCHA verification.
 * Version: 1.0
 * Author: Mehedi Hasan 
 */

// Register Custom Post Type
function create_student_post_type() {
    register_post_type('student',
        array(
            'labels' => array(
                'name' => __('Application'),
                'singular_name' => __('Applications'),
                'add_new' => __('Add New Application'),
                
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title'),
        )
    );
}
add_action('init', 'create_student_post_type');

// Add Custom Meta Boxes
function add_student_meta_boxes() {
    add_meta_box('student_info', 'Applications Information', 'student_info_callback', 'student', 'normal', 'high');
}
add_action('add_meta_boxes', 'add_student_meta_boxes');

function student_info_callback($post) {
    wp_nonce_field(basename(__FILE__), 'student_info_nonce');
    $serial_no = get_post_meta($post->ID, 'serial_no', true);
    $country = get_post_meta($post->ID, 'country', true);
    $document_no = get_post_meta($post->ID, 'document_no', true);
    $visa_validity = get_post_meta($post->ID, 'visa_validity', true);
    $visa_status = get_post_meta($post->ID, 'visa_status', true);
    $company_registration_no = get_post_meta($post->ID, 'company_registration_no', true);
    $passport_no = get_post_meta($post->ID, 'passport_no', true);
    // $last_pass_due_date = get_post_meta($post->ID, 'last_pass_due_date', true);
    // $renewal_pass_duration = get_post_meta($post->ID, 'renewal_pass_duration', true);
    // $current_pass_due_date = get_post_meta($post->ID, 'current_pass_due_date', true);
    $application_status = get_post_meta($post->ID, 'application_status', true);

    echo '<div class="backend_class"> <label for="serial_no">No:</label>';
    echo '<input type="text" id="serial_no" name="serial_no" value="' . esc_attr($serial_no) . '" class="form-control"> </div>';
    
    echo '<div class="backend_class"> <label for="country">Place of Issue:</label>';
    echo '<input type="text" id="country" name="country" value="' . esc_attr($country) . '" class="form-control"></div>';
    
    echo '<div class="backend_class"> <label for="document_no">Reference No:</label>';
    echo '<input type="text" id="document_no" name="document_no" value="' . esc_attr($document_no) . '" class="form-control"></div>';
    
    echo '<div class="backend_class"> <label for="visa_validity">Visa Validity:</label>';
    echo '<input type="text" id="visa_validity" name="visa_validity" value="' . esc_attr($visa_validity) . '" class="form-control"></div>';
    
    echo '<div class="backend_class"> <label for="visa_status">Visa Status:</label>';
    echo '<input type="text" id="visa_status" name="visa_status" value="' . esc_attr($visa_status) . '" class="form-control"></div>';
    
    echo '<div class="backend_class"> <label for="company_registration_no">Sticker No:</label>';
    echo '<input type="text" id="company_registration_no" name="company_registration_no" value="' . esc_attr($company_registration_no) . '" class="form-control"></div>';
    
    echo '<div class="backend_class"> <label for="passport_no">Passport No:</label>';
    echo '<input type="text" id="passport_no" name="passport_no" value="' . esc_attr($passport_no) . '" class="form-control"></div>';
    
    // echo '<label for="last_pass_due_date">Last Pass Due Date:</label>';
    // echo '<input type="text" id="last_pass_due_date" name="last_pass_due_date" value="' . esc_attr($last_pass_due_date) . '" class="form-control"><br>';
    
    // echo '<label for="renewal_pass_duration">Renewal Pass Duration(Month):</label>';
    // echo '<input type="text" id="renewal_pass_duration" name="renewal_pass_duration" value="' . esc_attr($renewal_pass_duration) . '" class="form-control"><br>';
    
    // echo '<label for="current_pass_due_date">Current Pass Due Date:</label>';
    // echo '<input type="text" id="current_pass_due_date" name="current_pass_due_date" value="' . esc_attr($current_pass_due_date) . '" class="form-control"><br>';
    
    echo '<div class="backend_class"> <label for="application_status">Application Status:</label>';
    echo '<input type="text" id="application_status" name="application_status" value="' . esc_attr($application_status) . '" class="form-control"></div>';
}

function save_student_meta_boxes($post_id) {
    if (!isset($_POST['student_info_nonce']) || !wp_verify_nonce($_POST['student_info_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    $post_type = get_post_type_object(get_post_type($post_id));
    if (!current_user_can($post_type->cap->edit_post, $post_id)) {
        return $post_id;
    }

    $fields = array('serial_no', 'country', 'document_no', 'visa_validity', 'visa_status', 'company_registration_no', 'passport_no', 'application_status');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'save_student_meta_boxes');

// Add CAPTCHA to the Result Check Form
function add_captcha_to_result_check_form() {
    echo '<div class="g-recaptcha" data-sitekey="6Le_WIcpAAAAAD5j4XnsT4Jc5mV42FIl5gGCJYCG"></div>';
}
add_action('comment_form', 'add_captcha_to_result_check_form');

// Create Shortcode for Result Check Form
function result_check_form_shortcode() {
    ob_start();
    ?>
    <div id="result-check-form">
        <form id="result-check-form-ajax" class="application_form_class">


           <div class="py-3">
            <img style="max-width: 100%; margin-left:-26px;" src="http://localhost/wptask/wp-content/uploads/2024/03/myvisa.jpeg" alt="">

            <h4>Malaysia Electronic Visa Facilitation & Services</h4>
           </div>

            <h2>VERIFY YOUR eVISA NOW</h2>

            <!-- <label  class="form-label" for="passport_no">Passport No:</label> -->
            <div class="form-group">
            <input type="text" name="passport_no" id="passport_no" placeholder="Passport Number" required class="form-control w-100 shadow-sm "> <i> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg></i> <br>
            </div>

            <!-- <label  class="form-label" for="company_registration_no">Company Registration No:</label> -->
             <div class="form-group">
            <input type="text" name="company_registration_no" placeholder="Sticker Number" id="company_registration_no" required class="form-control w-100 shadow-sm"><i data-bs-toggle="modal" data-bs-target="#exampleModal"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg></i><br>
           </div>
            
            <div class="g-recaptcha" data-sitekey="6Le_WIcpAAAAAD5j4XnsT4Jc5mV42FIl5gGCJYCG"></div>

            <div style="height: 20px;">
            <div id="loding_gif" class="d-none" ><img  style="width: 50px;" src="http://localhost/wptask/wp-content/uploads/2024/03/Infinity-1s-200px.gif" alt=""></div>
            </div>


           <div class="py-3 text-align-center">
           <input type="submit" name="submit" id="submit" value="Check" class="btn btn-primary px-5 mr-1">
            <input type="button" id="reset" value="Reset" class="btn btn-secondary px-5 ml-1">
           </div>
        </form>
        <div id="result-table"></div>
    </div>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div> -->
      <div class="modal-body">
        <img src="http://localhost/wptask/wp-content/uploads/2024/03/infoPassportEvisa.jpg" alt="">
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>

    <script>
    jQuery(document).ready(function($) {
        $('#reset').click(function() {
            $('#result-table').empty(); // Clear the result table
            $('#result-check-form-ajax input[type="text"]').val(''); // Reset the form
            grecaptcha.reset(); // Reset reCAPTCHA
        });

        $('#result-check-form-ajax').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $('#loding_gif').removeClass('d-none'); // Show loading GIF

            $.ajax({
                type: 'POST',
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: formData + '&action=process_result_check_ajax',
                success: function(response) {
                    $('#result-table').html(response);
                },
                complete: function() {
                    $('#loding_gif').addClass('d-none'); // Hide loading GIF when request completes
                }
            });
        });
    });
</script>

    <?php
    return ob_get_clean();
}
add_shortcode('result_check_form', 'result_check_form_shortcode');

// Process Form Submission with CAPTCHA Verification
function process_result_check_ajax() {
    // Verify reCAPTCHA
    $captcha_response = $_POST['g-recaptcha-response'];
    $secret_key = '6Le_WIcpAAAAAB7FeqHO1hzRvRBYR4VDDewhvP88';
    $verify_response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha_response");
    $body = wp_remote_retrieve_body($verify_response);
    $result = json_decode($body);

    if ($result->success) {
        // CAPTCHA verification successful, process form submission
        $company_registration_no = sanitize_text_field($_POST['company_registration_no']);
        $passport_no = sanitize_text_field($_POST['passport_no']);

        $args = array(
            'post_type' => 'student',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'company_registration_no',
                    'value' => $company_registration_no,
                    'compare' => '='
                ),
                array(
                    'key' => 'passport_no',
                    'value' => $passport_no,
                    'compare' => '='
                )
            )
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            $students_data = array(); // Initialize array to store student data
            $output = '<table class="table table-striped table_class text-center">';
            $output .= '<tr><th>No</th><th>Reference Number</th><th>Passport No</th><th>Application Status</th><th>Place of Issue</th><th>Visa Validy</th><th>Visa Status</th></tr>';
            while ($query->have_posts()) {
                $query->the_post();
                $serial_no = get_post_meta(get_the_ID(), 'serial_no', true);
                $country = get_post_meta(get_the_ID(), 'country', true);
                $document_no = get_post_meta(get_the_ID(), 'document_no', true);
                $visa_validity = get_post_meta(get_the_ID(), 'visa_validity', true);
                $visa_status = get_post_meta(get_the_ID(), 'visa_status', true);
                $company_registration_no = get_post_meta(get_the_ID(), 'company_registration_no', true);
                $passport_no = get_post_meta(get_the_ID(), 'passport_no', true);
                // $last_pass_due_date = get_post_meta(get_the_ID(), 'last_pass_due_date', true);
                // $renewal_pass_duration = get_post_meta(get_the_ID(), 'renewal_pass_duration', true);
                // $current_pass_due_date = get_post_meta(get_the_ID(), 'current_pass_due_date', true);
                $application_status = get_post_meta(get_the_ID(), 'application_status', true);
                
                // Store student data in array
                $students_data[] = array(
                    'serial_no' => $serial_no,
                    'country' => $country,
                    'document_no' => $document_no,
                    'visa_validity' => $visa_validity,
                    'visa_status' => $visa_status,
                    'company_registration_no' => $company_registration_no,
                    'passport_no' => $passport_no,
                    // 'last_pass_due_date' => $last_pass_due_date,
                    // 'renewal_pass_duration' => $renewal_pass_duration,
                    // 'current_pass_due_date' => $current_pass_due_date,
                    'application_status' => $application_status,
                );
            }
            // Output each student's data in the table
            foreach ($students_data as $student) {
                $output .= "<tr>";
                $output .= "<td>{$student['serial_no']}</td>";
                $output .= "<td>{$student['document_no']}</td>";
                $output .= "<td>{$student['passport_no']}</td>";
                $output .= "<td>{$student['application_status']}</td>";
                $output .= "<td>{$student['country']}</td>";
               
                $output .= "<td>{$student['visa_validity']}</td>";
                $output .= "<td>{$student['visa_status']}</td>";
                // $output .= "<td>{$student['company_registration_no']}</td>";
                
                // $output .= "<td>{$student['last_pass_due_date']}</td>";
                // $output .= "<td>{$student['renewal_pass_duration']}</td>";
                // $output .= "<td>{$student['current_pass_due_date']}</td>";
              
                $output .= "</tr>";
            }
            $output .= '</table>';
            echo '<p class="bg-success text-white text-center p-2 my-3 table_class">Record Found</p>';
            echo $output;
        } else {
            echo '<p class="bg-danger text-white text-center p-2 mt-3 table_class">No Record  found !</p>';
        }

// This code for the resposive 

//         if ($query->have_posts()) {
//     $students_data = array(); // Initialize array to store student data

//     // Table for desktop and tablet view
//     $output_desktop = '<table class="table  table-striped table-bordered table_class text-center">';
//     $output_desktop .= '<tr><th>No</th><th>Reference Number</th><th>Passport No</th><th>Application Status</th><th>Place of Issue</th><th>Visa Validity</th><th>Visa Status</th></tr>';
    
//     // Table for mobile view
//     $output_mobile = '<div class="table-responsive table-responsive-sm d-md-none">'; // Hide on desktop and tablet
//     $output_mobile .= '<table class="table table-striped table-bordered table_class text-center">';
//     $output_mobile .= '<tr><th>Name</th><th>Output</th></tr>';
    
//     while ($query->have_posts()) {
//         $query->the_post();
//         $serial_no = get_post_meta(get_the_ID(), 'serial_no', true);
//         $country = get_post_meta(get_the_ID(), 'country', true);
//         $document_no = get_post_meta(get_the_ID(), 'document_no', true);
//         $visa_validity = get_post_meta(get_the_ID(), 'visa_validity', true);
//         $visa_status = get_post_meta(get_the_ID(), 'visa_status', true);
//         $company_registration_no = get_post_meta(get_the_ID(), 'company_registration_no', true);
//         $passport_no = get_post_meta(get_the_ID(), 'passport_no', true);
//         $application_status = get_post_meta(get_the_ID(), 'application_status', true);
        
//         // Store student data in array
//         $students_data[] = array(
//             'serial_no' => $serial_no,
//             'country' => $country,
//             'document_no' => $document_no,
//             'visa_validity' => $visa_validity,
//             'visa_status' => $visa_status,
//             'company_registration_no' => $company_registration_no,
//             'passport_no' => $passport_no,
//             'application_status' => $application_status,
//         );
        
//         // Add data to both tables
//         $output_desktop .= "<tr>";
//         $output_desktop .= "<td>{$serial_no}</td>";
//         $output_desktop .= "<td>{$document_no}</td>";
//         $output_desktop .= "<td>{$passport_no}</td>";
//         $output_desktop .= "<td>{$application_status}</td>";
//         $output_desktop .= "<td>{$country}</td>";
//         $output_desktop .= "<td>{$visa_validity}</td>";
//         $output_desktop .= "<td>{$visa_status}</td>";
//         $output_desktop .= "</tr>";
        
//         $output_mobile .= "<tr>";
//         $output_mobile .= "<td>Serial No</td><td>{$serial_no}</td>";
//         $output_mobile .= "</tr>";
//         $output_mobile .= "<tr>";
//         $output_mobile .= "<td>Document No</td><td>{$document_no}</td>";
//         $output_mobile .= "</tr>";
//         // Repeat this pattern for each field you want to display
//     }
    
//     // Close table tags
//     $output_desktop .= '</table>';
//     $output_mobile .= '</table> </div>';

//     // Echo success message and appropriate table based on screen size
//     echo '<p class="bg-success text-white text-center p-2 my-3 table_class">Record Found</p>';
//     echo '<div class="d-none d-md-block">'; // Show only on desktop and tablet
//     echo $output_desktop;
//     echo '</div>';
//     echo $output_mobile;
// } else {
//     echo '<p class="bg-danger text-white text-center p-2 mt-3 table_class">No Record found!</p>';
// }


        wp_reset_postdata();
    } else {
        // CAPTCHA verification failed
        echo '<p class="bg-danger text-white text-center p-2 mt-3 table_class" >CAPTCHA verification failed. Please try again.</p>';
    }
    die();
}
add_action('wp_ajax_process_result_check_ajax', 'process_result_check_ajax');
add_action('wp_ajax_nopriv_process_result_check_ajax', 'process_result_check_ajax');



