<?php
    $email_template = new \Woolentor\Modules\EmailReports\Email_Template();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report - <?php echo get_bloginfo('name'); ?></title>
    <?php echo $email_template->get_style(); ?>
</head>
<body>
    <div class="container">
        <?php 
            // Header Section
            echo $email_template->get_header($report_data);
        ?>

        <div class="content">
            <?php
                // Sales Summary
                echo $email_template->get_sales_summary($report_data);

                // Top Products
                if(isset($report_data['top_products'])) {
                    echo $email_template->get_top_products($report_data['top_products']);
                }

                // Additional sections can be added here
            ?>
        </div>

        <?php
            // Footer Section
            echo $email_template->get_footer();
        ?>
    </div>
</body>
</html>