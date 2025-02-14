/** LAYOUT FOR ONE SINGLE POST TYPE AND PROJECT WILL BE DISPLAY IN THE FRONT END

<?php
get_header(); //Includes standard theme header

if (have_posts()) : //If loop that checks for posts and displays title and content
    while (have_posts()) : the_post(); ?>
    <div class='opv-project-wrapper'>
        <div class="opv-single-project">
            <h1 class="opv-project-title"><?php the_title(); ?></h1>
            <div class="opv-project-content">
                <?php the_content(); ?>
            </div>
            <div class="opv-project-details"> /** This displays the meta info  */
                <p><strong>Start Date:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), '_opv_project_start_date', true)); ?></p>
                <p><strong>End Date:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), '_opv_project_end_date', true)); ?></p>
                <p><strong>Status:</strong> <?php
                    $project_status = get_post_meta(get_the_ID(), '_opv_project_status', true);
                    $status_labels = array(
                        'not_started' => __('Not Yet Started', 'our-project-vault'),
                        'in_progress' => __('In Progress', 'our-project-vault'),
                        'complete' => __('Complete', 'our-project-vault'),
                    );
                    echo esc_html($status_labels[$project_status]);
                ?></p>
            </div>
        </div>
    </div>
    <?php endwhile;
endif;

get_footer(); //Includes standard theme footer
