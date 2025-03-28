<?php
/**
 * Project Card Component
 * 
 * Usage:
 * require_once 'components/project-card.php';
 * echo project_card([
 *   'title' => 'Bonsai Haven Project',
 *   'description' => 'Project description text...',
 *   'image' => '/Bonsai/Images/Index/IMG_6177.JPG',
 *   'location' => 'Kuala Lumpur, Malaysia',
 *   'project_type' => 'Bonsai Showcase',
 *   'designer' => 'Shazriq Azrin bin Senawi',
 *   'reverse' => false,
 *   'animation_image' => 'fade-right', // Optional: AOS animation type for image
 *   'animation_content' => 'fade-left', // Optional: AOS animation type for content
 *   'delay' => 0, // Optional: AOS delay in ms
 * ]);
 */

/**
 * Generate a project card
 * 
 * @param array $args Project card properties
 * @return string HTML for project card
 */
function project_card(array $args) {
    $defaults = [
        'title' => 'Project Title',
        'description' => 'Project description goes here.',
        'image' => '',
        'location' => 'Location',
        'project_type' => 'Project Type',
        'designer' => 'Designer Name',
        'reverse' => false,
        'animation_image' => 'fade-right',
        'animation_content' => 'fade-left',
        'delay' => 0,
    ];
    
    $args = array_merge($defaults, $args);
    
    $title = $args['title'];
    $description = $args['description'];
    $image = $args['image'];
    $location = $args['location'];
    $project_type = $args['project_type'];
    $designer = $args['designer'];
    $reverse = $args['reverse'];
    $animation_image = $reverse ? $args['animation_content'] : $args['animation_image'];
    $animation_content = $reverse ? $args['animation_image'] : $args['animation_content'];
    $delay = $args['delay'];
    
    // Build animation attributes for image
    $animation_image_attr = '';
    if (!empty($animation_image)) {
        $animation_image_attr .= ' data-aos="' . $animation_image . '"';
        if ($delay > 0) {
            $animation_image_attr .= ' data-aos-delay="' . $delay . '"';
        }
        $animation_image_attr .= ' data-aos-duration="1000"';
    }
    
    // Build animation attributes for content
    $animation_content_attr = '';
    if (!empty($animation_content)) {
        $animation_content_attr .= ' data-aos="' . $animation_content . '"';
        if ($delay > 0) {
            $animation_content_attr .= ' data-aos-delay="' . ($delay + 100) . '"';
        }
        $animation_content_attr .= ' data-aos-duration="1000"';
    }
    
    $html = '<div class="portfolio-item flex flex-col ' . ($reverse ? 'md:flex-row-reverse' : 'md:flex-row') . ' gap-8 mb-16">';
    
    // Image section
    $html .= '<div class="md:w-1/2"' . $animation_image_attr . '>';
    if (!empty($image)) {
        $html .= '<div class="overflow-hidden rounded-lg">';
        $html .= '<img src="' . $image . '" alt="' . $title . '" class="w-full h-auto object-cover transition-transform duration-500 hover:scale-105">';
        $html .= '</div>';
    }
    $html .= '</div>';
    
    // Content section
    $html .= '<div class="md:w-1/2 flex flex-col justify-center"' . $animation_content_attr . '>';
    $html .= '<h3 class="text-2xl font-bold mb-4 text-dark-olive">' . $title . '</h3>';
    $html .= '<p class="text-olive-dark mb-6">' . $description . '</p>';
    
    // Project details
    $html .= '<ul class="space-y-2 mb-6">';
    $html .= '<li class="flex"><span class="font-semibold w-24 text-dark-olive">Location:</span><span class="text-olive-dark">' . $location . '</span></li>';
    $html .= '<li class="flex"><span class="font-semibold w-24 text-dark-olive">Project:</span><span class="text-olive-dark">' . $project_type . '</span></li>';
    $html .= '<li class="flex"><span class="font-semibold w-24 text-dark-olive">Designer:</span><span class="text-olive-dark">' . $designer . '</span></li>';
    $html .= '</ul>';
    
    $html .= '<a href="portfolio.php" class="text-primary font-semibold hover:underline inline-flex items-center">';
    $html .= 'Browse Book Collection';
    $html .= '<svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">';
    $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>';
    $html .= '</svg>';
    $html .= '</a>';
    
    $html .= '</div>';
    
    $html .= '</div>';
    
    return $html;
} 